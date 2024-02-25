<?php

namespace App\DataTransferObjects;

use Illuminate\Support\Collection;
use InvalidArgumentException;

readonly class DemoTestDto
{
    public function __construct(
        public string $ref,
        public string $name,
        public ?string $description = null,
    ) {
    }

    public static function fromJsonArray(string $json): Collection
    {
        if (!json_validate($json)) {
            throw new InvalidArgumentException('Invalid payload provided.');
        }

        $data = json_decode($json, true);

        return collect($data)
            ->map(function ($data) {
                return new self(
                    $data['ref'],
                    $data['name'],
                    $data['description'] ?? null
                );
            });
    }
}
