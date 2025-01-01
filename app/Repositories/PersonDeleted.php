<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Http;

final class PersonDeleted extends Base
{
    public function delete(int $personId): void
    {
        $url = $this->baseUrlApi."/api/person/$personId";
        $response = Http::withToken($this->tokenApi)
            ->acceptJson()
            ->delete($url);

        if (! $response->ok()) {
            throw new ApiException(
                "Request for url = $url returned {$response->status()}.",
                $response->status()
            );
        }
    }
}
