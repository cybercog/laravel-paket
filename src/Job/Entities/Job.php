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

namespace Cog\Laravel\Paket\Job\Entities;

use Cog\Contracts\Paket\Job\Entities\Job as JobContract;
use Cog\Contracts\Paket\Process\Entities\Process as ProcessContract;
use Cog\Contracts\Paket\Requirement\Entities\Requirement as RequirementContract;
use Cog\Laravel\Paket\Process\Entities\Process;
use Cog\Laravel\Paket\Requirement\Entities\Requirement;
use DateTimeInterface;
use Illuminate\Support\Carbon;

final class Job implements JobContract
{
    private $type;

    private $id;

    private $status;

    private $process;

    private $requirement;

    private $createdAt;

    public function __construct(
        string $type,
        string $id,
        string $status,
        DateTimeInterface $createdAt,
        ProcessContract $process,
        ?RequirementContract $requirement = null
    ) {
        $this->type = $type;
        $this->id = $id;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->process = $process;
        $this->requirement = $requirement;
    }

    public static function fromArray(array $job): JobContract
    {
        return new self(
            $job['type'],
            $job['id'],
            $job['status'],
            Carbon::createFromFormat(DATE_RFC3339_EXTENDED, $job['createdAt']),
            Process::fromArray($job['process']),
            Requirement::fromArray($job['requirement'])
        );
    }

    public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'id' => $this->getId(),
            'status' => $this->status,
            'requirement' => $this->requirement->toArray(),
            'process' => $this->process->toArray(),
            'createdAt' => $this->createdAt->format(DATE_RFC3339_EXTENDED),
        ];
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRequirement(): RequirementContract
    {
        return $this->requirement;
    }
}
