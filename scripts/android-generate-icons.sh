#!/usr/bin/env bash
set -euo pipefail

if ! command -v sips >/dev/null 2>&1; then
  echo "sips not found (macOS built-in). This script only supports macOS." >&2
  exit 1
fi

src="${1:-public/images/logo-rigel-apk.png}"
pad_color="${PAD_COLOR:-FFFFFF}"

if [[ ! -f "${src}" ]]; then
  echo "Source icon not found: ${src}" >&2
  exit 1
fi

res_dir="android/app/src/main/res"
if [[ ! -d "${res_dir}" ]]; then
  echo "Android res directory not found: ${res_dir}" >&2
  exit 1
fi

tmp_dir="$(mktemp -d 2>/dev/null || mktemp -d -t rigel-icons)"
cleanup() {
  rm -rf "${tmp_dir}"
}
trap cleanup EXIT

make_padded_square_png() {
  local target_px="$1"
  local out_path="$2"

  # Round(target * 0.75) using integer math.
  local inner_px=$(( (target_px * 75 + 50) / 100 ))
  local scaled="${tmp_dir}/scaled_${target_px}.png"

  # sips can be noisy (prints CGColor), so silence output but keep exit codes.
  sips -z "${inner_px}" "${inner_px}" "${src}" --out "${scaled}" >/dev/null 2>&1
  sips -p "${target_px}" "${target_px}" "${scaled}" --padColor "${pad_color}" --out "${out_path}" >/dev/null 2>&1
}

# Legacy launcher icons (ic_launcher + ic_launcher_round)
make_padded_square_png 48  "${res_dir}/mipmap-mdpi/ic_launcher.png"
make_padded_square_png 48  "${res_dir}/mipmap-mdpi/ic_launcher_round.png"
make_padded_square_png 72  "${res_dir}/mipmap-hdpi/ic_launcher.png"
make_padded_square_png 72  "${res_dir}/mipmap-hdpi/ic_launcher_round.png"
make_padded_square_png 96  "${res_dir}/mipmap-xhdpi/ic_launcher.png"
make_padded_square_png 96  "${res_dir}/mipmap-xhdpi/ic_launcher_round.png"
make_padded_square_png 144 "${res_dir}/mipmap-xxhdpi/ic_launcher.png"
make_padded_square_png 144 "${res_dir}/mipmap-xxhdpi/ic_launcher_round.png"
make_padded_square_png 192 "${res_dir}/mipmap-xxxhdpi/ic_launcher.png"
make_padded_square_png 192 "${res_dir}/mipmap-xxxhdpi/ic_launcher_round.png"

# Adaptive icon foreground layer (ic_launcher_foreground)
make_padded_square_png 108 "${res_dir}/mipmap-mdpi/ic_launcher_foreground.png"
make_padded_square_png 162 "${res_dir}/mipmap-hdpi/ic_launcher_foreground.png"
make_padded_square_png 216 "${res_dir}/mipmap-xhdpi/ic_launcher_foreground.png"
make_padded_square_png 324 "${res_dir}/mipmap-xxhdpi/ic_launcher_foreground.png"
make_padded_square_png 432 "${res_dir}/mipmap-xxxhdpi/ic_launcher_foreground.png"

echo "OK: Android launcher icons updated from ${src}"
