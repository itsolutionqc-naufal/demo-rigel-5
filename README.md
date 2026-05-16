# 🪙 Rigel Coins - Digital Coin Trading Platform

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-Proprietary-red.svg)](LICENSE)

> Platform jual beli coin game online dengan sistem komisi otomatis, notifikasi Telegram real-time, dan aplikasi mobile native.

## 📱 Screenshots

### Web Dashboard
![Dashboard](docs/screenshots/dashboard.png)
*Dashboard admin dengan statistik real-time*

### Mobile App
<p align="center">
  <img src="docs/screenshots/mobile-login.png" width="250" alt="Login Screen"/>
  <img src="docs/screenshots/mobile-home.png" width="250" alt="Home Screen"/>
  <img src="docs/screenshots/mobile-wallet.png" width="250" alt="Wallet Screen"/>
</p>

### Telegram Integration
![Telegram Bot](docs/screenshots/telegram-notification.png)
*Notifikasi transaksi dengan bukti transfer dan tombol approve/reject*

## ✨ Fitur Utama

### 🎮 Multi-Game Support
- **8+ Game Populer**: Mobile Legends, Free Fire, PUBG, Genshin Impact, dan lainnya
- **Layanan Fleksibel**: Top-up diamond, UC, genesis crystal, dll
- **Harga Dinamis**: Update harga otomatis per layanan

### 💰 Sistem Komisi
- **Komisi Otomatis**: Perhitungan komisi real-time per transaksi
- **Multi-Level**: Support komisi untuk marketing dan admin
- **Withdrawal**: Penarikan komisi langsung ke rekening bank
- **Tracking**: Riwayat komisi lengkap dengan filter

### 📲 Telegram Bot Integration
- **5 Bot Aktif**: Bot terpisah per kategori layanan
- **Notifikasi Real-time**: Transaksi baru langsung masuk Telegram
- **Bukti Transfer**: Foto bukti transfer otomatis terkirim
- **Approve/Reject**: Tombol inline untuk konfirmasi cepat
- **Webhook**: Callback otomatis untuk update status

### 📱 Mobile Application
- **Native Android**: Built with Capacitor + WebView
- **Firebase Push Notifications**: Notifikasi transaksi real-time
- **Offline Support**: Cache data untuk akses cepat
- **Auto Update**: Download APK terbaru dari dalam app

### 👥 User Management
- **Role-Based Access**: Admin, Marketing, User
- **Referral System**: Kode referral untuk tracking marketing
- **Profile Management**: Update data pribadi dan rekening bank
- **Activity Log**: Tracking aktivitas user

### 📊 Dashboard & Analytics
- **Real-time Stats**: Total transaksi, komisi, pending orders
- **Charts**: Grafik penjualan dan komisi
- **Export**: Download laporan Excel/PDF
- **Filter**: Filter berdasarkan tanggal, status, layanan

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 11.x
- **PHP**: 8.2+
- **Database**: MySQL 8.0
- **Cache**: Redis (optional)
- **Queue**: Laravel Queue (Sync/Redis)

### Frontend
- **UI Framework**: Tailwind CSS 3.x
- **Components**: Livewire 3.x
- **Icons**: Lucide Icons
- **Build Tool**: Vite

### Mobile
- **Framework**: Capacitor 6.x
- **Platform**: Android (iOS coming soon)
- **Push Notifications**: Firebase Cloud Messaging
- **WebView**: Native Android WebView

### Integrations
- **Telegram**: Bot API for notifications
- **Firebase**: FCM for push notifications
- **WhatsApp**: Fonnte API for customer support

## 📋 Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- MySQL >= 8.0
- Redis (optional, for cache & queue)

## 🚀 Installation

### 1. Clone Repository
```bash
git clone https://github.com/itsolutionqc-naufal/demo-rigel-5.git
cd demo-rigel-5
```

