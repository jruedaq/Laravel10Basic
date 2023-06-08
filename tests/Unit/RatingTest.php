<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_product_belongs_to_many_users(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $user->rate($product, 5);

        // dd($user->ratings()->get());
        // dd($product->ratings()->get())

        $this->assertInstanceOf(Collection::class, $user->ratings(Product::class)->get());
        $this->assertInstanceOf(Collection::class, $product->qualifiers(User::class)->get());
    }

    public function test_averageRating(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $product = Product::factory()->create();

        $user->rate($product, 5);
        $user2->rate($product, 10);

        $this->assertEquals(7.5, $product->averageRating(User::class));
    }

    public function test_rating_model(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $user->rate($product, 5);

        $rating = Rating::first();

        $this->assertInstanceOf(Product::class, $rating->rateable);
        $this->assertInstanceOf(User::class, $rating->qualifier);
    }
}
