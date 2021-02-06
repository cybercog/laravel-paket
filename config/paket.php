<?php

/*
 * This file is part of Laravel Paket.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Cog\Laravel\Paket\Http\Middlewares\Authorize;

return [

    /*
    |--------------------------------------------------------------------------
    | Paket Base URI
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Paket will be accessible from. Feel free
    | to change this path to anything you like.
    |
    */

    'base_uri' => env('PAKET_BASE_URI', 'paket'),

    /*
    |--------------------------------------------------------------------------
    | Composer PATH
    |--------------------------------------------------------------------------
    |
    | Composer executable file location.
    |
    */

    'composer_path' => env('PAKET_COMPOSER_PATH', '/usr/bin/composer'),

    /*
    |--------------------------------------------------------------------------
    | Paket Route Middlewares
    |--------------------------------------------------------------------------
    |
    | These middlewares will be assigned to every Paket route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middlewares. Or, you can simply stick with this list.
    |
    */

    'middlewares' => [
        'web',
        Authorize::class,
    ],

];
