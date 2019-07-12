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

use Illuminate\Foundation\Http\FormRequest;

final class PostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'requirement.name' => [
                'required',
                'string',
            ],
            'requirement.version' => [
                'nullable',
                'string',
            ],
            'requirement.isDevelopment' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
