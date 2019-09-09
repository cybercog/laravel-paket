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

use Illuminate\Support\Facades\Route;

Route::prefix('api')->namespace('Api')->name('paket.api.')->group(function () {
    Route::get('repositories')
        ->uses('Repositories\CollectAction')
        ->name('repositories.collect');

    Route::get('requirements')
        ->uses('Requirements\CollectAction')
        ->name('requirements.collect');

    Route::get('jobs')
        ->uses('Jobs\CollectAction')
        ->name('jobs.collect');

    Route::post('jobs')
        ->uses('Jobs\PostAction')
        ->name('jobs.post');

    Route::get('jobs/{job}')
        ->uses('Jobs\GetAction')
        ->name('jobs.get');

    Route::delete('jobs/{job}')
        ->uses('Jobs\DeleteAction')
        ->name('jobs.delete');
});

Route::get('{view?}', 'AppAction')
    ->where('view', '(.*)')
    ->name('paket');
