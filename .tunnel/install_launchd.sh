#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
uid="$(id -u)"
domain="gui/${uid}"
php_bin="$(command -v php)"

if [[ -z "${php_bin}" ]]; then
  echo "ERROR: php not found in PATH." >&2
  exit 1
fi

launch_agents_dir="${HOME}/Library/LaunchAgents"
mkdir -p "${launch_agents_dir}"

laravel_plist="${launch_agents_dir}/com.rigel.laravel-serve.plist"
cloudflared_plist="${launch_agents_dir}/com.rigel.cloudflared-quick.plist"

cat >"${laravel_plist}" <<PLIST
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
  <key>Label</key>
  <string>com.rigel.laravel-serve</string>

  <key>WorkingDirectory</key>
  <string>${repo_root}</string>

  <key>ProgramArguments</key>
  <array>
    <string>${php_bin}</string>
    <string>artisan</string>
    <string>serve</string>
    <string>--host=127.0.0.1</string>
    <string>--port=8000</string>
  </array>

  <key>RunAtLoad</key>
  <true/>
  <key>KeepAlive</key>
  <true/>

  <key>StandardOutPath</key>
  <string>${repo_root}/.tunnel/launchd-laravel.log</string>
  <key>StandardErrorPath</key>
  <string>${repo_root}/.tunnel/launchd-laravel.log</string>
</dict>
</plist>
PLIST

cat >"${cloudflared_plist}" <<PLIST
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
  <key>Label</key>
  <string>com.rigel.cloudflared-quick</string>

  <key>ProgramArguments</key>
  <array>
    <string>/usr/local/bin/cloudflared</string>
    <string>tunnel</string>
    <string>--url</string>
    <string>http://127.0.0.1:8000</string>
    <string>--protocol</string>
    <string>http2</string>
    <string>--no-autoupdate</string>
    <string>--loglevel</string>
    <string>info</string>
  </array>

  <key>RunAtLoad</key>
  <true/>
  <key>KeepAlive</key>
  <true/>

  <key>StandardOutPath</key>
  <string>${repo_root}/.tunnel/launchd-cloudflared.log</string>
  <key>StandardErrorPath</key>
  <string>${repo_root}/.tunnel/launchd-cloudflared.log</string>
</dict>
</plist>
PLIST

mkdir -p "${repo_root}/.tunnel"

launchctl bootout "${domain}/com.rigel.laravel-serve" 2>/dev/null || true
launchctl bootout "${domain}/com.rigel.cloudflared-quick" 2>/dev/null || true

launchctl bootstrap "${domain}" "${laravel_plist}"
launchctl bootstrap "${domain}" "${cloudflared_plist}"

launchctl enable "${domain}/com.rigel.laravel-serve" || true
launchctl enable "${domain}/com.rigel.cloudflared-quick" || true

launchctl kickstart -k "${domain}/com.rigel.laravel-serve"
launchctl kickstart -k "${domain}/com.rigel.cloudflared-quick"

echo "OK: launchd services installed & started."
echo "- Logs: ${repo_root}/.tunnel/launchd-laravel.log"
echo "- Logs: ${repo_root}/.tunnel/launchd-cloudflared.log"
