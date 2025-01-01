<?php

namespace Tests\Feature\Http\Controllers;

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

    #[DataProvider('provider200')]
    public function test200(
        string $query,
        array $fakeResponsePeople,
        array $fakeResponsePerson,
        callable $assert
    ): void {
        Http::fake([
            config('app.api.url').'/api/people'.$query => Http::response($fakeResponsePeople, 200),
        ]);
        Http::fake([
            config('app.api.url').'/api/person/create' => Http::response($fakeResponsePerson, 200),
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
            ->get(route('person.create').$query);
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
                [
                    'isUnavailable' => false,
                    'isLive' => true,
                    'gender' => 1,
                    'hasPatronymic' => false,
                ],
                function ($response) {
                    $response->assertStatus(200);
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
            ->get(route('person.create').$query);
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
            ->get(route('person.create'));

        $response->assertStatus(401);
    }

    public function test500(): void
    {
        Http::fake([
            config('app.api.url').'/api/people?order=name' => Http::response(status: 500),
        ]);
        Http::fake([
            config('app.api.url').'/api/person/create' => Http::response(status: 200),
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
            ->get(route('person.create'));
        $response->assertStatus(500);
    }
}
