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

namespace Cog\Laravel\Paket\Support;

use Cog\Contracts\Paket\Job\Exceptions\JobFailed;
use Cog\Contracts\Paket\Job\Entities\Job as JobContract;
use Cog\Contracts\Paket\Requirement\Entities\Requirement as RequirementContract;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ProcessUtils;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

final class Composer
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $files;

    /**
     * The working path to execute Composer from.
     *
     * @var string
     */
    private $workingPath;

    /**
     * The logging path to store Composer logs.
     *
     * @var string
     */
    private $loggingPath;

    /**
     * Create a new Composer manager instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param string $workingPath
     * @param string $loggingPath
     * @return void
     */
    public function __construct(Filesystem $files, string $workingPath, string $loggingPath)
    {
        $this->files = $files;
        $this->workingPath = $workingPath;
        $this->loggingPath = $loggingPath;
    }

    /**
     * Regenerate the Composer autoloader files.
     *
     * @param array $extra
     * @return void
     */
    public function dumpAutoload(array $extra = []): void
    {
        $command = array_merge($this->getComposerExecutable(), ['dump-autoload'], $extra);

        $this->getProcess($command)->run();
    }

    /**
     * Regenerate the optimized Composer autoloader files.
     *
     * @return void
     */
    public function dumpOptimized(): void
    {
        $this->dumpAutoload(['--optimize']);
    }

    /**
     * Install Composer requirement.
     *
     * @param \Cog\Contracts\Paket\Requirement\Entities\Requirement $requirement
     * @param \Cog\Contracts\Paket\Job\Entities\Job $job
     * @return void
     *
     * @throws \Cog\Contracts\Paket\Job\Exceptions\JobFailed
     */
    public function install(RequirementContract $requirement, JobContract $job): void
    {
        $flags = [
            '--no-interaction',
        ];

        if ($requirement->isDevelopment()) {
            $flags[] = '--dev';
        }

        $command = sprintf(
            '%s require %s %s',
            '/usr/bin/composer',
            $requirement,
            implode(' ', $flags)
        );

        $this->executeCommand($job, $command);
    }

    /**
     * Uninstall Composer requirement.
     *
     * @param \Cog\Contracts\Paket\Requirement\Entities\Requirement $requirement
     * @param \Cog\Contracts\Paket\Job\Entities\Job $job
     * @return void
     *
     * @throws \Cog\Contracts\Paket\Job\Exceptions\JobFailed
     */
    public function uninstall(RequirementContract $requirement, JobContract $job): void
    {
        $flags = [
            '--no-interaction',
        ];

        if ($requirement->isDevelopment()) {
            $flags[] = '--dev';
        }

        $command = sprintf(
            '%s remove %s %s',
            '/usr/bin/composer',
            $requirement->getName(),
            implode(' ', $flags)
        );

        $this->executeCommand($job, $command);
    }

    /**
     * TODO: Extract this method out from the `Composer` class
     *
     * @param \Cog\Contracts\Paket\Job\Entities\Job $job
     * @param string $command
     *
     * @throws \Cog\Contracts\Paket\Job\Exceptions\JobFailed
     */
    private function executeCommand(JobContract $job, string $command): void
    {
        $jobLogFile = sprintf('%s/%s.log', $this->loggingPath, $job->getId());
        $this->addLineToLogFile($jobLogFile, "$ {$command}\n");

        $commands = [
            sprintf('export COMPOSER_HOME=%s', '~/.composer'),
            sprintf('cd %s', $this->workingPath),
            $command,
        ];

        $fullCommand = implode(' && ', $commands);
        $isPtyMode = true;
        $timeout = 5 * 60;

        $process = Process::fromShellCommandline($fullCommand);
        $process->setPty($isPtyMode);
        $process->setTimeout($timeout);
        $process->start();

        foreach ($process as $type => $data) {
            $this->addLineToLogFile($jobLogFile, trim($data));
        }

        $this->addLineToLogFile($jobLogFile, "\n\nDone. Job exited with {$process->getExitCode()}.");

        if ($process->getExitCode() !== 0) {
            throw JobFailed::withExitCode($job, $process->getExitCode());
        }
    }

    private function getComposerExecutable(): array
    {
        if ($this->files->exists($this->workingPath . '/composer.phar')) {
            return [$this->getPhpBinary(), 'composer.phar'];
        }

        return ['composer'];
    }

    private function getPhpBinary(): string
    {
        return ProcessUtils::escapeArgument((new PhpExecutableFinder)->find(false));
    }

    private function getProcess(array $command): Process
    {
        return (new Process($command, $this->workingPath))->setTimeout(null);
    }

    private function addLineToLogFile(string $jobLogFile, string $line): void
    {
        $this->files->append($jobLogFile, $line . "\n");
    }
}
