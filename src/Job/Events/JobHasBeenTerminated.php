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

namespace Cog\Laravel\Paket\Job\Events;

use Cog\Contracts\Paket\Job\Entities\Job as JobContract;

final class JobHasBeenTerminated
{
    private $job;

    public function __construct(JobContract $job)
    {
        $this->job = $job;
    }

    public function getJob(): JobContract
    {
        return $this->job;
    }
}
