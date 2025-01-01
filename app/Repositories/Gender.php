<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class Gender extends Base
{
    /**
     * @return Collection<int, string>
     */
    public function getAll(): Collection
    {
        $url = $this->baseUrlApi.'/api/genders';
        $response = Http::withToken($this->tokenApi)
            ->acceptJson()
            ->get($url);

        if (! $response->ok()) {
            throw new ApiException(
                "Request for url = $url returned {$response->status()}.",
                $response->status()
            );
        }

        return collect($response->json());
    }
}
