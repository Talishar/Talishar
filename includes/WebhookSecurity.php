<?php

// Shared policy for user-configurable match result webhooks: who may use the feature,
// and where their URL is allowed to point.
//
// Eligibility is a supporter perk — a paid Metafy tier, a Patreon supporter, or a
// Talishar contributor. Free Talishar community members do not qualify, which is why
// this checks tiers rather than IsTalisharMetafySupporter().
//
// Validating only at save time is not sufficient: curl re-resolves the hostname when
// the request is actually sent, so a low-TTL record can answer with a public address
// during validation and a private one at send time (DNS rebinding). Every address the
// host resolves to is therefore checked, and the vetted address is pinned onto the
// curl handle via CURLOPT_RESOLVE so no second resolution can occur.

if (!function_exists('IsMatchResultWebhookEligible')) {
    /**
     * Whether $uid may use match result webhooks.
     *
     * $metafyTiers is passed in rather than looked up so the join-time caller can reuse
     * the tiers it has already fetched instead of issuing a second query. Pass null to
     * have them fetched here (the profile/save path, which has no tiers to hand).
     */
    function IsMatchResultWebhookEligible(?string $uid, $metafyTiers = null): bool
    {
        if (empty($uid) || $uid === "-") return false;

        include_once __DIR__ . '/MetafyHelper.php';
        include_once __DIR__ . '/ModeratorList.inc.php';

        if ($metafyTiers === null) {
            $metafyTiers = GetMetafyTiersFromDatabase($uid);
        }
        if (HasPaidMetafyTier($metafyTiers)) return true;

        // Patreon supporters keep the perk; they are treated as supporters everywhere
        // else, so gating them out would read as losing something they already pay for.
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (($_SESSION["isPatron"] ?? false) || ($_SESSION["isPvtVoidPatron"] ?? false)) {
                return true;
            }
        }

        // Contributors, so the team can exercise the feature without a subscription.
        return IsUserContributor($uid);
    }
}

if (!function_exists('ResolveWebhookHostToPublicIp')) {
    /**
     * Resolve $host to all of its A/AAAA addresses and reject the host outright if any
     * one of them is private or reserved. Checking every address (rather than just the
     * first) closes the split-horizon case where a host publishes both a public A record
     * and, say, an AAAA record pointing at ::1.
     *
     * Returns the address to pin the connection to, or null with $error populated.
     */
    function ResolveWebhookHostToPublicIp(string $host, ?string &$error = null): ?string
    {
        $error = null;

        // A literal address needs no lookup, just the range check.
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            if (!filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $error = "Webhook URL must point to a public host.";
                return null;
            }
            return $host;
        }

        $addresses = [];

        $records = @dns_get_record($host, DNS_A | DNS_AAAA);
        if (is_array($records)) {
            foreach ($records as $record) {
                if (!empty($record['ip'])) $addresses[] = $record['ip'];
                elseif (!empty($record['ipv6'])) $addresses[] = $record['ipv6'];
            }
        }

        // dns_get_record can fail on some resolver configurations; fall back to the
        // system resolver so a working hostname is not rejected spuriously.
        if (empty($addresses)) {
            $v4 = @gethostbynamel($host);
            if (is_array($v4)) $addresses = $v4;
        }

        $addresses = array_values(array_unique(array_filter($addresses)));

        if (empty($addresses)) {
            $error = "Webhook URL hostname could not be resolved. Please check the URL and try again.";
            return null;
        }

        $pinned = null;
        foreach ($addresses as $address) {
            if (!filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $error = "Webhook URL must point to a public host.";
                return null;
            }
            // Prefer IPv4 for the pin; widest compatibility with outbound egress rules.
            if ($pinned === null || filter_var($address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                if ($pinned === null || !filter_var($pinned, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $pinned = $address;
                }
            }
        }

        return $pinned;
    }
}

if (!function_exists('ValidateWebhookUrl')) {
    /**
     * Full save-time validation. Returns an error message, or null when the URL is
     * acceptable. On success $pinnedIp receives the vetted address.
     */
    function ValidateWebhookUrl(string $url, ?string &$pinnedIp = null): ?string
    {
        $pinnedIp = null;

        if (strlen($url) > 2048) {
            return "Webhook URL is too long (max 2048 characters).";
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return "Invalid webhook URL format.";
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array($scheme, ['http', 'https'], true)) {
            return "Webhook URL must use http or https.";
        }

        $host = parse_url($url, PHP_URL_HOST);
        if (empty($host)) {
            return "Webhook URL has no host.";
        }

        // Strip the brackets around a literal IPv6 host before validating it.
        $host = trim($host, '[]');

        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return "Webhook URL must use a hostname, not a bare IP address.";
        }

        $error = null;
        $ip = ResolveWebhookHostToPublicIp($host, $error);
        if ($ip === null) {
            return $error ?? "Webhook URL could not be validated.";
        }

        $pinnedIp = $ip;
        return null;
    }
}

if (!function_exists('ApplyWebhookConnectionPinning')) {
    /**
     * Re-validate at send time and pin the vetted address onto the handle so curl
     * cannot perform its own resolution. Returns false if the URL is no longer safe.
     */
    function ApplyWebhookConnectionPinning($ch, string $url): bool
    {
        $pinnedIp = null;
        if (ValidateWebhookUrl($url, $pinnedIp) !== null || $pinnedIp === null) {
            return false;
        }

        $host = trim((string)parse_url($url, PHP_URL_HOST), '[]');
        $scheme = parse_url($url, PHP_URL_SCHEME);
        $port = parse_url($url, PHP_URL_PORT) ?: ($scheme === 'https' ? 443 : 80);

        curl_setopt($ch, CURLOPT_RESOLVE, ["$host:$port:$pinnedIp"]);
        return true;
    }
}
