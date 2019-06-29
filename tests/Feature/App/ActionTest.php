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

namespace Cog\Tests\Laravel\Paket\Feature\App;

use Cog\Tests\Laravel\Paket\TestCase;

final class ActionTest extends TestCase
{
    /** @test */
    public function it_visible_to_guest(): void
    {
        $this->artisan('paket:setup');

        $response = $this->get('/paket');

        $response->assertStatus(200);
    }
}