### 2. Install Dependencies
```bash
# PHP dependencies
composer install

# JavaScript dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rigel_coins
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:
```bash
php artisan migrate --seed
```

### 5. Telegram Bot Setup
Add your Telegram bot tokens to `.env`:
```env
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_ADMIN_CHAT_ID=your_chat_id_here
```

Setup webhooks:
```bash
php artisan telegram:setup-webhook https://yourdomain.com
```

### 6. Firebase Setup (Optional - for Push Notifications)
1. Download `google-services.json` from Firebase Console
2. Place it in `android/app/google-services.json`
3. Download Service Account JSON
4. Add to `.env`:
```env
FIREBASE_CREDENTIALS=/path/to/firebase-credentials.json
```

### 7. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Start Development Server
```bash
php artisan serve
```

Visit: `http://localhost:8000`

## 📱 Mobile App Build

### Prerequisites
- Java JDK 17 or 21
- Android SDK
- Gradle

### Build APK
```bash
# Sync Capacitor
npx cap sync android

# Build debug APK
cd android
./gradlew assembleDebug

# APK location: android/app/build/outputs/apk/debug/app-debug.apk
```

### Install to Device
```bash
adb install -r android/app/build/outputs/apk/debug/app-debug.apk
```

## 🔧 Configuration

### Telegram Bots
Configure multiple bots in database `telegram_bots` table:
```sql
INSERT INTO telegram_bots (name, username, token, chat_id, is_active)
VALUES ('Main Bot', 'YourBot', 'bot_token', 'chat_id', 1);
```

### Services
Add game services in admin panel:
- Navigate to `/admin/services`
- Click "Add Service"
- Fill in service details (name, price, commission rate)
- Assign Telegram bot

### Settings
Configure app settings in `/admin/settings`:
- WhatsApp number for support
- App download URL
- Download prompt modal
- Commission rates

## 📚 API Documentation

### Authentication
```bash
POST /api/login
POST /api/register
POST /api/logout
```

### Transactions
```bash
GET  /api/transactions
POST /api/transactions
GET  /api/transactions/{id}
```

### Wallet
```bash
GET  /api/wallet/balance
POST /api/wallet/withdraw
GET  /api/wallet/history
```

### Device Tokens (FCM)
```bash
POST /api/device-tokens
```

## 🔐 Security

- **CSRF Protection**: All forms protected with CSRF tokens
- **SQL Injection**: Eloquent ORM prevents SQL injection
- **XSS Protection**: Blade templating auto-escapes output
- **Authentication**: Laravel Sanctum for API
- **Rate Limiting**: API rate limiting enabled
- **Webhook Verification**: Telegram webhook secret token

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=TransactionTest
```

## 📦 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Configure production database
- [ ] Setup Redis for cache & queue
- [ ] Configure queue worker: `php artisan queue:work`
- [ ] Setup cron job: `* * * * * php artisan schedule:run`
- [ ] Build production assets: `npm run build`
- [ ] Setup SSL certificate (HTTPS required for Telegram webhooks)
- [ ] Configure Telegram webhooks to production URL
- [ ] Upload APK to production server

### Server Requirements
- PHP 8.2+ with extensions: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- MySQL 8.0+
- Nginx or Apache
- SSL Certificate
- Supervisor (for queue workers)

## 🤝 Contributing

This is a proprietary project. For contributions or issues, please contact the development team.

## 📄 License

Proprietary - All rights reserved © 2026 Rigel Agency

## 👥 Team

- **Developer**: IT Solution QC
- **Project Manager**: Naufal
- **Agency**: Rigel Agency

## 📞 Support

- **Website**: https://agencyrigel.com
- **WhatsApp**: Contact via website
- **Telegram**: @RigelSupport

## 🎯 Roadmap

### Q2 2026
- [x] Multi-bot Telegram integration
- [x] Firebase push notifications
- [x] Mobile app (Android)
- [ ] iOS app
- [ ] Payment gateway integration

### Q3 2026
- [ ] Automated pricing updates
- [ ] Advanced analytics dashboard
- [ ] Multi-language support
- [ ] API for third-party integration

### Q4 2026
- [ ] Cryptocurrency payment
- [ ] Loyalty program
- [ ] Affiliate dashboard
- [ ] White-label solution

---

<p align="center">
  Made with ❤️ by <a href="https://agencyrigel.com">Rigel Agency</a>
</p>
