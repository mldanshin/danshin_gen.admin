<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Http;

final class Photo extends Base
{
    public function getFile(int $personId, string $fileName): string
    {
        $url = $this->baseUrlApi."/api/photo/{$personId}/{$fileName}";
        $response = Http::withToken($this->tokenApi)
            ->withHeaders([
                'accept' => 'application/octet-stream',
            ])
            ->acceptJson()
            ->get($url);

        if (! $response->ok()) {
            throw new ApiException(
                "Request for url = $url returned {$response->status()}.",
                $response->status()
            );
        }

        return $response->body();
    }
}
