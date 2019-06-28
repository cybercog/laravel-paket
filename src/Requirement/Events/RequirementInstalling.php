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

namespace Cog\Laravel\Paket\Requirement\Events;

use Cog\Contracts\Paket\Job\Entities\Job as JobContract;
use Cog\Contracts\Paket\Requirement\Entities\Requirement as RequirementContract;

final class RequirementInstalling
{
    private $requirement;

    private $job;

    public function __construct(RequirementContract $requirement, JobContract $job)
    {
        $this->requirement = $requirement;
        $this->job = $job;
    }

    public function getRequirement(): RequirementContract
    {
        return $this->requirement;
    }

    public function getJob(): JobContract
    {
        return $this->job;
    }
}
