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

namespace Cog\Laravel\Paket\Http\Controllers\Api\Jobs;

use Illuminate\Contracts\Support\Responsable as ResponsableContract;
use Illuminate\Http\JsonResponse;

final class CollectResponse implements ResponsableContract
{
    private $jobs;

    public function __construct(iterable $jobs)
    {
        $this->jobs = $jobs;
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
        $jobs = [];
        foreach ($this->jobs as $job) {
            $jobs[] = $job->toArray();
        }

        return response()->json($jobs);
    }
}
