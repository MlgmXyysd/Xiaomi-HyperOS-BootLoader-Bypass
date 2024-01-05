# Xiaomi HyperOS BootLoader Bypass

![Version: 1.0](https://img.shields.io/badge/Version-1.0-brightgreen?style=for-the-badge) [![English](https://img.shields.io/badge/English-brightgreen?style=for-the-badge)](README.md) [![中文文档](https://img.shields.io/badge/中文文档-brightgreen?style=for-the-badge)](README-zh.md) [![日本語](https://img.shields.io/badge/日本語-brightgreen?style=for-the-badge)](README-ja.md)

PoC yang mengeksploitasi kerentanan akun Komunitas Xiaomi HyperOS untuk melewati batasan kaitan membuka kunci bootloader.

Jangan ragu untuk meminta pull jika Anda mau :)

## 💘 php-adb

Proyek ini dengan bangga menggunakan [php-adb](https://github.com/MlgmXyysd/php-adb) library.

## ☕ Belikan aku Kopi

✨ Jika Anda menyukai proyek saya, Anda dapat membelikan saya kopi di:

 - [爱发电](https://afdian.net/@MlgmXyysd)
 - [PayPal](https://paypal.me/MlgmXyysd)
 - [Patreon](https://www.patreon.com/MlgmXyysd)

## ⚠️ Peringatan

Setelah membuka kunci BootLoader, Anda mungkin mengalami situasi berikut:

- Software atau hardware tidak berfungsi dengan baik atau bahkan rusak.
- Hilangnya data yang tersimpan di perangkat.
- Pencurian kartu kredit, atau kerugian finansial lainnya.

Jika Anda mengalami salah satu hal di atas, Anda harus mengambil tanggung jawab sendiri karena ini adalah risiko yang mungkin Anda hadapi saat membuka kunci BootLoader. Hal ini jelas tidak mencakup seluruh risiko. Anda telah diperingatkan.

- Garansi hilang. Tidak hanya garansi dasar, beberapa garansi tambahan tambahan (seperti Mi Care atau garansi layar rusak) yang Anda beli juga mungkin hilang sesuai dengan pengecualian yang diberikan oleh Xiaomi.
- Penghancuran tingkat keamanan perangkat keras seperti Samsung Knox. Fitur terkait TEE akan rusak secara permanen. Tidak ada cara untuk mengembalikannya selain dengan mengganti motherboard.
- Anomali fungsional setelah mem-flash sistem pihak ketiga karena kode sumber kernel sumber tertutup.
- Perangkat atau akun diblokir dengan membuka kunci BootLoader.

Jika Anda mengalami salah satu hal di atas, anggaplah diri Anda terkutuk. Sejak Xiaomi membatasi pembukaan kunci BootLoader, hal ini bertentangan dengan semangat Xiaomi 'geek' dan bahkan GPL. Pembatasan Xiaomi pada pembukaan kunci BootLoader tidak ada habisnya, dan kami sebagai pengembang tidak dapat melakukan apa pun untuk mengatasinya.

## 📲 Persyratan buka kunci

- Perangkat yang valid:
  - Perangkat Xiaomi, Redmi, atau POCO\* yang tidak diblokir.
  - Perangkat Anda menjalankan versi resmi HyperOS.
  - (Pembaruan 2023/11/23) Perangkat Anda tidak dipaksa untuk memverifikasi kualifikasi akun oleh Xiaomi.
- Kartu SIM yang valid:
  - Kecuali tablet yang tidak dapat menggunakan kartu SIM.
  - Kartu SIM tidak boleh keluar dari layanan.
  - Kartu SIM harus dapat mengakses internet.
  - Hanya 2 perangkat per kartu SIM yang valid yang dibolehkan untuk membuka kunci dengan kartu SIM yang valid dalam jangka waktu tiga bulan.
- Akun Xiaomi yang valid:
  - Akun Xiaomi\* yang tidak diblokir.
  - Setiap akun hanya dapat membuka 1 ponsel dalam sebulan dan 3 ponsel dalam periode satu tahun.
- Anda telah membaca dan memahami [Peringatan](https://github.com/MlgmXyysd/Xiaomi-HyperOS-BootLoader-Bypass/tree/master/docs#%EF%B8%8F-warning) di atas.

- Menurut instruksi membuka kunci yang diberikan oleh Xiaomi,itu akan melarang beberapa akun dan perangkat menggunakan alat pembuka kunci, yang disebut "pengendalian risiko".

- ## ⚙️ Bagaimana cara menggunakan

1. Unduh dan instal PHP 8.0+ untuk sistem Anda dari [situs web resmi](https://www.php.net/downloads).
2. Aktifkan ekstensi OpenSSL dan Curl di `php.ini`.
3. Tempatkan `adb.php` di [php-adb](https://github.com/MlgmXyysd/php-adb) ke direktori.
4. Unduh [platform-tools](https://developer.android.com/studio/releases/platform-tools) dan letakkan di `libraries`. *Catatan: Mac OS perlu mengganti nama `adb` menjadi `adb-darwin`.*
5. Buka terminal dan gunakan bahasa PHP untuk menjalankan [skrip](../bypass.php).

- cat. Rilis telah mengemas file yang diperlukan dan skrip klik untuk menjalankan

6. Ketuk berulang kali pada `Pengaturan - Tentang Ponsel - Versi MIUI` untuk mengaktifkan `Opsi Pengembang`.
7. Aktifkan `Membuka Kunci OEM`, `USB Debugging` dan `USB Debugging (Pengaturan Keamanan)` di `Pengaturan - Pengaturan Tambahan - Opsi Pengembang`.
8. Masuk akun Xiaomi\* _valid_.
10. Hubungkan telepon ke PC melalui antarmuka kabel.
11. Centang `Selalu izinkan dari komputer ini` dan klik `OK`.

- Lihat [Persyaratan Membuka Kunci](https://github.com/MlgmXyysd/Xiaomi-HyperOS-BootLoader-Bypass/tree/master/docs#-Unlocking-requirements) di atas.

11. Tunggu dan ikuti petunjuk skrip.
12. Setelah pengaitan berhasil, Anda dapat menggunakan [alat buka kunci resmi](https://en.miui.com/unlock/index.html) untuk memeriksa waktu yang Anda perlukan untuk menunggu.
13. Selama masa tunggu, harap gunakan perangkat secara normal, tetap masukkan kartu SIM, jangan keluar dari akun Anda atau matikan `Temukan Ponsel Saya`, dan jangan mengaitkan ulang perangkat hingga berhasil dibuka kuncinya. Perangkat akan secara otomatis mengirimkan paket `HeartBeat` ke server sesekali.

14. ## 📖 Solusi

- Sedang dalam pemeliharaan...

## 🔖 FAQ

- T: Mengapa alat buka kunci masih mengingatkan saya untuk menunggu 168/360 (atau lebih) jam?
  - J: Secara prinsip, PoC ini hanya melewati batasan yang ditambahkan untuk HyperOS. Anda tetap harus mematuhi batasan MIUI.

- T: Perangkat menampilkan `Tidak dapat memverifikasi, tunggu satu atau dua menit dan coba lagi`.
  - J: Ini normal, permintaan pengikatan di sisi perangkat telah diblokir oleh skrip kami. Hasil pengikatan sebenarnya tunduk pada perintah skrip.

- T: Pengikatan gagal dengan kode kesalahan `401`.
  - J: Kredensial akun Xiaomi Anda telah kedaluwarsa, Anda harus keluar dan masuk lagi di perangkat Anda.

- T: Pengikatan gagal dengan kode kesalahan `20086`.
  - J: Kredensial perangkat Anda telah kedaluwarsa, Anda perlu me-reboot perangkat Anda.

- T: Pengikatan gagal dengan kode kesalahan `20090` atau `20091`.
  - J: Kegagalan fungsi Manajer Kredensial Perangkat Keamanan Perangkat, hubungi purna jual.

- T: Pengikatan gagal dengan kode kesalahan `30001`.
  - J: Perangkat Anda telah dipaksa untuk memverifikasi kualifikasi akun oleh Xiaomi. Xiaomi sudah lama kehilangan semangat 'geek'-nya, dan tidak ada yang bisa kami lakukan untuk mengatasinya.

- T: Pengikatan gagal dengan kode kesalahan `86015`.
  - J: Server telah menolak permintaan pengikatan ini, silakan coba lagi.

## ⚖️ Lisensi

Tanpa lisensi, Anda hanya diperbolehkan menggunakan proyek ini. Semua hak cipta (dan tautan, dsb.) dalam perangkat lunak ini tidak boleh dihapus atau diubah tanpa izin. Semua hak dilindungi oleh [MeowCat Studio](https://github.com/MeowCat-Studio), [Meow Mobile](https://github.com/Meow-Mobile) dan [NekoYuzu](https://github.com/MlgmXyysd).
