# Laravel Paket

![cog-laravel-paket](https://user-images.githubusercontent.com/1849174/67785136-d7ce7a80-fa7d-11e9-8217-eb1c8e0d4d7f.png)

<p align="center">
    <a href="https://github.com/cybercog/laravel-paket/releases"><img src="https://img.shields.io/github/release/cybercog/laravel-paket.svg?style=flat-square" alt="Releases"></a>
    <a href="https://travis-ci.org/cybercog/laravel-paket"><img src="https://img.shields.io/travis/cybercog/laravel-paket/master.svg?style=flat-square" alt="Build Status"></a>
    <a href="https://styleci.io/repos/194345461"><img src="https://styleci.io/repos/194345461/shield" alt="StyleCI"></a>
    <a href="https://scrutinizer-ci.com/g/cybercog/laravel-paket/?branch=master"><img src="https://img.shields.io/scrutinizer/g/cybercog/laravel-paket.svg?style=flat-square" alt="Code Quality"></a>
    <a href="https://github.com/cybercog/laravel-paket/blob/master/LICENSE"><img src="https://img.shields.io/github/license/cybercog/laravel-paket.svg?style=flat-square" alt="License"></a>
</p>

## Introduction

Laravel Paket satisfies application requirements. Manage Laravel dependencies without switching to command line!

Graphical User Interface made with [Tailwind CSS].

## Official Documentation

Documentation can be found in [Laravel Paket Guide].

## Installation

Pull in the package through Composer.

```shell script
$ composer require cybercog/laravel-paket --dev
```

Run Artisan `paket:setup` command to publish Paket assets to `public/vendor/paket` directory & create `storage/paket` directory for terminal job logs.

```shell script
$ php artisan paket:setup
```

## Upgrading

When upgrading Paket, you should re-publish assets to `public/vendor/paket` directory with force setup command.

```shell script
$ php artisan paket:setup --force
```

## Quick Start

Run local development server.

```shell script
$ php artisan serve
```

Go to URL [http://localhost:8000/paket](http://localhost:8000/paket) in your browser.

You will only be able to access this dashboard when `APP_ENV=local` is set in `.env` file.

### Dashboard

![Laravel Paket Dashboard](https://user-images.githubusercontent.com/1849174/64434687-ca25f580-d0c9-11e9-95c4-8df1f2bd02ff.png)

### Laravel Ecosystem

![Laravel Ecosystem](https://user-images.githubusercontent.com/1849174/64430016-005e7780-d0c0-11e9-8929-7667dcfc985e.png)

### Composer Requirements

![Laravel Paket Requirements](https://user-images.githubusercontent.com/1849174/64429876-aa89cf80-d0bf-11e9-939e-20107f6bab62.png)

### Terminal Jobs

![Laravel Paket Jobs](https://user-images.githubusercontent.com/1849174/64499584-4c790a00-d2c2-11e9-902d-cfed49be1d98.png)

### Terminal Job Details

![Laravel Paket Job](https://user-images.githubusercontent.com/1849174/64499560-38cda380-d2c2-11e9-9ea9-11491865ec6f.png)

## License

- `Laravel Paket` package is open-sourced software licensed under the [MIT License](LICENSE) by [Anton Komarev].
- `Laravel Paket` logo by [Caneco].

## About CyberCog

[CyberCog] is a Social Unity of enthusiasts. Research best solutions in product & software development is our passion.

- [Follow us on Twitter]

<a href="https://cybercog.su"><img src="https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png" alt="CyberCog"></a>

[Anton Komarev]: https://komarev.com
[Caneco]: http://twitter.com/caneco
[CyberCog]: https://cybercog.su
[Follow us on Twitter]: https://twitter.com/cybercog
[Laravel Paket Guide]: https://laravel-paket.readme.io/docs
[Tailwind CSS]: https://github.com/tailwindcss/tailwindcss
