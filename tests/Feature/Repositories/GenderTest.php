<?php

namespace Tests\Feature\Repositories;

use App\Exceptions\ApiException;
use App\Repositories\Gender as GenderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class GenderTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function testSuccess(): void
    {
        $fakeResponse = [
            "1" => "man",
            "2" => "woman"
        ];

        Http::fake([
            config("app.api.url") . "/api/genders" => Http::response($fakeResponse, 200),
        ]);

        $actual = new GenderRepository()->getAll();

        $this->assertEquals($fakeResponse, $actual->all());
    }

    public function testWrong500(): void
    {
        Http::fake([
            config("app.api.url") . "/api/genders" => Http::response(status: 500),
        ]);

        $this->expectException(ApiException::class);
        $actual = new GenderRepository()->getAll();
    }
}
