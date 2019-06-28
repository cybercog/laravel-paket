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

namespace Cog\Laravel\Paket\Http\Controllers\Api\Jobs\Get;

use Cog\Contracts\Paket\Job\Entities\Job as JobContract;
use Illuminate\Contracts\Support\Responsable as ResponsableContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class Response implements ResponsableContract
{
    private $job;

    public function __construct(JobContract $job)
    {
        $this->job = $job;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $this->toJson($request);
    }

    private function toJson(Request $request): JsonResponse
    {
        return response()->json($this->job->toArray());
    }
}
