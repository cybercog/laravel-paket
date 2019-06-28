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

namespace Cog\Laravel\Paket\Http\Controllers\Api\Requirements\Post;

use Cog\Contracts\Paket\Job\Entities\Job as JobContract;
use Cog\Contracts\Paket\Requirement\Entities\Requirement as RequirementContract;
use Illuminate\Contracts\Support\Responsable as ResponsableContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class Response implements ResponsableContract
{
    private $requirement;

    private $job;

    public function __construct(RequirementContract $requirement, JobContract $job)
    {
        $this->requirement = $requirement;
        $this->job = $job;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $this->toJson($request);
    }

    private function toJson(Request $request): JsonResponse
    {
        $jobUrl = route('paket.api.jobs.get', $this->job->getId());

        $data = [
            'data' => [
                'type' => 'CogPaketJob',
                'id' => $this->job->getId(),
                'attributes' => [
                    'status' => 'Shell command execution',
                ],
                'links' => [
                    'self' => $jobUrl,
                ],
            ],
        ];

        return response()->json($data, 202, [
            'Content-Location' => $jobUrl,
        ]);
    }
}
