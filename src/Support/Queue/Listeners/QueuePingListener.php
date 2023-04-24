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

namespace Cog\Laravel\Paket\Support\Queue\Listeners;

use Cog\Laravel\Paket\Support\Queue\Events\QueueHasBeenPinged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\File;

final class QueuePingListener implements ShouldQueue
{
    public function handle(QueueHasBeenPinged $event): void
    {
        File::put(storage_path('paket/queue-ping.log'), $event->getId());
    }
}
