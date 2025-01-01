<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonStoredControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('provider200')]
    public function test200(callable $fnData): void
    {
        Http::fake([
            config('app.api.url').'/api/person' => Http::response(['person_id' => 132], 200),
        ]);
        Http::fake([
            config('app.api.url').'/api/photo/temp' => Http::response(['fileName' => 'fakeTemp.webp'], status: 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->post(route('person.store'), $fnData(Storage::disk('testing')));
        $response->assertStatus(200);
    }

    /**
     * @return mixed[]
     */
    public static function provider200(): array
    {
        return [
            [
                fn ($disk) => [
                    'is_unavailable' => false,
                    'is_live' => true,
                    'gender' => 1,
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
                        'fake977' => [
                            'name' => 'site',
                            'url' => 'https://danshin.net',
                        ],
                    ],
                    'phones' => [
                        '+79990001122',
                    ],
                    'residences' => [
                        'fake3435' => [
                            'name' => 'Kemerovo',
                            'date' => '2025-01-10',
                        ],
                    ],
                    'parents' => [
                        'fake977' => [
                            'person' => 252,
                            'role' => 2,
                        ],
                        'fake345' => [
                            'person' => 255,
                            'role' => 3,
                        ],
                    ],
                    'marriages' => [
                        'fake875' => [
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
            ->post(route('person.store'), []);

        $response->assertStatus(401);
    }

    #[DataProvider('provider422')]
    public function test422(array $data): void
    {
        Http::fake([
            config('app.api.url').'/api/person' => Http::response(status: 422),
        ]);
        Http::fake([
            config('app.api.url').'/api/photo/temp' => Http::response(status: 422),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->post(route('person.store'), $data);
        $response->assertStatus(422);
    }

    /**
     * @return mixed[]
     */
    public static function provider422(): array
    {
        return [
            [
                [],
            ],
        ];
    }

    public function test500(): void
    {
        Http::fake([
            config('app.api.url').'/api/person' => Http::response(status: 500),
        ]);
        Http::fake([
            config('app.api.url').'/api/photo/temp' => Http::response(status: 500),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->post(route('person.store'), []);
        $response->assertStatus(500);
    }
}
