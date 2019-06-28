<?php

/*
 * This file is part of PHP Contracts: Paket.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cog\Contracts\Paket\Job\Repositories;

use Cog\Contracts\Paket\Job\Entities\Job;
use Cog\Contracts\Paket\Requirement\Entities\Requirement;

interface JobRepository
{
    public function all(): iterable;

    public function getById(string $id): Job;

    public function store(Job $job, Requirement $requirement): void;

    public function changeJobStatus(Job $job, string $statusName): void;

    public function changeJobExitCode(Job $job, int $exitCode): void;

}
