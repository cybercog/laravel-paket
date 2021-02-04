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

namespace Cog\Laravel\Paket;

use Cog\Contracts\Paket\Job\Repositories\Job as JobRepositoryContract;
use Cog\Laravel\Paket\Console\Commands\Setup;
use Cog\Laravel\Paket\Job\Events\JobHasBeenCreated;
use Cog\Laravel\Paket\Job\Listeners\JobRunner;
use Cog\Laravel\Paket\Job\Repositories\JobFileRepository;
use Cog\Laravel\Paket\Support\Composer;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class PaketServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->configure();
        $this->registerConsoleCommands();
    }

    public function boot(): void
    {
        $this->registerMiddlewareGroups();
        $this->registerPublishes();
        $this->registerResources();
        $this->registerRoutes();
        $this->registerBindings();
        $this->registerListeners();
    }

    private function getRouteConfiguration(): array
    {
        return [
            'namespace' => 'Cog\Laravel\Paket\Http\Controllers',
            'prefix' => ltrim(Config::get('paket.base_uri', 'paket'), '/'),
            'middleware' => 'paket',
        ];
    }

    /**
     * Merge Paket configuration with the application configuration.
     *
     * @return void
     */
    private function configure(): void
    {
        if (!$this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/paket.php', 'paket');
        }
    }

    private function registerMiddlewareGroups(): void
    {
        Route::middlewareGroup('paket', Config::get('paket.middlewares', []));
    }

    private function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'paket');
    }

    private function registerPublishes(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/paket'),
            ], 'paket-assets');

            $this->publishes([
                __DIR__ . '/../config/paket.php' => config_path('paket.php'),
            ], 'paket-config');
        }
    }

    private function registerRoutes(): void
    {
        Route::group($this->getRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    private function registerConsoleCommands(): void
    {
        $this->commands([
            Setup::class,
        ]);
    }

    private function registerBindings(): void
    {
        $this->app->singleton(Composer::class, function () {
            return new Composer(
                $this->app->make(Filesystem::class),
                base_path(),
                storage_path('paket/jobs'),
                Config::get('paket.composer_path', '/usr/bin/composer')
            );
        });

        $this->app->singleton(JobRepositoryContract::class, function () {
            return new JobFileRepository(
                $this->app->make(Filesystem::class),
                storage_path('paket')
            );
        });
    }

    private function registerListeners(): void
    {
        Event::listen(JobHasBeenCreated::class, JobRunner::class);
    }
}
