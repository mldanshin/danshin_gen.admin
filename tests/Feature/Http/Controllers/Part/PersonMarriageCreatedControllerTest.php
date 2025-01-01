<?php

namespace Tests\Feature\Http\Controllers\Part;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonMarriageCreatedControllerTest extends TestCase
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
        string $query,
        int $genderId,
        callable $assert
    ): void {
        Http::fake([
            config('app.api.url')."/api/marriage/roles/$genderId" => Http::response($fakeResponse, 200),
        ]);

        $response = $this->actingAs($this->getAdmim())
            ->withSession()
            ->get(route('part.person.marriage.create').$query);
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
                    1 => 'Partner',
                    2 => 'Mug',
                    3 => 'Gena',
                ],
                '?role_id=1&gender_id=1',
                1,
                function ($response) {
                    $response->assertStatus(200);
                    $response->assertSee(__('person.marriages.items.1'));
                    $response->assertSee(__('person.marriages.items.2'));
                },
            ],
        ];
    }

    public function test_create401(): void
    {
        $response = $this->withSession()
            ->get(route('part.person.marriage.create'));

        $response->assertStatus(401);
    }
}
