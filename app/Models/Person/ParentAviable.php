<?php

namespace App\Models\Person;

use Illuminate\Support\Collection;

final readonly class ParentAviable
{
    /**
     * @param  Collection<int, object>  $aviablePerson
     */
    public function __construct(
        public ?int $parentId,
        public int $roleParentId,
        public Collection $aviablePerson
    ) {}
}
