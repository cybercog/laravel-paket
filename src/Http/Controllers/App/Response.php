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

namespace Cog\Laravel\Paket\Http\Controllers\App;

use Illuminate\Contracts\Support\Responsable as ResponsableContract;
use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Config;

final class Response implements ResponsableContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $this->toHtml($request);
    }

    private function toHtml(Request $request): IlluminateResponse
    {
        return response()->view('paket::app', [
            'cssFile' => 'app.css',
            'paketScriptVariables' => [
                'baseUri' => Config::get('paket.base_uri'),
            ],
        ]);
    }
}
