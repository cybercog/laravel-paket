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

use Cog\Contracts\Paket\Job\Repositories\Job as JobRepositoryContract;

final class Action
{
    public function __invoke(string $id, JobRepositoryContract $jobs)
    {
        return new Response($jobs->getById($id));
    }
}
