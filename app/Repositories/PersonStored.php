<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use App\Exceptions\BadRequestException;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Http;

final class PersonStored extends Base
{
    use PersonRequest;

    public function store(Request $request): int
    {
        $url = $this->baseUrlApi.'/api/person';
        $response = Http::withToken($this->tokenApi)
            ->acceptJson()
            ->post($url, $this->getData($request));

        if ($response->status() === 422) {
            throw new BadRequestException(
                "Request for url = $url returned {$response->status()}."
                    .json_encode($response->json(), JSON_UNESCAPED_UNICODE)
            );
        }

        if (! $response->ok()) {
            throw new ApiException(
                "Request for url = $url returned {$response->status()}.",
                $response->status()
            );
        }

        return $response->json()['person_id'];
    }
}
