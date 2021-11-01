# Online Store API

## Deksripsi
Based on the stated facts above, please do the following things:
1. Describe what you think happened that caused those bad reviews during our 12.12 event and why it happened. Put this in a section in
   your README.md file.
2. Based on your analysis, propose a solution that will prevent the incidents from occurring again. Put this in a section in your README.md
   file.
3. Based on your proposed solution, build a Proof of Concept that demonstrates technically how your solution will work.

## Solusi
1. Ada beberapa kemungkinan, jika melihat dari deskripsi soal:
   - pada saat itu sistem tetap membolehkan barang ditambahkan ke dalam keranjang, walaupun stok barang sudah 0 atau minus
2. Untuk menghindari kejadian seperti yang di deskripsi, maka solusinya saat menambahkan barang ke dalam keranjang, maka perlu dilakukna pengecekan stok terlebih dahulu.

## Requirement
1. ^php7.4 
2. composer 2.1.3

## Tutorial
1. Install composer
2. Jalankan perintah "composer install"
3. copy file ".env.example" ke ".env"
4. Atur koneksi database pada file ".env"
5. Melakukan generate table dan master data, untuk melakukan hal ini terdapat 2 cara:
   1. import data menggunakan file "online_store_db.sql"
   2. menjalankan perintah "php artisan migrate" untuk membuat table, kemudian menjalankan perintah "php artisan db:seed" untuk generate master data
6. Jalankan perintah "php artisan key:generate" untuk generate key di file ".env"
7. Untuk menjalankan applikasi, bisa menggunakan:
   1. perintah "php artisan serve" maka applikasi akan berjalan pada port 8000
   2. letakkan file applikasi ke dalam folder /var/www/html
8. Daftarkan diri menggunakan API "Register", atau bisa menggunakan user yang telah digenerate dengan username "user@evermos" password "123456"
9. Gunakan API "List Products" untuk melihat daftar barang
10. Gunakan API "Create Cart" untuk menambahkan barang kedalam keranjang
11. Gunakan API "Create Checkout" untuk melakukan checkout
12. Gunakan API "Update Checkout" untuk mengubah status pembayaran "awaiting" menjadi "paid"

## Note
1. pada applikasi sudah memiliki 2 user Admin dengan username "admin@evermos.com" dan user biasa dengan username "user@evermos.com" dengan password "123456"
2. Untuk melihat live log saat applikasi dijalankan, lakukan perintah "tail -f storage/logs/laravel.log"


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
