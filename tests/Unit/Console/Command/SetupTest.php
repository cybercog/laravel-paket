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

namespace Cog\Tests\Laravel\Paket\Unit\Console\Commands;

use Cog\Tests\Laravel\Paket\TestCase;

final class SetupTest extends TestCase
{
    /** @test */
    public function it_publishes_assets_on_setup(): void
    {
        $this->withoutMockingConsoleOutput();
        $status = $this->artisan('paket:setup');

        $this->assertSame(0, $status);
    }

    /** @test */
    public function it_publishes_assets_on_setup_with_force_flag(): void
    {
        $this->withoutMockingConsoleOutput();
        $status = $this->artisan('paket:setup', [
            '--force' => true,
        ]);

        $this->assertSame(0, $status);
    }
}
