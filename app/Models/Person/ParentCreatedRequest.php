<?php

namespace App\Models\Person;

use Illuminate\Support\Collection;

final readonly class ParentCreatedRequest
{
    /**
     * @param  Collection<int, int>  $marriages
     */
    public function __construct(
        public ?int $personId,
        public ?string $birthDate,
        public ?int $roleId,
        public Collection $marriages,
    ) {}
}
