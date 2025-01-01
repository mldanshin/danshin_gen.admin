<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use App\Exceptions\BadRequestException;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Http;

trait PersonRequest
{
    private function getData(Request $request): array
    {
        return [
            'id' => $request->input('id', null),
            'is_unavailable' => $request->boolean('is_unavailable'),
            'is_live' => $request->boolean('is_live'),
            'gender' => $request->input('gender', null),
            'surname' => $request->input('surname', null),
            'old_surname' => $request->input('old_surname', null),
            'name' => $request->input('name', null),
            'patronymic' => $request->input('patronymic', null),
            'has_patronymic' => $request->boolean('has_patronymic'),
            'birth_date' => $request->input('birth_date', null),
            'birth_place' => $request->input('birth_place', null),
            'death_date' => $request->input('death_date', null),
            'burial_place' => $request->input('burial_place', null),
            'note' => $request->input('note', null),
            'activities' => $request->input('activities', null),
            'emails' => $request->input('emails', null),
            'internet' => $request->input('internet', null),
            'phones' => $request->input('phones', null),
            'residences' => $request->input('residences', null),
            'parents' => $request->input('parents', null),
            'marriages' => $request->input('marriages', null),
            'photo' => $this->storePhotos($request),
        ];
    }

    /**
     * @return mixed[]
     */
    private function storePhotos(Request $request): array
    {
        $array = $request->input('photo', null);
        $arrayPhoto = [];
        if ($array !== null) {
            $files = $request->file('photo');
            foreach ($array as $key => $value) {
                $file = $files[$key]['file'];
                $arrayPhoto[$key] = [
                    'order' => $value['order'],
                    'date' => $value['date'],
                    'file_name' => $this->storePhoto(
                        $file->getPathname(),
                        $file->getClientOriginalName()
                    ),
                ];
            }
        }

        return $arrayPhoto;
    }

    private function storePhoto(string $path, string $fileName): string
    {
        $url = $this->baseUrlApi.'/api/photo/temp';
        $response = Http::asMultipart()
            ->withToken($this->tokenApi)
            ->acceptJson()
            ->attach(
                'photo',
                file_get_contents($path),
                $fileName
            )
            ->post($url);

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

        return $response->json()['fileName'];
    }
}
