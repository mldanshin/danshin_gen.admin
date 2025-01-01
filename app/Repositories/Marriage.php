<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use App\Exceptions\BadRequestException;
use App\Models\Person\MarriageAviable;
use App\Models\Person\MarriageCreatedRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class Marriage extends Base
{
    /**
     * @return Collection<int, string>
     */
    public function getRolesAll(): Collection
    {
        $url = $this->baseUrlApi.'/api/marriage/roles';
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

    /**
     * @return Collection<int, string>
     */
    public function getRolesByGender(int $genderId): Collection
    {
        $url = $this->baseUrlApi."/api/marriage/roles/$genderId";
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

    public function getAviableByRequest(MarriageCreatedRequest $request): ?MarriageAviable
    {
        if ($request->roleId === null) {
            return null;
        }

        $obj = $this->aviableRequest(
            $request->personId,
            $request->birthDate,
            $request->parents,
            $request->roleId
        );

        return new MarriageAviable(
            $request->roleId,
            null,
            $obj->role,
            $obj->people
        );
    }

    /**
     * @return Collection<int, MarriageAviable>
     */
    public function getAviableByPerson(object $person): Collection
    {
        $collect = collect();

        if (isset($person->marriages)) {
            foreach ($person->marriages as $marriage) {
                $collect->push(
                    new MarriageAviable(
                        $marriage->role,
                        $marriage->soulmate,
                        $marriage->soulmateRole,
                        $this->aviableRequest(
                            $person->id,
                            $person->birthDate->string ?? null,
                            (isset($person->parents))
                                ? $this->parentsByPerson($person->parents)
                                : collect(),
                            $marriage->role
                        )->people
                    )
                );
            }
        }

        return $collect;
    }

    /**
     * @param  Collection<int, int>  $parents
     */
    private function aviableRequest(
        ?int $personId,
        ?string $birthDate,
        Collection $parents,
        int $roleId
    ): object {
        $query = http_build_query([
            'person_id' => $personId,
            'birth_date' => $birthDate,
            'parents' => $this->parents($parents),
            'role_id' => $roleId,
        ]);
        $url = $this->baseUrlApi.'/api/marriage/possible?'.$query;
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

        return $response->object();
    }

    /**
     * @param  Collection<int, int>  $parents
     * @return array<int>
     */
    private function parents(Collection $parents): array
    {
        $array = [];
        foreach ($parents as $parent) {
            $array[] = [
                'person' => $parent,
            ];
        }

        return $array;
    }

    /**
     * @param  array<int, object>  $parents
     * @return Collection<int, int>
     */
    private function parentsByPerson(array $parents): Collection
    {
        $collect = collect();
        foreach ($parents as $parent) {
            $collect->push($parent->person);
        }

        return $collect;
    }
}
