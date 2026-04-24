#!/usr/bin/env bash
set -euo pipefail

uid="$(id -u)"
domain="gui/${uid}"

launchctl bootout "${domain}/com.rigel.laravel-serve" 2>/dev/null || true
launchctl bootout "${domain}/com.rigel.cloudflared-quick" 2>/dev/null || true

rm -f "${HOME}/Library/LaunchAgents/com.rigel.laravel-serve.plist"
rm -f "${HOME}/Library/LaunchAgents/com.rigel.cloudflared-quick.plist"

echo "OK: launchd services removed."
