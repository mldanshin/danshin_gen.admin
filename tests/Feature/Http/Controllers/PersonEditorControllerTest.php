<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonEditorControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('provider200')]
    public function test200(
        string $query,
        array $fakeResponsePeople,
        int $personId,
        array $fakeResponsePerson,
        array $fakeResponseParent,
        array $fakeResponseMarriage,
        callable $assert
    ): void {
        Http::fake([
            config('app.api.url').'/api/people'.$query => Http::response($fakeResponsePeople, 200),
        ]);
        Http::fake([
            config('app.api.url')."/api/person/$personId/edit" => Http::response($fakeResponsePerson, 200),
        ]);
        Http::fake([
            config('app.api.url').'/api/genders' => Http::response([
                '1' => 'men',
                '2' => 'women',
            ], 200),
        ]);
        Http::fake([
            config('app.api.url').'/api/parent/roles' => Http::response([
                '1' => 'mother',
                '2' => 'dad',
            ], 200),
        ]);
        Http::fake([
            config('app.api.url').'/api/parent/possible?person_id=21&birth_date=2000-10-12&marriages%5B0%5D%5Bperson%5D=4&role_id=1' => Http::response($fakeResponseParent, 200),
        ]);
        Http::fake([
            config('app.api.url')."/api/marriage/roles/{$fakeResponsePerson['gender']}" => Http::response([
                '1' => 'partner23',
                '2' => 'partner994',
            ], 200),
        ]);
        Http::fake([
            config('app.api.url').'/api/marriage/possible?person_id=21&birth_date=2000-10-12&parents%5B0%5D%5Bperson%5D=2&role_id=2' => Http::response($fakeResponseMarriage, 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('person.edit', ['person' => $personId]).$query);
        $assert($response);
    }

    /**
     * @return mixed[]
     */
    public static function provider200(): array
    {
        return [
            [
                '?search=fakesearch&order=age',
                [
                    [
                        'id' => 45,
                        'surname' => 'Egorov',
                        'oldSurname' => [
                            'Ifake1',
                            'Fake23',
                        ],
                        'name' => 'Ivan',
                        'patronymic' => 'Petrovich',
                    ],
                    [
                        'id' => 33,
                        'surname' => null,
                        'oldSurname' => null,
                        'name' => null,
                        'patronymic' => null,
                    ],
                ],
                21,
                [
                    'id' => 21,
                    'isUnavailable' => false,
                    'isLive' => true,
                    'gender' => 2,
                    'surname' => 'Ivanov',
                    'oldSurname' => [
                        [
                            'surname' => 'OldIvanov',
                            'order' => 1,
                        ],
                        [
                            'surname' => 'OldPetrov',
                            'order' => 2,
                        ],
                    ],
                    'name' => 'Ivan',
                    'patronymic' => 'Ivanovich',
                    'hasPatronymic' => true,
                    'birthDate' => [
                        'hasUnknown' => false,
                        'isEmpty' => false,
                        'string' => '2000-10-12',
                    ],
                    'birthPlace' => 'Kemerovo',
                    'deathDate' => [
                        'hasUnknown' => false,
                        'isEmpty' => false,
                        'string' => '2022-??-01',
                    ],
                    'burialPlace' => 'Tomsk',
                    'note' => 'Fake note',
                    'activities' => [
                        'driver',
                        'meneger',
                    ],
                    'emails' => [
                        'fake@danshin.net',
                        'fake@fake.fake',
                    ],
                    'internet' => [
                        [
                            'name' => 'site1',
                            'url' => 'http://danshin.net',
                        ],
                    ],
                    'phones' => [
                        '+79999999999',
                        '+78888888888',
                    ],
                    'residences' => [
                        [
                            'name' => 'Beresovo',
                            'date' => [
                                'hasUnknown' => false,
                                'isEmpty' => false,
                                'string' => '2021-01-01',
                            ],
                        ],
                    ],
                    'parents' => [
                        [
                            'person' => 2,
                            'role' => 1,
                        ],
                    ],
                    'marriages' => [
                        [
                            'role' => 2,
                            'soulmate' => 4,
                            'soulmateRole' => 4,
                        ],
                    ],
                ],
                [
                    [
                        'id' => 2,
                        'surname' => 'Egorov',
                        'oldSurname' => [
                            'Danad',
                        ],
                        'name' => 'Igori',
                        'patronymic' => null,
                    ],
                ],
                [
                    'role' => 2,
                    'people' => [
                        [
                            'id' => 2,
                            'surname' => 'Vagen',
                            'oldSurname' => [
                                'Gagarin',
                            ],
                            'name' => 'Maxim',
                            'patronymic' => null,
                        ],
                    ],
                ],
                function ($response) {
                    $response->assertStatus(200);
                    $response->assertSee(route('person.create'));
                    $response->assertSee(route('person.update', ['person' => 21]));
                    $response->assertSee(route('person.destroy', ['person' => 21]));
                    $response->assertSee(__('person.unavailable.label'));
                    $response->assertSee(__('person.live.label'));
                    $response->assertSee(__('person.gender.items.1'));
                    $response->assertSee('Ivanov');
                    $response->assertSee('OldIvanov');
                    $response->assertSee('OldPetrov');
                    $response->assertSee('Ivan');
                    $response->assertSee('Ivanovich');
                    $response->assertSee('2000-10-12');
                    $response->assertSee('Kemerovo');
                    $response->assertSee('2022-??-01');
                    $response->assertSee('Tomsk');
                    $response->assertSee('Fake note');
                    $response->assertSee('driver');
                    $response->assertSee('meneger');
                    $response->assertSee('fake@danshin.net');
                    $response->assertSee('fake@fake.fake');
                    $response->assertSee('site1');
                    $response->assertSee('http://danshin.net');
                    $response->assertSee('+79999999999');
                    $response->assertSee('+78888888888');
                    $response->assertSee('Beresovo');
                    $response->assertSee('2021-01-01');
                    $response->assertSee(__('person.parents.items.1'));
                    $response->assertSee('Egorov');
                    $response->assertSee('Danad');
                    $response->assertSee('Igori');
                    $response->assertSee(__('person.marriages.items.1'));
                    $response->assertSee('Vagen');
                    $response->assertSee('Gagarin');
                    $response->assertSee('Maxim');
                    $response->assertSee(route('person.create'));
                    $response->assertSee(route('person.store'));
                },
            ],
        ];
    }

    #[DataProvider('provider302')]
    public function test302(string $query): void
    {
        Http::fake([
            config('app.api.url').'/api/people'.$query => Http::response(status: 302),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('person.edit', ['person' => 1]).$query);
        $response->assertStatus(302);
    }

    /**
     * @return mixed[]
     */
    public static function provider302(): array
    {
        return [
            ['?order=fake'],
        ];
    }

    public function test401(): void
    {
        $response = $this->withSession()
            ->get(route('person.edit', ['person' => 1]));

        $response->assertStatus(401);
    }

    public function test404(): void
    {
        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('person.edit', ['person' => 'fake']));
        $response->assertStatus(404);
    }

    public function test500(): void
    {
        Http::fake([
            config('app.api.url').'/api/people?order=name' => Http::response(status: 500),
        ]);
        Http::fake([
            config('app.api.url').'/api/person/1/edit' => Http::response(status: 200),
        ]);
        Http::fake([
            config('app.api.url').'/api/genders' => Http::response([
                '1' => 'men',
                '2' => 'women',
            ], 200),
        ]);
        Http::fake([
            config('app.api.url').'/api/parent/roles' => Http::response([
                '1' => 'mother',
                '2' => 'dad',
            ], 200),
        ]);
        Http::fake([
            config('app.api.url').'/api/marriage/roles/1' => Http::response([
                '1' => 'partner23',
                '2' => 'partner994',
            ], 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('person.edit', ['person' => 1]));
        $response->assertStatus(500);
    }
}
