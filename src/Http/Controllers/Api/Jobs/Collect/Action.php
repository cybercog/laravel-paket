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

namespace Cog\Laravel\Paket\Http\Controllers\Api\Jobs\Collect;

use Cog\Contracts\Paket\Job\Repositories\Job as JobRepositoryContract;
use Illuminate\Contracts\Support\Responsable as ResponsableContract;
use Illuminate\Http\Request;

final class Action
{
    public function __invoke(JobRepositoryContract $jobs, Request $request): ResponsableContract
    {
        return new Response($jobs->all());
    }
}
