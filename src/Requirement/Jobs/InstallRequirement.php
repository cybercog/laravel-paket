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

namespace Cog\Laravel\Paket\Requirement\Jobs;

use Cog\Contracts\Paket\Job\Entities\Job as JobContract;
use Cog\Contracts\Paket\Job\Exceptions\JobFailed;
use Cog\Contracts\Paket\Job\Repositories\Job as JobRepositoryContract;
use Cog\Contracts\Paket\Requirement\Entities\Requirement as RequirementContract;
use Cog\Laravel\Paket\Requirement\Events\RequirementHasBeenInstalled;
use Cog\Laravel\Paket\Support\Composer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

final class InstallRequirement implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;

    private $requirement;

    private $paketJob;

    public function __construct(RequirementContract $requirement, JobContract $paketJob)
    {
        $this->requirement = $requirement;
        $this->paketJob = $paketJob;
    }

    public function handle(JobRepositoryContract $jobs, Composer $composer): void
    {
        $jobs->changeJobStatus($this->paketJob, 'InProgress');

        try {
            $composer->install($this->requirement, $this->paketJob);

            $jobs->changeJobStatus($this->paketJob, 'Done');
            $jobs->changeJobExitCode($this->paketJob, 0);

            event(new RequirementHasBeenInstalled($this->requirement, $this->paketJob));
        } catch (JobFailed $exception) {
            $jobs->changeJobStatus($this->paketJob, 'Failed');
            $jobs->changeJobExitCode($this->paketJob, $exception->getExitCode());

            // TODO: (?) Dispatch `RequirementInstallationHasBeenFailed` || `InstallationHasBeenFailed` event?
        }
    }
}
