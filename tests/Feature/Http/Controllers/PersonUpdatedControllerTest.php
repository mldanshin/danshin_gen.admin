<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonUpdatedControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('provider200')]
    public function test200(int $personId, callable $fnData): void
    {
        Http::fake([
            config('app.api.url')."/api/person/$personId" => Http::response(['person_id' => $personId], 200),
        ]);
        Http::fake([
            config('app.api.url').'/api/photo/temp' => Http::response(['fileName' => 'fakeTemp.webp'], status: 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->put(route('person.update', ['person' => $personId]), $fnData(Storage::disk('testing')));
        $response->assertStatus(200);
    }

    /**
     * @return mixed[]
     */
    public static function provider200(): array
    {
        return [
            [
                12,
                fn ($disk) => [
                    'id' => 12,
                    'is_unavailable' => false,
                    'is_live' => true,
                    'gender' => 12,
                    'surname' => 'Danshin',
                    'old_surname' => [
                        [
                            'surname' => 'Petrov',
                            'order' => 1,
                        ],
                    ],
                    'name' => 'Maxim',
                    'patronymic' => 'Leonidovich',
                    'has_patronymic' => true,
                    'birth_date' => '1979-11-18',
                    'birth_place' => 'Kemerovo',
                    'death_date' => null,
                    'burial_place' => null,
                    'note' => 'Note',
                    'activities' => [
                        'lawyer',
                        'developer',
                    ],
                    'emails' => [
                        'fake@danshin.net',
                    ],
                    'internet' => [
                        [
                            'name' => 'site',
                            'url' => 'https://danshin.net',
                        ],
                    ],
                    'phones' => [
                        '+79990001122',
                    ],
                    'residences' => [
                        [
                            'name' => 'Kemerovo',
                            'date' => '2025-01-10',
                        ],
                    ],
                    'parents' => [
                        [
                            'person' => 252,
                            'role' => 2,
                        ],
                        [
                            'person' => 255,
                            'role' => 3,
                        ],
                    ],
                    'marriages' => [
                        [
                            'role' => 2,
                            'soulmate' => 151,
                            'soulmate_role' => 3,
                        ],
                    ],
                    'photo' => [
                        'fake345' => [
                            'order' => 1,
                            'date' => '2020-01-01',
                            'file' => new UploadedFile(
                                $disk->path('files/spring.webp'),
                                'spring.webp'
                            ),
                        ],
                    ],
                ],
            ],
            [
                12,
                fn ($disk) => [
                    'is_unavailable' => false,
                    'is_live' => true,
                    'gender' => 1,
                ],
            ],
        ];
    }

    public function test401(): void
    {
        $response = $this->withSession()
            ->put(route('person.update', ['person' => 12]), []);

        $response->assertStatus(401);
    }

    public function test404(): void
    {
        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->put(route('person.update', ['person' => 'fake']));
        $response->assertStatus(404);
    }

    #[DataProvider('provider422')]
    public function test422(int $personId, array $data): void
    {
        Http::fake([
            config('app.api.url')."/api/person/$personId" => Http::response(status: 422),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->put(route('person.update', ['person' => $personId]), $data);
        $response->assertStatus(422);
    }

    /**
     * @return mixed[]
     */
    public static function provider422(): array
    {
        return [
            [
                12,
                [],
            ],
        ];
    }

    public function test500(): void
    {
        Http::fake([
            config('app.api.url').'/api/person/1' => Http::response(status: 500),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->put(route('person.update', ['person' => 1]), []);
        $response->assertStatus(500);
    }
}
