<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use App\Models\Person\ParentCreatedRequest as ParentCreatedRequestModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

final class ParentRepository extends Base
{
    /**
     * @return Collection<int, string>
     */
    public function getRolesAll(): Collection
    {
        $url = $this->baseUrlApi . "/api/parent/roles";
        $response = Http::withToken($this->tokenApi)
            ->acceptJson()
            ->get($url);

        if (!$response->ok()) {
            throw new ApiException(
                "Request for url = $url returned {$response->status()}.",
                $response->status()
            );
        }

        return collect($response->json());
    }

    /**
     * @return Collection<int, object>|null
     */
    public function getParentsAviable(ParentCreatedRequestModel $request): ?Collection
    {
        if ($request->roleId === null) {
            return null;
        }

        $query = http_build_query([
            "person_id" => $request->personId,
            "birth_date" => $request->birthDate,
            "mariages" => $this->mariages($request->mariages),
            "role_id" => $request->roleId
        ]);
        $url = $this->baseUrlApi . "/api/parent/possible?" . $query;
        $response = Http::withToken($this->tokenApi)
            ->acceptJson()
            ->get($url);

        if (!$response->ok()) {
            throw new ApiException(
                "Request for url = $url returned {$response->status()}.",
                $response->status()
            );
        }

        $collect = collect();
        $array = $response->json();
        foreach ($array as $item) {
            $collect->push((object) $item);
        }

        return $collect;
    }

    private function mariages(Collection $mariages): array
    {
        $array = [];
        foreach ($mariages as $mariage) {
            $array[] = [
                "person" => $mariage
            ];
        }
        return $array;
    }
}
