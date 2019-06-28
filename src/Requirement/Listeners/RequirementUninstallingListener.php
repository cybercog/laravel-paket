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

use Cog\Laravel\Paket\Requirement\Events\RequirementUninstalling;
use Cog\Laravel\Paket\Requirement\Jobs\UninstallRequirement;
use Illuminate\Contracts\Queue\ShouldQueue;

final class RequirementUninstallingListener implements ShouldQueue
{
    public function handle(RequirementUninstalling $event): void
    {
        UninstallRequirement::dispatch($event->getRequirement(), $event->getJob());
    }
}
