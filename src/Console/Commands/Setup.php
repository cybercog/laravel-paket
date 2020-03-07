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

namespace Cog\Laravel\Paket\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;

final class Setup extends Command
{
    protected $name = 'paket:setup';

    protected $description = 'Set up all of the Paket resources';

    public function handle(): int
    {
        $this->warn('Paket scaffolding set up starting');
        $this->publishAssets();
        $this->createPaketStorage(storage_path('paket'));
        $this->createPaketJobsStorage(storage_path('paket/jobs'));
        $this->info('Paket scaffolding set up completed');

        return 0;
    }

    protected function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Overwrite any existing files'],
        ];
    }

    private function publishAssets(): void
    {
        if ($this->option('force')) {
            $this->warn('Force publishing Paket assets');
            $this->call('vendor:publish', [
                '--tag' => 'paket-assets',
                '--force' => $this->option('force'),
            ]);
        } else {
            $this->warn('Publishing Paket assets');
            $this->call('vendor:publish', [
                '--tag' => 'paket-assets',
            ]);
        }
        $this->info('Published Paket assets');
    }

    private function createPaketStorage(string $path): void
    {
        $this->createDirectory($path);
        $this->createGitIgnore($path, "*\n!jobs/\n!.gitignore\n");
    }

    private function createPaketJobsStorage(string $path): void
    {
        $this->createDirectory($path);
        $this->createGitIgnore($path, "*\n!.gitignore\n");
    }

    private function createDirectory(string $path): void
    {
        if (file_exists($path)) {
            return;
        }

        $this->warn("Creating `{$path}` directory");
        mkdir($path);
        $this->info("Created `{$path}` directory");
    }

    private function createGitIgnore(string $path, string $content): void
    {
        $filepath = sprintf('%s/.gitignore', $path);
        if (file_exists($filepath)) {
            return;
        }

        $this->warn("Creating `{$filepath}` file");
        File::put($filepath, $content);
        $this->info("Created `{$filepath}` file");
    }
}
