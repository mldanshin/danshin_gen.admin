<?php

namespace App\Models\Person;

use Illuminate\Support\Collection;

final readonly class MarriageCreatedRequest
{
    /**
     * @param  Collection<int, int>  $parents
     */
    public function __construct(
        public ?int $personId,
        public ?string $birthDate,
        public ?int $roleId,
        public Collection $parents,
    ) {}
}
