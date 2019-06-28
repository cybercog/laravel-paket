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

namespace Cog\Laravel\Paket\Requirement\Listeners;

use Cog\Laravel\Paket\Requirement\Events\RequirementInstalling;
use Cog\Laravel\Paket\Requirement\Jobs\InstallRequirement;
use Illuminate\Contracts\Queue\ShouldQueue;

final class RequirementInstallingListener implements ShouldQueue
{
    public function handle(RequirementInstalling $event): void
    {
        InstallRequirement::dispatch($event->getRequirement(), $event->getJob());
    }
}
