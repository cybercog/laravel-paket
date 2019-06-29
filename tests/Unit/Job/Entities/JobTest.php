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

namespace Cog\Tests\Laravel\Paket\Unit\Job\Entities;

use Cog\Laravel\Paket\Job\Entities\Job;
use Cog\Tests\Laravel\Paket\TestCase;
use Illuminate\Support\Carbon;

final class JobTest extends TestCase
{
    /** @test */
    public function it_can_instantiate_from_array(): void
    {
        $job = Job::fromArray([
            'type' => 'TestType',
            'id' => 'TestId',
            'status' => 'TestStatus',
            'process' => [],
            'requirement' => [
                'name' => 'cybercog/laravel-paket',
            ],
            'createdAt' => Carbon::now()->format(DATE_RFC3339_EXTENDED),
        ]);

        $this->assertInstanceOf(Job::class, $job);
        $this->assertSame('TestType', $job->getType());
    }
}
