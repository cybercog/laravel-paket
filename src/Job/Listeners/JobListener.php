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

namespace Cog\Laravel\Paket\Job\Listeners;

use Cog\Laravel\Paket\Job\Events\JobHasBeenCreated;
use Cog\Laravel\Paket\Requirement\Jobs\InstallRequirement;
use Cog\Laravel\Paket\Requirement\Jobs\UninstallRequirement;
use Illuminate\Contracts\Queue\ShouldQueue;

final class JobListener implements ShouldQueue
{
    public function handle(JobHasBeenCreated $event): void
    {
        if ($event->getJob()->getType() === 'RequirementInstall') {
            InstallRequirement::dispatch($event->getJob());
        } else {
            UninstallRequirement::dispatch($event->getJob());
        }
    }
}
