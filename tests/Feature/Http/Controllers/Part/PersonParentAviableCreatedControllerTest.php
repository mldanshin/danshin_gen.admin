<?php

namespace Tests\Feature\Http\Controllers\Part;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonParentAviableCreatedControllerTest extends TestCase
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
            config('app.api.url').'/api/parent/possible'.$queryApi => Http::response($fakeResponse, 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('part.person.parent_aviable.create').$query);
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
                '?birth_date=2024-01-01&marriages%5B0%5D%5Bperson%5D=1&role_id=1',
                '?role_id=1&temp_id=fake999&birth_date=2024-01-01&marriages[]=1',
                function ($response) {
                    $response->assertStatus(200);
                    $response->assertSee('Egorov');
                    $response->assertSee('Danad');
                    $response->assertSee('Igori');
                    $response->assertSee('fake999');
                },
            ],
            [
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
                '?marriages%5B0%5D%5Bperson%5D=1&role_id=1',
                '?role_id=1&temp_id=fake999&birth_date=ssss&marriages[]=1',
                function ($response) {
                    $response->assertStatus(200);
                    $response->assertSee('Egorov');
                    $response->assertSee('Danad');
                    $response->assertSee('Igori');
                    $response->assertSee('fake999');
                },
            ],
        ];
    }

    public function test_create401(): void
    {
        $response = $this->withSession()
            ->get(route('part.person.parent_aviable.create'));

        $response->assertStatus(401);
    }
}
