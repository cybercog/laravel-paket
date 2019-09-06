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

namespace Cog\Laravel\Paket\Job\Repositories;

use Cog\Contracts\Paket\Job\Entities\Job as JobContract;
use Cog\Contracts\Paket\Job\Repositories\Job as JobRepositoryContract;
use Cog\Laravel\Paket\Job\Entities\Job;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class JobFileRepository implements JobRepositoryContract
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $files;

    /**
     * The path to store jobs data.
     *
     * @var string
     */
    private $storagePath;

    /**
     * Create a new job file repository instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param string $storagePath
     */
    public function __construct(Filesystem $files, string $storagePath)
    {
        $this->files = $files;
        $this->storagePath = $storagePath;
    }

    public function all(): iterable
    {
        $index = $this->getIndex();

        $jobs = [];
        foreach ($index as $job) {
            $jobs[] = Job::fromArray($job);
        }

        return $jobs;
    }

    public function getById(string $id): JobContract
    {
        $index = $this->getIndex();

        foreach ($index as $job) {
            if ($job['id'] === $id) {
                $job['process']['output'] = $this->getJobProcessOutput($job['id']);

                return Job::fromArray($job);
            }
        }

        throw new NotFoundHttpException("Job with id `{$id}` not found.");
    }

    // TODO: [v2.0] Add to contract
    public function deleteById(string $id): void
    {
        $index = $this->getIndex();

        foreach ($index as $key => $job) {
            if ($job['id'] === $id) {
                $jobKey = $key;
                break;
            }
        }

        if (!isset($jobKey)) {
            throw new NotFoundHttpException("Job with id `{$id}` not found.");
        }

        unset($index[$jobKey]);
        $index = array_values($index);

        $this->deleteJobProcessOutput($id);

        $this->putIndex($index);
    }

    public function store(JobContract $job): void
    {
        $index = $this->getIndex();

        $index[] = $job->toArray();

        $this->putIndex($index);
    }

    public function changeJobStatus(JobContract $job, string $statusName): void
    {
        $names = [
            'Pending',
            'Running',
            'Success',
            'Failed',
        ];

        if (!in_array($statusName, $names)) {
            // TODO: Throw custom exception
            throw new RuntimeException('Unknown job status');
        }

        $index = $this->getIndex();

        foreach ($index as $key => $record) {
            if ($record['id'] === $job->getId()) {
                $index[$key]['status'] = $statusName;
                break;
            }
        }

        $this->putIndex($index);
    }

    public function changeJobExitCode(JobContract $job, int $exitCode): void
    {
        $index = $this->getIndex();

        foreach ($index as $key => $record) {
            if ($record['id'] === $job->getId()) {
                $index[$key]['process']['exitCode'] = $exitCode;
                break;
            }
        }

        $this->putIndex($index);
    }

    private function getIndex(): array
    {
        try {
            return json_decode($this->files->get($this->getIndexFilePath(), $isLocked = true), true);
        } catch (FileNotFoundException $exception) {
            return [];
        }
    }

    private function putIndex(iterable $index): void
    {
        $this->files->put($this->getIndexFilePath(), json_encode($index));
    }

    private function getJobProcessOutput(string $jobId): string
    {
        $path = sprintf('%s/jobs/%s.log', $this->storagePath, $jobId);

        try {
            return $this->files->get($path);
        } catch (FileNotFoundException $exception) {
            return '';
        }
    }

    private function deleteJobProcessOutput(string $jobId): void
    {
        $path = sprintf('%s/jobs/%s.log', $this->storagePath, $jobId);
        $this->files->delete($path);
    }

    private function getIndexFilePath(): string
    {
        return $this->storagePath . '/jobs.json';
    }
}
