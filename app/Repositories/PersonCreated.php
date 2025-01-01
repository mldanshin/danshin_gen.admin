<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Http;

final class PersonCreated extends Base
{
    public function get(): object
    {
        $url = $this->baseUrlApi . "/api/person/create";
        $response = Http::withToken($this->tokenApi)
            ->acceptJson()
            ->get($url);

        if (!$response->ok()) {
            throw new ApiException(
                "Request for url = $url returned {$response->status()}.",
                $response->status()
            );
        }

        $obj = (object) $response->json();
        $this->oldSurname($obj);
        $this->birthDate($obj);
        $this->deathDate($obj);
        $this->internet($obj);
        $this->residences($obj);
        $this->parents($obj);

        return $obj;
    }

    private function oldSurname(object $obj): void
    {
        if (isset($obj->oldSurname)) {
            $array = [];
            foreach ($obj->oldSurname as $item) {
                $array[] = (object) $item;
            }
            $obj->oldSurname = $array;
        }
    }

    private function birthDate(object $obj): void
    {
        if (isset($obj->birthDate)) {
            $obj->birthDate = (object) $obj->birthDate;
        }
    }

    private function deathDate(object $obj): void
    {
        if (isset($obj->deathDate)) {
            $obj->deathDate = (object) $obj->deathDate;
        }
    }

    private function internet(object $obj): void
    {
        if (isset($obj->internet)) {
            $array = [];
            foreach ($obj->internet as $item) {
                $array[] = (object) $item;
            }
            $obj->internet = $array;
        }
    }

    private function residences(object $obj): void
    {
        if (isset($obj->residences)) {
            $array = [];
            foreach ($obj->residences as $item) {
                if (isset($item["date"])) {
                    $item["date"] = (object) $item["date"];
                }
                $array[] = (object) $item;
            }
            $obj->residences = $array;
        }
    }

    private function parents(object $obj): void
    {
        if (isset($obj->parents)) {
            $array = [];
            foreach ($obj->parents as $item) {
                $array[] = (object) $item;
            }
            $obj->parents = $array;
        }
    }
}
