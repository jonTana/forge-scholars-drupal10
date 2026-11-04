#!/bin/sh
set -eu
cd "$(dirname "$0")/.."
find . -name '*.b64' | while read -r f; do
  out="${f%.b64}"
  base64 -d "$f" > "$out"
  rm -f "$f"
done
echo "Media restored."
