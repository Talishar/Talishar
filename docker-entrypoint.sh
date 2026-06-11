#!/bin/sh
set -e

mkdir -p /run/php
chown www-data:www-data /run/php

php-fpm --nodaemonize &
FPM_PID=$!

# Wait for the FPM socket before Apache starts — detects config errors early
# and eliminates the race where the first request arrives before FPM is ready.
i=0
while [ ! -S /run/php/php-fpm.sock ]; do
    if ! kill -0 "$FPM_PID" 2>/dev/null; then
        echo "php-fpm failed to start" >&2
        exit 1
    fi
    i=$((i + 1))
    if [ "$i" -ge 100 ]; then
        echo "Timed out waiting for php-fpm socket" >&2
        exit 1
    fi
    sleep 0.1
done

# Set trap BEFORE starting Apache so no SIGTERM is missed during startup.
# apache2ctl graceful-stop sends the correct signal (via PID file) to the apache2
# binary itself — kill "$APACHE_PID" would only kill the apache2ctl wrapper shell,
# leaving apache2 as an orphan that gets SIGKILLed with no chance to drain.
trap 'apache2ctl graceful-stop 2>/dev/null; kill "$FPM_PID" 2>/dev/null; exit 0' TERM INT QUIT

apache2ctl -D FOREGROUND &
APACHE_PID=$!

# Watchdog: if either process exits unexpectedly, kill the other and exit
# non-zero so Docker's restart: always policy restarts the container.
# (apache2ctl stays alive while apache2 runs, so kill -0 correctly tracks apache2 health.)
while kill -0 "$FPM_PID" 2>/dev/null && kill -0 "$APACHE_PID" 2>/dev/null; do
    sleep 5
done

echo "A supervised process exited unexpectedly, shutting down container" >&2
apache2ctl graceful-stop 2>/dev/null || true
kill "$FPM_PID" "$APACHE_PID" 2>/dev/null
exit 1
