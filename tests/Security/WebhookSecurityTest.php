<?php

namespace Talishar\Tests\Security;

use PHPUnit\Framework\TestCase;

/**
 * Webhook URL Validation Tests
 * Covers the checks in ValidateWebhookUrl that run before any DNS lookup, so these
 * tests need no network access.
 */
class WebhookSecurityTest extends TestCase
{
    /**
     * @dataProvider nonStandardPortProvider
     */
    public function testNonStandardPortsAreRejected(string $url)
    {
        $this->assertSame("Webhook URL must use port 80 or 443.", ValidateWebhookUrl($url));
    }

    public static function nonStandardPortProvider(): array
    {
        return [
            'alt http' => ['http://example.com:8080/hook'],
            'smtp'     => ['http://example.com:25/hook'],
            'ssh'      => ['https://example.com:22/hook'],
            'high'     => ['https://example.com:65535/hook'],
        ];
    }

    /**
     * @dataProvider standardPortProvider
     */
    public function testStandardPortsPassThePortCheck(string $url)
    {
        // Resolution may still fail without network access; only the port check is under test.
        $this->assertNotSame("Webhook URL must use port 80 or 443.", ValidateWebhookUrl($url));
    }

    public static function standardPortProvider(): array
    {
        return [
            'implicit' => ['https://example.com/hook'],
            'http 80'  => ['http://example.com:80/hook'],
            'https 443' => ['https://example.com:443/hook'],
        ];
    }

    public function testBareIpAddressIsRejected()
    {
        $this->assertSame(
            "Webhook URL must use a hostname, not a bare IP address.",
            ValidateWebhookUrl('http://169.254.169.254/latest/meta-data/')
        );
    }

    public function testNonHttpSchemeIsRejected()
    {
        $this->assertSame("Webhook URL must use http or https.", ValidateWebhookUrl('ftp://example.com/hook'));
    }
}
