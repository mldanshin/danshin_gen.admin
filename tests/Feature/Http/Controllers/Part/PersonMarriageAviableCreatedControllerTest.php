<?php

namespace Tests\Feature\Http\Controllers\Part;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonMarriageAviableCreatedControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    #[DataProvider('providerCreate200')]
    public function test_create200(
        array $fakeResponse,
        string $queryApi,
        string $query,
        callable $assert
    ): void {
        Http::fake([
            config('app.api.url').'/api/marriage/possible'.$queryApi => Http::response($fakeResponse, 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('part.person.marriage_aviable.create').$query);
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
                    'role' => 2,
                    'people' => [
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
                ],
                '?person_id=13&birth_date=2020-10-10&parents%5B0%5D%5Bperson%5D=3&role_id=3',
                '?role_id=3&temp_id=fake999&parents[]=3&birth_date=2020-10-10&person_id=13',
                function ($response) {
                    $response->assertStatus(200);
                    $response->assertSee('Egorov');
                    $response->assertSee('Danad');
                    $response->assertSee('Igori');
                    $response->assertSee('fake999');
                    $response->assertSee(__('person.marriages.items.2'));
                },
            ],
            [
                [
                    'role' => 2,
                    'people' => [
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
                ],
                '?person_id=13&parents%5B0%5D%5Bperson%5D=3&role_id=3',
                '?role_id=3&temp_id=fake999&parents[]=3&birth_date=ssss&person_id=13',
                function ($response) {
                    $response->assertStatus(200);
                    $response->assertSee('Egorov');
                    $response->assertSee('Danad');
                    $response->assertSee('Igori');
                    $response->assertSee('fake999');
                    $response->assertSee(__('person.marriages.items.2'));
                },
            ],
        ];
    }

    public function test_create401(): void
    {
        $response = $this->withSession()
            ->get(route('part.person.marriage_aviable.create'));

        $response->assertStatus(401);
    }
}
