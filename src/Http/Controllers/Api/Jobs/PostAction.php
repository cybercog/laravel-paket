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

namespace Cog\Laravel\Paket\Http\Controllers\Api\Jobs;

use Cog\Contracts\Paket\Job\Repositories\Job as JobRepositoryContract;
use Cog\Contracts\Paket\Requirement\Entities\Requirement as RequirementContract;
use Cog\Laravel\Paket\Job\Entities\Job;
use Cog\Laravel\Paket\Job\Events\JobHasBeenCreated;
use Cog\Laravel\Paket\Process\Entities\Process;
use Cog\Laravel\Paket\Requirement\Entities\Requirement;
use Illuminate\Contracts\Support\Responsable as ResponsableContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use MCStreetguy\ComposerParser\Factory as ComposerParser;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class PostAction
{
    private $jobs;

    public function __construct(JobRepositoryContract $jobs)
    {
        $this->jobs = $jobs;
    }

    public function __invoke(PostRequest $request): ResponsableContract
    {
        $type = $request->input('type');
        $requirement = Requirement::fromArray($request->input('requirement'));

        switch ($type) {
            case 'RequirementInstall':
                if ($this->isRequirementInstalled($requirement)) {
                    throw ValidationException::withMessages([
                        'name' => [
                            "Package `{$requirement}` already installed",
                        ],
                    ]);
                }
                break;
            case 'RequirementUninstall':
                $requirement = $this->getInstalledRequirement($requirement);
                break;
        }

        $job = new Job(
            $type,
            strval(Uuid::uuid4()),
            'Pending',
            Carbon::now(),
            new Process(),
            $requirement
        );

        $this->jobs->store($job);

        event(new JobHasBeenCreated($job));

        return new PostResponse($job);
    }

    private function getInstalledRequirements(): array
    {
        $lockFile = ComposerParser::parseLockfile(base_path('composer.lock'));

        $packages = $lockFile->getPackages()->getData();
        $devPackages = $lockFile->getPackagesDev()->getData();
        $platform = $lockFile->getPlatform()->getData();
        $devPlatform = $lockFile->getPlatformDev()->getData();
        foreach ($packages as &$package) {
            $package['isDevelopment'] = false;
        }
        foreach ($devPackages as &$package) {
            $package['isDevelopment'] = true;
        }
        $platforms = [];
        foreach ($platform as $name => $version) {
            $platforms[] = [
                'name' => $name,
                'version' => $version,
                'isDevelopment' => false,
            ];
        }
        foreach ($devPlatform as $name => $version) {
            $platforms[] = [
                'name' => $name,
                'version' => $version,
                'isDevelopment' => true,
            ];
        }

        return array_merge($packages, $devPackages, $platforms);
    }

    private function getInstalledRequirement(RequirementContract $requirement): RequirementContract
    {
        $installedRequirements = $this->getInstalledRequirements();

        $installedRequirement = Arr::first($installedRequirements, function (array $value) use ($requirement) {
            return $value['name'] === $requirement->getName();
        });

        if (is_null($installedRequirement)) {
            throw new NotFoundHttpException();
        }

        $requirement = Requirement::fromArray($installedRequirement);

        return $requirement;
    }

    private function isRequirementInstalled(RequirementContract $requirement): bool
    {
        $installedRequirements = $this->getInstalledRequirements();

        $installedRequirement = Arr::first($installedRequirements, function (array $value) use ($requirement) {
            return $value['name'] === $requirement->getName();
        });

        return !is_null($installedRequirement)
            && $installedRequirement['version'] === $requirement->getVersion()
            && $installedRequirement['isDevelopment'] === $requirement->isDevelopment();
    }
}
