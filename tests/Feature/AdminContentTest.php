<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_require_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_login_and_create_a_category(): void
    {
        Storage::fake('public');
        User::factory()->create(['email' => 'admin@gmail.com', 'password' => '12345678', 'is_admin' => true]);

        $this->post('/admin/login', ['email' => 'admin@gmail.com', 'password' => '12345678'])
            ->assertRedirect('/admin');

        $this->post('/admin/categories', [
            'name' => 'Toys',
            'description' => 'Fun toys for children.',
            'color' => '#FF69B4',
            'image' => UploadedFile::fake()->image('toys.jpg'),
        ])->assertRedirect('/admin/categories');

        $category = Category::firstOrFail();
        Storage::disk('public')->assertExists($category->image_path);
        $this->get('/')->assertOk()->assertSee('Toys');
    }

    public function test_admin_can_create_and_display_a_product(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create([
            'name' => 'Glass Toys',
            'description' => null,
            'color' => '#08033D',
            'image_path' => 'categories/glass-toys.jpg',
        ]);

        $this->actingAs($admin)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'Glass Doll',
            'image' => UploadedFile::fake()->image('glass-doll.jpg'),
            'quantity' => 5,
            'price' => 1500,
            'discount_price' => 1299,
            'description' => '',
        ])->assertRedirect('/admin/products');

        $product = Product::firstOrFail();
        Storage::disk('public')->assertExists($product->image_path);
        $this->actingAs($admin)->get('/admin/products')->assertOk()->assertSee('Glass Doll');
        $this->get(route('categories.products', $category))->assertOk()->assertSee('Glass Doll')->assertSee('1,299.00');
    }
}
