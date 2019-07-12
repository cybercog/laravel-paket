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

namespace Cog\Laravel\Paket\Http\Controllers\Api\Requirements;

use Illuminate\Contracts\Support\Responsable as ResponsableContract;
use MCStreetguy\ComposerParser\Factory as ComposerParser;

final class CollectAction
{
    public function __invoke(): ResponsableContract
    {
        $requirements = $this->getInstalledRequirements();

        return new CollectResponse($requirements);
    }

    private function getInstalledRequirements(): array
    {
        $lockFile = ComposerParser::parseLockfile(base_path('composer.lock'));
        $jsonFile = ComposerParser::parseComposerJson(base_path('composer.json'));

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

        $requires = $jsonFile->getRequire()->getData();
        $devRequires = $jsonFile->getRequireDev()->getData();

        $roots = [
            'essential' => [],
            'dev' => [],
        ];
        foreach ($platform as $name => $version) {
            $roots['essential'][] = [
                'name' => $name,
                'version' => $version,
            ];
        }
        foreach ($devPlatform as $name => $version) {
            $roots['dev'][] = [
                'name' => $name,
                'version' => $version,
            ];
        }
        foreach ($packages as $key => $package) {
            if (isset($requires[$package['name']]) || isset($devRequires[$package['name']])) {
                $roots['essential'][] = $package;
                unset($packages[$key]);
            }
        }
        foreach ($devPackages as $key => $package) {
            if (isset($requires[$package['name']]) || isset($devRequires[$package['name']])) {
                $roots['dev'][] = $package;
                unset($devPackages[$key]);
            }
        }
        $dependencies = [
            'essential' => [],
            'dev' => [],
        ];
        foreach ($packages as $key => $package) {
            $dependencies['essential'][] = $package;
        }
        foreach ($devPackages as $key => $package) {
            $dependencies['dev'][] = $package;
        }

        return [
            'roots' => $roots,
            'dependencies' => $dependencies,
        ];
    }
}
