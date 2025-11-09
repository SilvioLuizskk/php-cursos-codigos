#!/usr/bin/env bash
set -euo pipefail

if [ -z "${CLOUDFLARE_ZONE_ID:-}" ] || [ -z "${CLOUDFLARE_API_TOKEN:-}" ]; then
  echo "Please set CLOUDFLARE_ZONE_ID and CLOUDFLARE_API_TOKEN"
  exit 1
fi

if [ "$#" -lt 1 ]; then
  echo "Usage: $0 <url1> [url2 ...]"
  exit 1
fi

ZONE_ID="$CLOUDFLARE_ZONE_ID"
TOKEN="$CLOUDFLARE_API_TOKEN"

URLS=$(printf '%s\n' "$@" | jq -R -s -c 'split("\n")[:-1]')

curl -s -X POST "https://api.cloudflare.com/client/v4/zones/$ZONE_ID/purge_cache" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  --data "{\"files\": $URLS }" | jq '.'
