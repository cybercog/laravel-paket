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

namespace Cog\Laravel\Paket\Requirement\Entities;

use Cog\Contracts\Paket\Requirement\Entities\Requirement as RequirementContract;

final class Requirement implements RequirementContract
{
    private $name;

    private $version;

    private $isDevelopment;

    public function __construct(string $name, ?string $version = null, bool $isDevelopment = false)
    {
        $this->name = $name;
        $this->version = $version;
        $this->isDevelopment = $isDevelopment;
    }

    public function __toString(): string
    {
        if (is_null($this->version)) {
            return $this->name;
        }

        return sprintf('%s:%s', $this->name, $this->version);
    }

    public static function fromArray(array $requirement): RequirementContract
    {
        return new self(
            $requirement['name'],
            $requirement['version'] ?? null,
            $requirement['isDevelopment'] ?? false
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'version' => $this->version,
            'isDevelopment' => $this->isDevelopment,
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function isDevelopment(): bool
    {
        return $this->isDevelopment;
    }

    public function isNotDevelopment(): bool
    {
        return !$this->isDevelopment();
    }
}
