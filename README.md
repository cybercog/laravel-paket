# Laravel Paket

![cog-laravel-paket](https://user-images.githubusercontent.com/1849174/55282087-15cfb880-534e-11e9-9187-a99215bd8c4d.png)

<p align="center">
<a href="https://github.com/cybercog/laravel-paket/releases"><img src="https://img.shields.io/github/release/cybercog/laravel-paket.svg?style=flat-square" alt="Releases"></a>
<a href="https://travis-ci.org/cybercog/laravel-paket"><img src="https://img.shields.io/travis/cybercog/laravel-paket/master.svg?style=flat-square" alt="Build Status"></a>
<a href="https://styleci.io/repos/194345461"><img src="https://styleci.io/repos/194345461/shield" alt="StyleCI"></a>
<a href="https://scrutinizer-ci.com/g/cybercog/laravel-paket/?branch=master"><img src="https://img.shields.io/scrutinizer/g/cybercog/laravel-paket.svg?style=flat-square" alt="Code Quality"></a>
<a href="https://github.com/cybercog/laravel-paket/blob/master/LICENSE"><img src="https://img.shields.io/github/license/cybercog/laravel-paket.svg?style=flat-square" alt="License"></a>
</p>

## Introduction

Laravel Paket satisfies application requirements. Manage Laravel dependencies without switching to command line!

## Official Documentation

Documentation can be found in [Laravel Paket Guide](https://laravel-paket.readme.io/docs).

## Installation

Pull in the package through Composer.

```sh
$ composer require cybercog/laravel-paket --dev
```

After installing Paket, publish its assets to `public/vendor/paket` directory & create storage in `storage/paket` directory using the `paket:setup` Artisan command.

```sh
$ php artisan paket:setup
```

## Usage

Paket exposes a dashboard at `/paket`. By default, you will only be able to access this dashboard in the `local` environment.

### Dashboard

![Laravel Paket Dashboard](https://user-images.githubusercontent.com/1849174/60401748-80cb9e00-9b8e-11e9-8a38-9d49ab418445.png)

### Requirements List

![Laravel Paket Requirements](https://user-images.githubusercontent.com/1849174/60401755-9fca3000-9b8e-11e9-86d0-b5c8d26b3238.png)

### Jobs List

![Laravel Paket Jobs](https://user-images.githubusercontent.com/1849174/60400934-4f99a080-9b83-11e9-8bb6-e3ce3fcce1a4.png)

### Job

![Laravel Paket Job](https://user-images.githubusercontent.com/1849174/60400942-6fc95f80-9b83-11e9-9afd-f82f76980fb4.png)

## License

- `Laravel Paket` package is open-sourced software licensed under the [MIT License](LICENSE) by [Anton Komarev](https://komarev.com).
- `Shipping` logo image licensed under [Creative Commons 3.0](https://creativecommons.org/licenses/by/3.0/us/) by Gan Khoon Lay.

## About CyberCog

[CyberCog](https://cybercog.ru) is a Social Unity of enthusiasts. Research best solutions in product & software development is our passion.

- [Follow us on Twitter](https://twitter.com/cybercog)

<a href="https://cybercog.ru"><img src="https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png" alt="CyberCog"></a>
