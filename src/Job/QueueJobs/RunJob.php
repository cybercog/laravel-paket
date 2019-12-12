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

namespace Cog\Laravel\Paket\Job\QueueJobs;

use Cog\Contracts\Paket\Job\Entities\Job as JobContract;
use Cog\Contracts\Paket\Job\Exceptions\JobFailed;
use Cog\Contracts\Paket\Job\Repositories\Job as JobRepositoryContract;
use Cog\Laravel\Paket\Job\Events\JobHasBeenTerminated;
use Cog\Laravel\Paket\Support\Composer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use RuntimeException;

final class RunJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    private $paketJob;

    public function __construct(JobContract $paketJob)
    {
        $this->paketJob = $paketJob;
    }

    public function handle(JobRepositoryContract $jobs, Composer $composer): void
    {
        $jobs->changeJobStatus($this->paketJob, 'Running');

        try {
            switch ($this->paketJob->getType()) {
                case 'RequirementInstall':
                    $composer->install($this->paketJob->getRequirement(), $this->paketJob);
                    break;
                case 'RequirementUninstall':
                    $composer->uninstall($this->paketJob->getRequirement(), $this->paketJob);
                    break;
                default:
                    // TODO: Throw custom exception
                    throw new RuntimeException('Unknown type of job');
            }

            $jobs->changeJobStatus($this->paketJob, 'Success');
            $jobs->changeJobExitCode($this->paketJob, 0);
        } catch (JobFailed $exception) {
            $jobs->changeJobStatus($this->paketJob, 'Failed');
            $jobs->changeJobExitCode($this->paketJob, $exception->getExitCode());
        }

        event(new JobHasBeenTerminated($this->paketJob));
    }
}
