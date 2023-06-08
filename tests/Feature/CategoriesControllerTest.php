<?php


use App\Models\Category;
use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoriesControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sanctum::actingAs(
            User::factory()->create()
        );
    }

    public function test_index(): void
    {
        Category::factory(5)->create();

        $response = $this->getJson('/api/categories');

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonCount(6, 'data');
    }

    public function test_create_new(): void
    {
        $data = [
            'name' => $title = 'Hola',
            'slug' => Str::slug($title),
            'description' => Factory::create()->paragraph(),
        ];
        $response = $this->postJson('/api/categories', $data);

        $response->assertSuccessful();
//        $response->assertHeader('content-type', 'application/json');
        $this->assertDatabaseHas('categories', $data);
    }

    public function test_update(): void
    {
        $category = Category::factory()->create();

        $data = [
            'name' => $title = 'Hola',
            'slug' => Str::slug($title),
            'description' => Factory::create()->paragraph(),
        ];

        $response = $this->patchJson("/api/categories/{$category->getKey()}", $data);
        $response->assertSuccessful();
//        $response->assertHeader('content-type', 'application/json');
    }

    public function test_show(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/categories/{$category->getKey()}");

        $response->assertSuccessful();
//        $response->assertHeader('content-type', 'application/json');
    }

    public function test_delete(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->getKey()}");

        $response->assertSuccessful();
//        $response->assertHeader('content-type', 'application/json');
        $this->assertModelMissing($category);
    }

}
