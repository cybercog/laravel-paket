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

namespace Cog\Laravel\Paket\Http\Controllers\Api\Repositories;

use Illuminate\Contracts\Support\Responsable as ResponsableContract;
use Illuminate\Http\JsonResponse;

final class CollectResponse implements ResponsableContract
{
    private $repositories;

    public function __construct(iterable $repositories)
    {
        $this->repositories = $repositories;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $this->toJson();
    }

    private function toJson(): JsonResponse
    {
        return response()->json($this->repositories);
    }
}
