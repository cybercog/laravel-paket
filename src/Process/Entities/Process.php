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

namespace Cog\Laravel\Paket\Process\Entities;

use Cog\Contracts\Paket\Process\Entities\Process as ProcessContract;

final class Process implements ProcessContract
{
    private $output;

    private $exitCode;

    public function __construct(?string $output = null, ?int $exitCode = null)
    {
        $this->output = $output;
        $this->exitCode = $exitCode;
    }

    public static function fromArray(array $process): ProcessContract
    {
        return new self(
            $process['output'] ?? null,
            $process['exitCode'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'output' => $this->output,
            'exitCode' => $this->exitCode,
        ];
    }
}
