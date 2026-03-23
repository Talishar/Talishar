#!/usr/bin/env python3
"""
analyze_access_log.py - Summarize an Apache access log by IP and endpoint.

Usage:
    python3 analyze_access_log.py <time_filter> [logfile] [options]

Examples:
    # All requests in minute 13:14 (uses default log: talishar.net_ssl_access.log)
    python3 analyze_access_log.py "13:14:"

    # Collapse query params
    python3 analyze_access_log.py "13:14:" --strip-params

    # Specific hour on a specific date
    python3 analyze_access_log.py "23/Mar/2026:13:"

    # Show only the top 20 busiest IP+endpoint combos
    python3 analyze_access_log.py "13:14:" --strip-params --top 20

    # Summary per IP only (ignore endpoint breakdown)
    python3 analyze_access_log.py "13:14:" --by-ip

    # Specify a different log file
    python3 analyze_access_log.py "13:14:" /var/log/apache2/other.log --strip-params

    # Filter out noise — only rows with 3+ hits
    python3 analyze_access_log.py "13:14:" --strip-params --min-count 3

Log line format expected (Apache Combined Log):
    001.001.001.001 - - [23/Mar/2026:13:14:21 +0000] "GET /path?q=1 HTTP/1.1" 200 1234 "ref" "ua"
"""

import sys
import re
import argparse
from collections import defaultdict


# Parses: IP - - [timestamp] "METHOD path proto" status bytes ["ref" "ua"]
_LOG_RE = re.compile(
    r'(?P<ip>\S+)'          # IP address
    r' \S+ \S+ '            # ident, authuser
    r'\[(?P<ts>[^\]]+)\] '  # [timestamp]
    r'"(?P<method>\S+) '    # "METHOD
    r'(?P<path>\S+) '       # /path?query
    r'\S+" '                # HTTP/version"
    r'(?P<status>\d+) '     # status code
    r'(?P<bytes>\S+)'       # bytes (may be -)
)


def parse_line(line):
    m = _LOG_RE.match(line)
    if not m:
        return None
    return m.group('ip'), m.group('ts'), m.group('method'), m.group('path'), m.group('status')


def endpoint_key(path, strip_params):
    return path.split('?')[0] if strip_params else path


def main():
    parser = argparse.ArgumentParser(
        description='Summarize an Apache access log by IP and endpoint.',
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog=__doc__,
    )
    parser.add_argument('time_filter',  help='Substring to match in the timestamp, e.g. "13:14:" or "23/Mar/2026:13:"')
    parser.add_argument('logfile',      nargs='?', default='talishar.net_ssl_access.log',
                        help='Path to the access log file (default: talishar.net_ssl_access.log)')
    parser.add_argument('--strip-params', action='store_true',
                        help='Strip query strings so all calls to the same .php file are grouped together')
    parser.add_argument('--top', type=int, default=0,
                        help='Limit output to the top N rows (0 = all, default: 0)')
    parser.add_argument('--by-ip', action='store_true',
                        help='Show a per-IP summary only, without the endpoint breakdown')
    parser.add_argument('--min-count', type=int, default=1,
                        help='Only show rows with at least this many requests (default: 1)')
    args = parser.parse_args()

    # ------------------------------------------------------------------ #
    # Read + filter log                                                    #
    # ------------------------------------------------------------------ #
    ip_endpoint: dict[tuple, int] = defaultdict(int)
    ip_total:    dict[str, int]   = defaultdict(int)
    status_count: dict[str, int]  = defaultdict(int)
    total = 0
    skipped = 0

    try:
        with open(args.logfile, 'r', errors='replace') as fh:
            for line in fh:
                if args.time_filter not in line:
                    continue
                parsed = parse_line(line)
                if parsed is None:
                    skipped += 1
                    continue
                ip, _ts, _method, path, status = parsed
                total += 1
                ep = endpoint_key(path, args.strip_params)
                ip_endpoint[(ip, ep)] += 1
                ip_total[ip] += 1
                status_count[status] += 1
    except FileNotFoundError:
        print(f"Error: '{args.logfile}' not found.", file=sys.stderr)
        sys.exit(1)

    # ------------------------------------------------------------------ #
    # Report                                                               #
    # ------------------------------------------------------------------ #
    print(f"\n{'='*60}")
    print(f"  Access Log Analysis")
    print(f"{'='*60}")
    print(f"  Log file  : {args.logfile}")
    print(f"  Filter    : '{args.time_filter}'")
    print(f"  Matched   : {total:,} requests  ({skipped} unparseable lines skipped)")
    print(f"  Unique IPs: {len(ip_total):,}")
    print(f"{'='*60}\n")

    if total == 0:
        print("No matching lines found.")
        return

    # Status code breakdown
    print("--- HTTP Status Codes ---")
    for status in sorted(status_count):
        bar = '#' * min(40, status_count[status])
        print(f"  {status}  {status_count[status]:>7,}  {bar}")
    print()

    # ------------------------------------------------------------------ #
    # Per-IP summary                                                       #
    # ------------------------------------------------------------------ #
    if args.by_ip:
        rows = sorted(ip_total.items(), key=lambda x: -x[1])
        rows = [(ip, c) for ip, c in rows if c >= args.min_count]
        if args.top:
            rows = rows[:args.top]

        print(f"--- Requests by IP ({len(rows)} shown) ---")
        print(f"  {'Count':>7}  {'IP Address'}")
        print(f"  {'-------':>7}  {'-'*20}")
        for ip, count in rows:
            print(f"  {count:>7,}  {ip}")

    # ------------------------------------------------------------------ #
    # Per-IP + endpoint breakdown                                          #
    # ------------------------------------------------------------------ #
    else:
        rows = sorted(ip_endpoint.items(), key=lambda x: -x[1])
        rows = [((ip, ep), c) for (ip, ep), c in rows if c >= args.min_count]
        if args.top:
            rows = rows[:args.top]

        print(f"--- Requests by IP + Endpoint ({len(rows)} shown) ---")
        col_ep  = 55   # truncation width for endpoint column
        header_ip = f"{'IP Address':<20}"
        header_ep = f"{'Endpoint'}"
        print(f"  {'Count':>7}  {header_ip}  {header_ep}")
        print(f"  {'-------':>7}  {'':->20}  {'-'*col_ep}")
        for (ip, ep), count in rows:
            ep_display = ep if len(ep) <= col_ep else ep[:col_ep - 3] + '...'
            print(f"  {count:>7,}  {ip:<20}  {ep_display}")

    print()


if __name__ == '__main__':
    main()
