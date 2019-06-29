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

return [

    /*
    |--------------------------------------------------------------------------
    | Paket URI
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Paket will be accessible from. Feel free
    | to change this path to anything you like.
    |
    */

    'uri' => env('PAKET_URI', 'paket'),

];