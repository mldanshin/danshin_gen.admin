<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use App\Exceptions\BadRequestException;
use App\Models\People\Person as PersonModel;
use App\Models\People\Request as RequestModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class People extends Base
{
    /**
     * @return Collection<int, PersonModel>
     */
    public function show(RequestModel $request): Collection
    {
        $url = $this->baseUrlApi.'/api/people'.$this->buildQueryFilterOrder($request);
        $response = Http::withToken($this->tokenApi)
            ->acceptJson()
            ->get($url);

        if ($response->status() === 422) {
            throw new BadRequestException(
                "Request for url = $url returned {$response->status()}."
            );
        }

        if (! $response->ok()) {
            throw new ApiException(
                "Request for url = $url returned {$response->status()}.",
                $response->status()
            );
        }

        $json = $response->json();

        $people = collect();

        foreach ($json as $item) {
            $people->push(
                new PersonModel(
                    $item['id'],
                    $item['surname'],
                    $this->getOldSurname(($item['oldSurname'])),
                    $item['name'],
                    $item['patronymic'],
                )
            );
        }

        return $people;
    }

    /**
     * @param  array<string>  $json
     * @return Collection<int, string>
     */
    private function getOldSurname(?array $json): Collection
    {
        $collect = collect();

        if ($json !== null) {
            foreach ($json as $item) {
                $collect->push($item);
            }
        }

        return $collect;
    }
}
