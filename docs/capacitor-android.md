# Convert web ke APK (Android) pakai Capacitor

> Catatan: URL `*.trycloudflare.com` bisa berubah kalau tunnel restart, jadi APK kamu bisa “blank” kalau URL-nya berubah.
> Untuk yang stabil, pakai domain/subdomain sendiri di Cloudflare (Named Tunnel).

## 1) Pastikan web bisa diakses via tunnel

- Pastikan Laravel jalan dan tunnel jalan (contoh URL):
  - `https://proudly-tri-checks-apache.trycloudflare.com`

## 2) Install dependency Capacitor

```bash
npm i
```

## 3) Generate project Android

```bash
npx cap add android
npx cap sync android
```

## 4) Build APK lewat Android Studio

```bash
npx cap open android
```

Di Android Studio:
- Tunggu Gradle sync selesai
- Run ke device/emulator, atau buat APK:
  - **Build → Build Bundle(s) / APK(s) → Build APK(s)**

## 5) Ganti URL tunnel kalau berubah

Edit `capacitor.config.ts` lalu update native project:

```bash
npx cap sync android
```

