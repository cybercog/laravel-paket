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
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Support\Facades\File;

final class Setup extends Command
{
    use DetectsApplicationNamespace;

    protected $signature = 'paket:setup {--force : Overwrite any existing files}';

    protected $description = 'Set up all of the Paket resources';

    public function handle(): int
    {
        if ($this->option('force')) {
            $this->comment('Force publishing Paket Assets...');
            $this->call('vendor:publish', [
                '--tag' => 'paket-assets',
                '--force' => $this->option('force'),
            ]);
        } else {
            $this->comment('Publishing Paket Assets...');
            $this->call('vendor:publish', [
                '--tag' => 'paket-assets',
            ]);
        }

        $paketStoragePath = storage_path('paket');
        $this->comment("Creating `{$paketStoragePath}` Directory...");
        $this->createPaketStorage($paketStoragePath);

        $paketJobsStoragePath = storage_path('paket/jobs');
        $this->comment("Creating `{$paketJobsStoragePath}` Directory...");
        $this->createPaketJobsStorage($paketJobsStoragePath);

        $this->info('Paket scaffolding set up completed.');

        return 0;
    }

    private function createPaketStorage(string $path): void
    {
        $this->createGitIgnore($path, "*\n!jobs/\n!.gitignore\n");
    }

    private function createPaketJobsStorage(string $path): void
    {
        $this->createGitIgnore($path, "*\n!.gitignore\n");
    }

    private function createGitIgnore(string $path, string $content): void
    {
        if (!file_exists($path)) {
            mkdir($path);
        }

        $filepath = sprintf('%s/.gitignore', $path);

        if (!file_exists($filepath)) {
            File::put($filepath, $content);
        }
    }
}
