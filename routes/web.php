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
    Route::get('requirements')
        ->uses('Requirements\Collect\Action')
        ->name('requirements.collect');

    Route::post('requirements')
        ->uses('Requirements\Post\Action')
        ->name('requirements.post');

    Route::delete('requirements/{uid}')
        ->uses('Requirements\Delete\Action')
        ->name('requirements.delete');

    Route::get('jobs')
        ->uses('Jobs\Collect\Action')
        ->name('jobs.collect');

    Route::get('jobs/{job}')
        ->uses('Jobs\Get\Action')
        ->name('jobs.get');
});

Route::get('{view?}', 'App\Action')
    ->where('view', '(.*)')
    ->name('paket');
