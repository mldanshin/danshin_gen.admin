<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('provider200')]
    public function test200(string $query, array $fakeResponse, callable $assert): void
    {
        Http::fake([
            config('app.api.url').'/api/people'.$query => Http::response($fakeResponse, 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('index').$query);
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
                function ($response) {
                    $response->assertStatus(200);

                    $response->assertSee(route('index'));

                    $response->assertSee(__('filter.search.placeholder'));
                    $response->assertSee('fakesearch');
                    $response->assertSee(__('order.types.name.label'));
                    $response->assertSee(__('order.types.age.label'));

                    $response->assertSee('Egorov');
                    $response->assertSee('Ifake1');
                    $response->assertSee('Fake23');
                    $response->assertSee('Ivan');
                    $response->assertSee('Petrovich');
                    $response->assertSee(assert('/person/45/edit'));
                    $response->assertSee(__('people.person.surname_unknown'));
                    $response->assertSee(__('people.person.name_unknown'));
                    $response->assertSee(__('people.person.patronymic_unknown'));
                    $response->assertSee(assert('/person/33/edit'));

                    $response->assertSee(route('person.create'));

                    $response->assertSee(__('layout.autor.role'));
                    $response->assertSee(__('layout.autor.name'));
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
            ->get(route('index').$query);
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
            ->get(route('index'));

        $response->assertStatus(401);
    }

    public function test500(): void
    {
        Http::fake([
            config('app.api.url').'/api/people?order=name' => Http::response(status: 500),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('index'));
        $response->assertStatus(500);
    }
}
