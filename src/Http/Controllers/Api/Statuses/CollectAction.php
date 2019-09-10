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

namespace Cog\Laravel\Paket\Http\Controllers\Api\Statuses;

use Cog\Laravel\Paket\Support\Queue\Events\QueueHasBeenPinged;
use Cog\Laravel\Paket\Support\Queue\Listeners\QueuePingListener;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\File;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;
use Ramsey\Uuid\Uuid;

final class CollectAction
{
    public function __invoke()
    {
        return [
            'queue' => $this->resolveQueueStatus(),
        ];
    }

    private function resolveQueueStatus(): array
    {
        $queueConnection = config('queue.default');

        if ($queueConnection === 'sync') {
            return [
                'handler' => 'Illuminate',
                'connection' => $queueConnection,
                'status' => 'inactive',
            ];
        }

        if (interface_exists(MasterSupervisorRepository::class)) {
            $horizonQueueStatus = $this->resolveHorizonQueueStatus();
            if ($horizonQueueStatus !== 'inactive') {
                return [
                    'handler' => 'Horizon',
                    'connection' => $queueConnection,
                    'status' => $horizonQueueStatus,
                ];
            }
        }

        return [
            'handler' => 'Illuminate',
            'connection' => $queueConnection,
            'status' => $this->resolveIlluminateQueueStatus(),
        ];
    }

    private function resolveHorizonQueueStatus(): string
    {
        if (!$masters = app(MasterSupervisorRepository::class)->all()) {
            return 'inactive';
        }

        return collect($masters)->contains(function ($master) {
            return $master->status === 'paused';
        }) ? 'paused' : 'running';
    }

    private function resolveIlluminateQueueStatus(): string
    {
        $uuid = Uuid::uuid4()->toString();

        $dispatcher = app(Dispatcher::class);
        $dispatcher->listen(QueueHasBeenPinged::class, QueuePingListener::class);
        $dispatcher->dispatch(new QueueHasBeenPinged($uuid));
        sleep(2);

        $storedUuid = File::get(storage_path('paket/queue-ping.log'));

        return $uuid === $storedUuid ? 'running' : 'inactive';
    }
}
