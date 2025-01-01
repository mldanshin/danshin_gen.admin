<?php

namespace Tests\Feature\Http\Controllers\Part;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonCreatedControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('providerCreate200')]
    public function test_create200(array $fakeResponse, callable $assert): void
    {
        Http::fake([
            config('app.api.url').'/api/person/create' => Http::response($fakeResponse, 200),
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
            config('app.api.url')."/api/marriage/roles/{$fakeResponse['gender']}" => Http::response([
                '1' => 'partner23',
                '2' => 'partner994',
            ], 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('part.person.create'));
        $assert($response);
    }

    /**
     * @return mixed[]
     */
    public static function providerCreate200(): array
    {
        return [
            [
                [
                    'isUnavailable' => false,
                    'isLive' => true,
                    'gender' => 1,
                    'hasPatronymic' => false,
                ],
                function ($response) {
                    $response->assertStatus(200);
                    $response->assertSee(__('person.unavailable.label'));
                    $response->assertSee(__('person.live.label'));
                    $response->assertSee(__('person.gender.items.1'));
                    $response->assertSee(route('person.create'));
                    $response->assertSee(route('person.store'));
                },
            ],
            [
                [
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
                    ],
                    'marriages' => [
                        [
                            'role' => 2,
                            'soulmate' => 4,
                            'soulmateRole' => 4,
                        ],
                    ],
                ],
                function ($response) {
                    $response->assertStatus(200);
                    $response->assertSee(route('person.create'));
                    $response->assertSee(route('person.store'));
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
                    $response->assertSee(route('person.create'));
                    $response->assertSee(route('person.store'));
                },
            ],
        ];
    }

    public function test_create401(): void
    {
        $response = $this->withSession()
            ->get(route('part.person.create'));

        $response->assertStatus(401);
    }
}
