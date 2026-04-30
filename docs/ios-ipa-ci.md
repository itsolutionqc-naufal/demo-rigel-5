# Build iOS `.ipa` via GitHub Actions

Workflow: `.github/workflows/ios-ipa.yml`

## 1) Secrets yang wajib di GitHub

Tambahkan di `Settings -> Secrets and variables -> Actions`:

- `APPLE_DEVELOPMENT_TEAM`: Team ID Apple Developer.
- `IOS_CERTIFICATE_BASE64`: isi file certificate `.p12` yang sudah di-`base64`.
- `IOS_CERTIFICATE_PASSWORD`: password certificate `.p12`.
- `IOS_PROVISIONING_PROFILE_BASE64`: isi file `.mobileprovision` yang di-`base64`.
- `IOS_PROVISIONING_PROFILE_NAME`: nama provisioning profile (harus sama dengan di Apple Developer).
- `KEYCHAIN_PASSWORD`: password sementara untuk keychain di runner.

Contoh encode base64 (macOS):

```bash
base64 -i ios_distribution.p12 | pbcopy
base64 -i profile.mobileprovision | pbcopy
```

## 2) Jalankan workflow

1. Buka tab `Actions`.
2. Pilih workflow `Build iOS IPA`.
3. Klik `Run workflow`.
4. Pilih:
   - `export_method`: `development`, `ad-hoc`, `app-store`, atau `enterprise`.
   - `bundle_id`: default `com.rigel.webview`.

## 3) Hasil build

- Artifact `.ipa`: `ios-ipa-<export_method>`
- Artifact debug tambahan: `ios-archive-and-logs`

## 4) Catatan penting

- Untuk install ke iPhone pribadi, biasanya pakai `development` (device terdaftar pada provisioning profile).
- Untuk tester banyak user, pakai `ad-hoc` atau `app-store` (TestFlight).
