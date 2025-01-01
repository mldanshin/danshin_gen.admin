<?php

namespace App\Repositories;

use App\Models\People\FilterOrder\OrderType;
use Illuminate\Support\Collection;

final class Order
{
    /**
     * @return Collection<string, OrderType>
     */
    public function getList(): Collection
    {
        return collect([
            'age' => OrderType::AGE,
            'name' => OrderType::NAME,
        ]);
    }
}
