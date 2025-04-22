<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesIdempotencyKey
{
    /**
     * Generate a unique idempotency key
     *
     * @return string
     */
    public function generateIdempotencyKey(): string
    {
        return Str::uuid()->toString();
    }

    /**
     * Generate an idempotency key based on specific data
     *
     * @param array $data
     * @return string
     */
    public function generateIdempotencyKeyFromData(array $data): string
    {
        return md5(json_encode($data) . microtime(true));
    }
} 