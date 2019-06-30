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

Laravel Paket satisfies application requirements. Manage dependencies without switching to command line!

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

## License

- `Laravel Paket` package is open-sourced software licensed under the [MIT License](LICENSE) by [Anton Komarev](https://komarev.com).
- `Shipping` logo image licensed under [Creative Commons 3.0](https://creativecommons.org/licenses/by/3.0/us/) by Gan Khoon Lay.

## About CyberCog

[CyberCog](http://www.cybercog.ru) is a Social Unity of enthusiasts. Research best solutions in product & software development is our passion.

- [Follow us on Twitter](https://twitter.com/cybercog)

<a href="http://cybercog.ru"><img src="https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png" alt="CyberCog"></a>
