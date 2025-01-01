<?php

namespace App\Repositories;

use App\Models\People\Request as RequestModel;

abstract class Base
{
    protected readonly string $baseUrlApi;

    protected readonly string $tokenApi;

    public function __construct()
    {
        $this->baseUrlApi = config('app.api.url');
        $this->tokenApi = config('app.api.token');
    }

    protected function buildQueryFilterOrder(RequestModel $request): string
    {
        $query = http_build_query([
            'search' => $request->search,
            'order' => $request->orderType,
        ]);

        return (empty($query)) ? '' : '?'.$query;
    }
}
