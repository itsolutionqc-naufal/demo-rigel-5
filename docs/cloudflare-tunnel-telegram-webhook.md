# Cloudflare Tunnel (Free) untuk Telegram Webhook

Tujuan: bikin endpoint HTTPS publik (pakai domain Cloudflare kamu) yang nge-forward ke app lokal, supaya Telegram bisa hit webhook `POST /telegram/webhook`.

## 1) Jalankan app lokal

Pilih salah satu:

- `php artisan serve --host=127.0.0.1 --port=8000`
- atau kalau kamu pakai workflow lain, pastikan app bisa diakses di `http://127.0.0.1:8000`

## 2) Setup Cloudflare Tunnel (sekali saja)

Login Cloudflare:

- `cloudflared tunnel login`

Buat tunnel (contoh nama):

- `cloudflared tunnel create demo_rigel_5`

Route DNS ke tunnel (contoh subdomain webhook):

- `cloudflared tunnel route dns demo_rigel_5 webhook.agencyrigel.com`

## 3) Buat config tunnel di project

Copy template:

- `mkdir -p .cloudflared`
- `cp scripts/cloudflared-config.example.yml .cloudflared/config.yml`

Lalu edit `.cloudflared/config.yml`:

- `credentials-file`: isi path absolut file JSON tunnel (biasanya ada di `~/.cloudflared/<UUID>.json`)
- `hostname`: ganti ke hostname kamu (mis: `webhook.agencyrigel.com`)
- `service`: pastikan port sama dengan app lokal (default `8000`)

Catatan: folder `.cloudflared/` sudah di-ignore (biar credentials nggak ikut ke repo).

## 4) Jalankan tunnel

Set environment variable tunnel name, lalu jalankan:

- `CF_TUNNEL_NAME=demo_rigel_5 scripts/cloudflared-tunnel.sh`

Kalau kamu mau pakai port lain:

- `CF_TUNNEL_NAME=demo_rigel_5 PORT=8000 scripts/cloudflared-tunnel.sh`

## 5) Set APP_URL ke domain tunnel

Di `.env` set:

- `APP_URL=https://webhook.agencyrigel.com`

## 6) Set Telegram webhook ke domain tunnel

Kamu bisa pakai salah satu:

- Pakai `APP_URL` (tanpa argumen URL):
  - `php artisan telegram:setup-webhook --all-active`
- Atau explicit URL (sesuai contoh kamu):
  - `php artisan telegram:setup-webhook https://webhook.agencyrigel.com/telegram/webhook --all-active`

Kalau endpointnya bisa diakses, ping:

- `curl -i https://webhook.agencyrigel.com/telegram/webhook`

