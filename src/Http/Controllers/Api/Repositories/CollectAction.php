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

namespace Cog\Laravel\Paket\Http\Controllers\Api\Repositories;

use Illuminate\Contracts\Support\Responsable as ResponsableContract;
use MCStreetguy\ComposerParser\Factory as ComposerParser;

final class CollectAction
{
    public function __invoke(): ResponsableContract
    {
        $repositories = $this->getRepositories();

        return new CollectResponse($repositories);
    }

    private function getRepositories(): array
    {
        $jsonFile = ComposerParser::parseComposerJson(base_path('composer.json'));

        $repositories = $jsonFile->getRepositories();
        $output = [];

        foreach ($repositories as $key => $repository) {
            $package = $repository->getPackage();

            $output[$key] = [
                'type' => $repository->getType(),
                'url' => $repository->getUrl(),
                'options' => $repository->getOptions(),
                'package' => is_null($package) ? null : $package->toArray(),
            ];
        }

        $output = $this->addDefaultRepository($output);

        return $output;
    }

    // TODO: Remove after fix: https://github.com/MCStreetguy/ComposerParser/issues/4
    private function addDefaultRepository(array $repositories): array
    {
        $isPackagistOrgDisabled = false;
        foreach ($repositories as $key => $repository) {
            // This hacky temporary solution the only way to determine `{"packagist.org": false}` repository object
            // But in fact it will display that packagist ignored even with any empty object
            if ($repository['type'] === '' && $repository['url'] === '' && $repository['options'] === [] && is_null($repository['package'])) {
                $isPackagistOrgDisabled = true;
                unset($repositories[$key]);
            }
        }

        if ($isPackagistOrgDisabled) {
            return $repositories;
        }

        $defaultRepository = [
            'type' => 'composer',
            'url' => 'https?://repo.packagist.org',
            'allow_ssl_downgrade' => true,
        ];

        if (!isset($repositories['packagist.org'])) {
            $repositories['packagist.org'] = [];
        }

        $repositories['packagist.org'] = array_merge($defaultRepository, $repositories['packagist.org']);

        return $repositories;
    }
}
