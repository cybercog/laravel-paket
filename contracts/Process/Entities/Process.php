<?php

/*
 * This file is part of PHP Contracts: Paket.
 *
 * (c) Anton Komarev <anton@komarev.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Cog\Contracts\Paket\Process\Entities;

interface Process
{
    public static function fromArray(array $process): self;

    public function toArray(): array;
}
