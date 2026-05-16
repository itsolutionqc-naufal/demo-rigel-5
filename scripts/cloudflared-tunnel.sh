#!/usr/bin/env bash
set -euo pipefail

if ! command -v cloudflared >/dev/null 2>&1; then
  echo "cloudflared not found. Install Cloudflare Tunnel first." >&2
  exit 1
fi

port="${PORT:-8000}"
local_url="${LOCAL_URL:-http://127.0.0.1:${port}}"

config_path="${CF_CONFIG_PATH:-.cloudflared/config.yml}"
tunnel_name="${CF_TUNNEL_NAME:-}"

if [[ -n "${tunnel_name}" ]]; then
  if [[ ! -f "${config_path}" ]]; then
    echo "Missing ${config_path}." >&2
    echo "Copy scripts/cloudflared-config.example.yml -> ${config_path} and fill it in." >&2
    exit 1
  fi

echo "Starting Cloudflare Tunnel (named) ..."
echo "Config: ${config_path}"
exec cloudflared tunnel --config "${config_path}" run "${tunnel_name}"
fi

echo "CF_TUNNEL_NAME not set; starting quick tunnel (random trycloudflare.com URL) ..."
echo "Local: ${local_url}"
exec cloudflared tunnel --url "${local_url}"

