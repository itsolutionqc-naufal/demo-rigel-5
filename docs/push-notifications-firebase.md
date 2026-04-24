# Push Notification (FCM) untuk APK (Capacitor) + Laravel

Dokumen ini menjelaskan cara:
- Mengaktifkan **Firebase Cloud Messaging (FCM)** untuk Android (dan iOS optional).
- Mengambil file yang dibutuhkan dari Firebase.
- Menghubungkan token device ke user login di Laravel.
- Mengirim notif otomatis saat transaksi **success/failed**.

## 1) Firebase setup (Android)

1. Buka Firebase Console → **Project Settings** → tab **General**
2. Klik **Add app** → pilih **Android**
3. Isi:
   - Android package name: `com.rigel.webview`
4. Download **`google-services.json`**
5. Taruh file ke:
   - `android/app/google-services.json`
6. Sync:
   - `npx cap sync android`

## 2) Firebase setup (iOS) (butuh Xcode + Apple Developer)

1. Firebase Console → **Add app** → **iOS**
2. Bundle ID: `com.rigel.webview`
3. Download **`GoogleService-Info.plist`**
4. Masukkan ke project iOS (Xcode): `ios/App/App/GoogleService-Info.plist`
5. Xcode Capabilities:
   - **Push Notifications**
   - **Background Modes** → centang **Remote notifications**
6. Apple Developer portal:
   - Buat **APNs Auth Key (.p8)** → catat **Key ID** dan **Team ID**
7. Firebase Console → Project Settings → **Cloud Messaging**:
   - Upload APNs key `.p8` + Key ID + Team ID

## 3) Service account JSON untuk server (Laravel)

1. Firebase Console → Project Settings → tab **Service accounts**
2. Klik **Generate new private key** (JSON)
3. Simpan file di server, contoh:
   - `storage/app/firebase-service-account.json`
4. Tambah ke `.env`:
   - `FIREBASE_CREDENTIALS=/full/path/to/storage/app/firebase-service-account.json`

> Jangan commit file JSON ini ke git.

## 4) Token device tersimpan ke user

Saat user login lewat APK, app akan:
- minta permission push
- ambil **FCM token**
- POST ke endpoint Laravel: `POST /device-tokens`

Cek data tersimpan di tabel `device_tokens`.

## 5) Trigger notif transaksi

Notif dikirim otomatis ketika status transaksi berubah menjadi:
- `success`
- `failed`

Judul & isi notif mengikuti yang ada di `app/Services/NotificationService.php`.

## 6) Build Android APK

```bash
cd android
export JAVA_HOME="/Applications/Android Studio.app/Contents/jbr/Contents/Home"
export PATH="$JAVA_HOME/bin:$PATH"
./gradlew assembleDebug
```

Output APK:
- `android/app/build/outputs/apk/debug/app-debug.apk`

## 7) Test cepat (Firebase Console)

1. Install APK di HP
2. Login
3. Ambil token di DB `device_tokens.token`
4. Firebase Console → Cloud Messaging → test send ke token tersebut

