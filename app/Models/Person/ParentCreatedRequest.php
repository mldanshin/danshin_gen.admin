<?php

namespace App\Models\Person;

use Illuminate\Support\Collection;

final readonly class ParentCreatedRequest
{
    /**
     * @param Collection<int, int> $mariages
     */
    public function __construct(
        public ?int $personId,
        public ?string $birthDate,
        public ?int $roleId,
        public Collection $mariages,
    ) {
    }
}
