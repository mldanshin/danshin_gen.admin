<?php

namespace App\Models\Person;

final readonly class MarriageAviable
{
    /**
     * @param  array<int, object>  $aviablePerson
     */
    public function __construct(
        public int $roleId,
        public ?int $soulmateId,
        public int $soulmateRoleId,
        public array $aviablePerson
    ) {}
}
