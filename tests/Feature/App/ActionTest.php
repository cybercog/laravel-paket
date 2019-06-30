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

use Cog\Laravel\Paket\Http\Middlewares\Authorize;
use Cog\Tests\Laravel\Paket\TestCase;
use Orchestra\Testbench\Http\Middleware\VerifyCsrfToken;

final class ActionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            Authorize::class,
            VerifyCsrfToken::class,
        ]);
    }

    /** @test */
    public function it_exposes_dashboard_when_url_is_default(): void
    {
        $response = $this->get('/paket');

        $response->assertStatus(200);
    }
}
