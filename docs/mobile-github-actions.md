# GitHub Actions untuk Android + iPhone

## Workflow yang tersedia

- Android APK: `.github/workflows/android-apk.yml`
- iPhone IPA: `.github/workflows/ios-ipa.yml`

## Cara jalanin Android

1. Buka tab `Actions`.
2. Pilih `Build Android APK`.
3. Klik `Run workflow`.
4. Pilih `build_type`:
   - `debug` untuk cepat testing.
   - `release` untuk build release APK.

Artifact hasil:
- `android-apk-debug` atau `android-apk-release`

## Cara jalanin iPhone

1. Isi dulu secrets iOS (lihat `docs/ios-ipa-ci.md`).
2. Buka tab `Actions`.
3. Pilih `Build iOS IPA`.
4. Klik `Run workflow`.
5. Pilih `export_method` dan `bundle_id`.

Artifact hasil:
- `ios-ipa-<export_method>`
- `ios-archive-and-logs`
