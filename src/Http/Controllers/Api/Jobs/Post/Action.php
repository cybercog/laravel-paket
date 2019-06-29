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

namespace Cog\Laravel\Paket\Http\Controllers\Api\Jobs\Post;

use Cog\Contracts\Paket\Job\Entities\Job as JobContract;
use Cog\Contracts\Paket\Job\Repositories\Job as JobRepositoryContract;
use Cog\Laravel\Paket\Job\Entities\Job;
use Cog\Laravel\Paket\Process\Entities\Process;
use Cog\Laravel\Paket\Requirement\Entities\Requirement;
use Cog\Laravel\Paket\Requirement\Events\RequirementInstalling;
use Cog\Laravel\Paket\Requirement\Events\RequirementUninstalling;
use Cog\Laravel\Paket\Support\Composer;
use Illuminate\Contracts\Support\Responsable as ResponsableContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;
use MCStreetguy\ComposerParser\Factory as ComposerParser;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Action
{
    public function __invoke(JobRepositoryContract $jobs, Composer $composer, Request $request): ResponsableContract
    {
        if ($request->input('type') === 'RequirementInstall') {
            $job = $this->install($jobs, $request);
        } else {
            $job = $this->uninstall($jobs, $request);
        }

        return new Response($job);
    }

    // TODO: Extract to RequirementFileRepository
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

    private function install(JobRepositoryContract $jobs, Request $request): JobContract
    {
        $requirement = Requirement::fromArray($request->input('requirement'));

        $installedRequirements = $this->getInstalledRequirements();

        $installedRequirement = Arr::first($installedRequirements, function (array $value) use ($requirement) {
            return $value['name'] === $requirement->getName();
        });

        if (!is_null($installedRequirement)) {
            if ($installedRequirement['version'] === $requirement->getVersion()
                && $installedRequirement['isDevelopment'] === $requirement->isDevelopment()) {
                throw ValidationException::withMessages([
                    'name' => [
                        "Package `{$requirement}` already installed",
                    ],
                ]);
            }
        }

        $job = new Job(
            'RequirementInstall',
            Uuid::uuid4()->toString(),
            'Waiting',
            Carbon::now(),
            new Process(),
            $requirement
        );

        $jobs->store($job);

        event(new RequirementInstalling($requirement, $job));

        return $job;
    }

    private function uninstall(JobRepositoryContract $jobs, Request $request): JobContract
    {
        $installedRequirements = $this->getInstalledRequirements();
        $requirementName = $request->input('requirement.name');

        $installedRequirement = Arr::first($installedRequirements, function (array $value) use ($requirementName) {
            return $value['name'] === $requirementName;
        });

        if (is_null($installedRequirement)) {
            throw new NotFoundHttpException();
        }

        $requirement = Requirement::fromArray($installedRequirement);

        $job = new Job(
            'RequirementUninstall',
            Uuid::uuid4()->toString(),
            'Waiting',
            Carbon::now(),
            new Process(),
            $requirement
        );

        $jobs->store($job);

        event(new RequirementUninstalling($requirement, $job));

        return $job;
    }
}
