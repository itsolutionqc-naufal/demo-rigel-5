<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first admin user or create one if not exists
        $user = User::where('email', 'admin@rigel.com')->first() ?? User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Admin Rigel',
                'email' => 'admin@rigel.com',
                'password' => bcrypt('password'),
            ]);
        }

        $articles = [
            [
                'title' => 'Panduan Lengkap Top Up Koin Voya: Cara Mudah dan Aman',
                'excerpt' => 'Pelajari cara top up koin Voya dengan mudah dan aman. Dapatkan bonus koin dan promo menarik setiap hari.',
                'content' => 'Voya menjadi salah satu aplikasi top up koin populer di Indonesia. Dengan interface yang user-friendly, Voya memudahkan pengguna untuk melakukan top up berbagai game dan layanan digital. Proses top up di Voya sangat cepat, biasanya hanya membutuhkan waktu 1-3 menit. Selain itu, Voya sering memberikan promo menarik seperti bonus koin tambahan dan cashback. Keamanan transaksi juga terjamin dengan sistem pembayaran yang terenkripsi.',
                'category' => 'tutorial',
                'status' => 'published',
            ],
            [
                'title' => 'Review Aplikasi Sugo: Top Up Game Tercepat dan Termurah',
                'excerpt' => 'Sugo menawarkan top up game dengan harga kompetitif dan proses instan. Cocok untuk para gamers Indonesia.',
                'content' => 'Sugo hadir sebagai solusi top up game dengan harga yang sangat kompetitif. Aplikasi ini support berbagai game populer seperti Mobile Legends, Free Fire, PUBG Mobile, dan banyak lagi. Keunggulan Sugo terletak pada kecepatan proses top up yang hampir instan. Selain itu, Sugo juga menyediakan berbagai metode pembayaran mulai dari transfer bank, e-wallet, hingga minimarket. Customer service Sugo juga siap membantu 24 jam.',
                'category' => 'review',
                'status' => 'published',
            ],
            [
                'title' => 'Cara Top Up Diamond Mobile Legends di Voya dengan Promo',
                'excerpt' => 'Dapatkan diamond ML dengan harga murah dan bonus menarik di aplikasi Voya. Tutorial lengkap dengan gambar.',
                'content' => 'Mobile Legends menjadi game paling populer di Indonesia, dan Voya menyediakan layanan top up diamond dengan harga terbaik. Untuk top up diamond ML di Voya, pertama buka aplikasi Voya lalu pilih game Mobile Legends. Masukan User ID dan Zone ID Anda dengan benar. Pilih jumlah diamond yang diinginkan, mulai dari 86 diamond hingga 5636 diamond. Voya sering memberikan promo seperti bonus diamond 10-20% dan cashback hingga 50 ribu rupiah.',
                'category' => 'tutorial',
                'status' => 'published',
            ],
            [
                'title' => 'Keuntungan Menggunakan Aplikasi Top Up Koin vs Konvensional',
                'excerpt' => 'Kenapa harus pakai aplikasi top up koin? Simak keuntungannya dibandingkan metode konvensional.',
                'content' => 'Aplikasi top up koin seperti Voya dan Sugo menawarkan banyak keuntungan dibandingkan metode konvensional. Pertama, prosesnya jauh lebih cepat dan bisa dilakukan kapan saja. Kedua, harga biasanya lebih murah dengan berbagai promo. Ketiga, lebih aman karena tidak perlu memberikan data sensitif ke pihak ketiga. Keempat, tersedia berbagai metode pembayaran. Kelima, ada sistem poin dan reward untuk pengguna setia. Keenam, customer service yang responsif.',
                'category' => 'tips',
                'status' => 'published',
            ],
            [
                'title' => 'Promo Top Up Koin Spesial: Cashback dan Bonus Diamond',
                'excerpt' => 'Jangan lewatkan promo menarik bulan ini! Cashback hingga 100 ribu dan bonus diamond 30%.',
                'content' => 'Bulan ini Voya dan Sugo mengadakan promo spesial untuk para pengguna setia. Voya memberikan cashback hingga 100 ribu rupiah untuk top up minimal 500 ribu. Sugo menawarkan bonus diamond 30% untuk game Mobile Legends dan Free Fire. Promo ini berlaku untuk semua metode pembayaran. Jangan lupa cek halaman promo setiap hari karena ada flash sale dengan diskon hingga 50%. Syarat dan ketentuan berlaku untuk semua promo.',
                'category' => 'promo',
                'status' => 'published',
            ],
            [
                'title' => 'Cara Daftar dan Verifikasi Akun di Aplikasi Top Up Koin',
                'excerpt' => 'Panduan lengkap mendaftar akun Voya, Sugo, dan aplikasi sejenis. Proses mudah hanya 5 menit.',
                'content' => 'Pendaftaran akun di aplikasi top up koin sangat mudah dan cepat. Pertama, download aplikasi dari Play Store atau App Store. Buka aplikasi dan pilih "Daftar" atau "Sign Up". Masukan nomor HP aktif Anda, lalu klik "Kirim OTP". Masukan kode OTP yang dikirim via SMS. Lengkapi data diri seperti nama, email, dan password. Untuk keamanan, aktifkan 2FA (Two Factor Authentication). Setelah verifikasi berhasil, Anda bisa langsung melakukan top up.',
                'category' => 'tutorial',
                'status' => 'published',
            ],
            [
                'title' => 'Perbandingan Harga Top Up Diamond ML di Voya vs Sugo',
                'excerpt' => 'Mana yang lebih murah untuk top up Diamond ML? Bandingkan harga dan promo keduanya.',
                'content' => 'Kami telah melakukan perbandingan harga top up Diamond Mobile Legends di Voya dan Sugo. Untuk 86 diamond, Voya menawarkan harga Rp 16.900 sedangkan Sugo Rp 16.500. Untuk 172 diamond, Voya Rp 33.900 vs Sugo Rp 33.000. Namun, Voya sering memberikan cashback yang membuat harga menjadi lebih murah. Sugo unggul dalam hal kecepatan proses. Keduanya memiliki kelebihan masing-masing, pilih sesuai kebutuhan Anda.',
                'category' => 'review',
                'status' => 'published',
            ],
            [
                'title' => 'Tips Aman Top Up Koin: Hindari Penipuan dan Scam',
                'excerpt' => 'Pelajari cara menghindari penipuan saat top up koin. Tips keamanan dari para ahli.',
                'content' => 'Keamanan saat top up koin sangat penting. Gunakan hanya aplikasi resmi seperti Voya dan Sugo yang tersedia di Play Store. Jangan pernah memberikan password atau PIN ke siapapun. Cek URL website pastikan benar-benar resmi. Hindari top up dari individual atau pihak tidak dikenal. Gunakan metode pembayaran yang aman. Simpan bukti transaksi. Jika ada yang mencurigakan, langsung hubungi customer service. Jangan tergiur harga yang terlalu murah karena bisa jadi penipuan.',
                'category' => 'tips',
                'status' => 'published',
            ],
            [
                'title' => 'Top Up Koin untuk Game Luar Negeri: Genshin Impact, Honkai Star Rail',
                'excerpt' => 'Cara top up Genesis Crystal dan Oneiric Shard untuk game miHoYo dengan mudah.',
                'content' => 'Game-game dari miHoYo seperti Genshin Impact dan Honkai Star Rail sangat populer di Indonesia. Voya dan Sugo menyediakan layanan top up untuk kedua game ini. Untuk Genshin Impact, tersedia Genesis Crystal mulai dari 60 crystal hingga 6480 crystal. Untuk Honkai Star Rail, tersedia Oneiric Shard dengan berbagai nominal. Prosesnya sama seperti top up game lain, masukan User ID, pilih nominal, dan lakukan pembayaran.',
                'category' => 'tutorial',
                'status' => 'published',
            ],
            [
                'title' => 'Event Spesial Ramadhan: Diskon Top Up Koin hingga 50%',
                'excerpt' => 'Manfaatkan momen Ramadhan dengan promo diskon besar-besaran untuk top up koin game favorit Anda.',
                'content' => 'Ramadhan menjadi momen spesial bagi para gamers Indonesia. Voya dan Sugo menghadirkan promo diskon hingga 50% untuk berbagai game populer. Ada juga program "Ngabuburit Gaming" dengan bonus koin tambahan 25% untuk top up antara jam 15.00-18.00. Jangan lupa cek halaman event setiap hari karena ada flash sale dengan harga super murah. Promo berlaku selama bulan Ramadhan dan Idul Fitri.',
                'category' => 'promo',
                'status' => 'published',
            ],
            [
                'title' => 'Cara Top Up UC PUBG Mobile di Aplikasi Sugo: Step by Step',
                'excerpt' => 'Dapatkan UC PUBG Mobile dengan harga terbaik di Sugo. Tutorial lengkap dengan screenshot.',
                'content' => 'PUBG Mobile merupakan game battle royale terpopuler di dunia. Sugo menyediakan layanan top up UC (Unknown Cash) dengan harga kompetitif. Buka aplikasi Sugo dan pilih game PUBG Mobile. Masukan Player ID Anda yang bisa dilihat di profil game. Pilih jumlah UC yang diinginkan, mulai dari 60 UC hingga 8100 UC. Pilih metode pembayaran favorit Anda. UC akan masuk ke akun Anda dalam waktu 1-5 menit setelah pembayaran berhasil.',
                'category' => 'tutorial',
                'status' => 'published',
            ],
            [
                'title' => 'Keunggulan Aplikasi Voya: Kenapa Gamers Memilihnya?',
                'excerpt' => 'Telusuri keunggulan Voya yang membuatnya menjadi pilihan utama para gamers Indonesia.',
                'content' => 'Voya memiliki beberapa keunggulan yang membuatnya favorit para gamers. Pertama, proses super cepat hanya 30 detik. Kedua, harga kompetitif dengan promo menarik. Ketiga, support 24/7 via WhatsApp dan live chat. Keempat, metode pembayaran lengkap. Kelima, aplikasi ringan dan tidak lag. Keenam, sistem keamanan terbaik. Ketujuh, reward program untuk pelanggan setia. Kedelapan, update promo setiap hari.',
                'category' => 'review',
                'status' => 'published',
            ],
            [
                'title' => 'Top Up Koin untuk Aplikasi Live Streaming: Bigo Live, TikTok',
                'excerpt' => 'Cara top up koin untuk live streaming di Bigo Live, TikTok, dan platform lainnya.',
                'content' => 'Platform live streaming seperti Bigo Live dan TikTok juga membutuhkan top up koin. Voya dan Sugo menyediakan layanan ini dengan harga terjangkau. Untuk Bigo Live, tersedia Diamond dengan berbagai nominal. Untuk TikTok, tersedia Koin TikTok mulai dari 70 koin hingga 7000 koin. Prosesnya sangat mudah dan cepat. Cocok untuk Anda yang suka nonton live streaming dan ingin memberikan gift ke streamer favorit.',
                'category' => 'tutorial',
                'status' => 'published',
            ],
            [
                'title' => 'Cara Mendapatkan Voucher Diskon di Aplikasi Top Up Koin',
                'excerpt' => 'Tips dan trik mendapatkan voucher diskon untuk top up koin di Voya dan Sugo.',
                'content' => 'Voucher diskon bisa menghemat biaya top up Anda. Cara pertama, cek halaman promo setiap hari karena ada flash sale. Kedua, ikuti media sosial Voya dan Sugo untuk kode voucher eksklusif. Ketiga, invite teman untuk mendapatkan voucher referral. Keempat, kumpulkan poin dari setiap transaksi untuk ditukar voucher. Kelima, ikuti event-event spesial seperti anniversary. Keenam, aktifkan notifikasi untuk update promo terbaru.',
                'category' => 'tips',
                'status' => 'published',
            ],
            [
                'title' => 'Perbandingan Fitur: Voya vs Sugo vs Aplikasi Lainnya',
                'excerpt' => 'Bandingkan fitur lengkap Voya, Sugo, dan aplikasi top up koin lainnya untuk pilihan terbaik.',
                'content' => 'Kami telah melakukan perbandingan fitur antara Voya, Sugo, dan aplikasi top up koin lainnya. Voya unggul dalam kecepatan proses dan promo harian. Sugo unggul dalam harga kompetitif dan game support. Aplikasi lain mungkin memiliki keunggulan di bidang tertentu. Pilih sesuai kebutuhan Anda. Faktor yang perlu dipertimbangkan: kecepatan, harga, promo, game support, metode pembayaran, dan customer service.',
                'category' => 'review',
                'status' => 'published',
            ],
            [
                'title' => 'Cara Klaim Garansi dan Refund di Aplikasi Top Up Koin',
                'excerpt' => 'Panduan lengkap klaim garansi dan refund jika top up mengalami masalah.',
                'content' => 'Kadang top up bisa mengalami masalah seperti koin tidak masuk. Jangan khawatir, Voya dan Sugo memiliki sistem garansi dan refund. Simpan bukti transaksi seperti screenshot dan nomor order. Hubungi customer service via WhatsApp atau live chat. Jelaskan masalah Anda dengan jelas. Sertakan bukti transaksi. Proses klaim biasanya membutuhkan waktu 1-24 jam. Jika valid, Anda akan mendapatkan refund atau koin akan diproses ulang.',
                'category' => 'tips',
                'status' => 'published',
            ],
            [
                'title' => 'Update Terbaru: Game Baru yang Support Top Up di Voya',
                'excerpt' => 'Daftar game terbaru yang bisa di-top up di Voya. Ada banyak game baru menarik!',
                'content' => 'Voya terus menambahkan support untuk game-game baru. Game terbaru yang support termasuk: Honor of Kings, Arena Breakout, Farlight 84, dan banyak lagi. Selain itu, Voya juga menambahkan support untuk game-game indie yang sedang trending. Cek aplikasi Voya setiap minggu untuk update game baru. Jika game favorit Anda belum support, bisa request ke customer service. Voya akan berusaha menambahkan support sesuai permintaan pengguna.',
                'category' => 'news',
                'status' => 'published',
            ],
            [
                'title' => 'Strategi Bisnis Aplikasi Top Up Koin: Behind The Scene',
                'excerpt' => 'Mengintip strategi bisnis dan operasional di balik kesuksesan aplikasi top up koin.',
                'content' => 'Aplikasi top up koin seperti Voya dan Sugo memiliki strategi bisnis yang menarik. Mereka bekerja sama langsung dengan publisher game untuk mendapatkan harga grosir. Margin keuntungan berasal dari selisih harga jual dan harga beli. Promo dan diskon adalah strategi marketing untuk mendapatkan pelanggan baru. Customer service 24/7 membutuhkan investasi besar. Sistem keamanan dan teknologi juga membutuhkan biaya maintenance. Namun, volume transaksi yang tinggi membuat bisnis ini tetap profitable.',
                'category' => 'news',
                'status' => 'published',
            ],
            [
                'title' => 'FAQ Aplikasi Top Up Koin: Jawaban untuk Pertanyaan Populer',
                'excerpt' => 'Kumpulan pertanyaan yang sering ditanyakan tentang aplikasi top up koin dan jawabannya.',
                'content' => 'Berbagai pertanyaan populer tentang top up koin: Apakah aman? Ya, asal menggunakan aplikasi resmi. Berapa lama prosesnya? Biasanya 1-5 menit. Apakah ada biaya tambahan? Tidak, harga yang tertera adalah harga final. Bagaimana jika koin tidak masuk? Segera hubungi customer service dengan bukti transaksi. Apakah ada garansi? Ya, semua transaksi bergaransi. Bisakah refund? Ya, jika memenuhi syarat. Apakah support semua game? Hampir semua game populer didukung.',
                'category' => 'faq',
                'status' => 'published',
            ],
        ];

        foreach ($articles as $article) {
            Article::create([
                'title' => $article['title'],
                'slug' => Str::slug($article['title']),
                'excerpt' => $article['excerpt'],
                'content' => $article['content'],
                'category' => $article['category'],
                'status' => $article['status'],
                'user_id' => $user->id,
                'published_at' => $article['status'] === 'published' ? now() : null,
                'views' => rand(50, 500),
            ]);
        }
    }
}