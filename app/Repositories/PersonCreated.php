<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Http;

final class PersonCreated extends Base
{
    public function get(): object
    {
        $url = $this->baseUrlApi.'/api/person/create';
        $response = Http::withToken($this->tokenApi)
            ->acceptJson()
            ->get($url);

        if (! $response->ok()) {
            throw new ApiException(
                "Request for url = $url returned {$response->status()}.",
                $response->status()
            );
        }

        return $response->object();
    }
}
