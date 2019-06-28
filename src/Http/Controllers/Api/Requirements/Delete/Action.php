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

namespace Cog\Laravel\Paket\Http\Controllers\Api\Requirements\Delete;

use Cog\Contracts\Paket\Job\Repositories\JobRepository as JobRepositoryContract;
use Cog\Laravel\Paket\Job\Entities\Job;
use Cog\Laravel\Paket\Requirement\Entities\Requirement;
use Cog\Laravel\Paket\Requirement\Events\RequirementUninstalling;
use Illuminate\Contracts\Support\Responsable as ResponsableContract;
use Illuminate\Support\Arr;
use MCStreetguy\ComposerParser\Factory as ComposerParser;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class Action
{
    public function __invoke(string $uid, JobRepositoryContract $jobs): ResponsableContract
    {
        $installedRequirements = $this->getInstalledRequirements();
        $requirementName = base64_decode($uid);

        $installedRequirement = Arr::first($installedRequirements, function (array $value) use ($requirementName) {
            return $value['name'] === $requirementName;
        });

        if (is_null($installedRequirement)) {
            throw new NotFoundHttpException();
        }

        $job = Job::ofType('ComposerUninstall');

        $requirement = new Requirement(
            $installedRequirement['name'],
            $installedRequirement['version'],
            $installedRequirement['isDevelopment']
        );

        $jobs
            ->store($job, $requirement);

        event(new RequirementUninstalling($requirement, $job));

        return new Response($requirement, $job);
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
}
