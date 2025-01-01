<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use App\Exceptions\BadRequestException;
use App\Models\Person\ParentAviable;
use App\Models\Person\ParentCreatedRequest as ParentCreatedRequestModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

final class ParentRepository extends Base
{
    /**
     * @return Collection<int, string>
     */
    public function getRolesAll(): Collection
    {
        $url = $this->baseUrlApi.'/api/parent/roles';
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

    public function getParentsAviableByRequest(ParentCreatedRequestModel $request): ?ParentAviable
    {
        if ($request->roleId === null) {
            return null;
        }

        return new ParentAviable(
            null,
            $request->roleId,
            $this->aviablePerson($this->parentAviableRequest(
                $request->personId,
                $request->birthDate,
                $request->marriages,
                $request->roleId
            ))
        );
    }

    /**
     * @return Collection<int, ParentAviable>
     */
    public function getParentsAviableByPerson(object $person): Collection
    {
        $collect = collect();

        if (isset($person->parents)) {
            foreach ($person->parents as $parent) {
                $collect->push(
                    new ParentAviable(
                        $parent->person,
                        $parent->role,
                        $this->aviablePerson($this->parentAviableRequest(
                            $person->id,
                            $person->birthDate->string ?? null,
                            (isset($person->marriages))
                                ? $this->marriagesByPerson($person->marriages)
                                : collect(),
                            $parent->role
                        ))
                    )
                );
            }
        }

        return $collect;
    }

    /**
     * @param  Collection<int, int>  $marriages
     * @return array<int, mixed>
     */
    private function parentAviableRequest(
        ?int $personId,
        ?string $birthDate,
        Collection $marriages,
        int $parentRoleId
    ): array {
        $query = http_build_query([
            'person_id' => $personId,
            'birth_date' => $birthDate,
            'marriages' => $this->marriages($marriages),
            'role_id' => $parentRoleId,
        ]);
        $url = $this->baseUrlApi.'/api/parent/possible?'.$query;
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

        return $response->json();
    }

    /**
     * @param  array<int, mixed>  $array
     * @return Collection<int, object>|null
     */
    private function aviablePerson(array $array): Collection
    {
        $collect = collect();
        foreach ($array as $item) {
            $collect->push((object) $item);
        }

        return $collect;
    }

    private function marriages(Collection $marriages): array
    {
        $array = [];
        foreach ($marriages as $marriage) {
            $array[] = [
                'person' => $marriage,
            ];
        }

        return $array;
    }

    /**
     * @param  array<int, object>  $marriages
     * @param  Collection<int, int>  $marriages
     */
    private function marriagesByPerson(array $marriages): Collection
    {
        $collect = collect();
        foreach ($marriages as $marriage) {
            $collect->push($marriage->soulmate);
        }

        return $collect;
    }
}
