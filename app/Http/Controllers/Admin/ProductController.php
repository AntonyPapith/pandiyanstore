<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
        ]);

        $products = Product::with(['category', 'images'])
            ->when($filters['q'] ?? null, fn ($query, $name) => $query->where('name', 'like', '%'.$name.'%'))
            ->when($filters['category_id'] ?? null, fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'filters' => $filters,
        ]);
    }
 
    public function create(): View
    {
        return view('admin.products.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->has('products')) {
            return $this->storeRows($request);
        }

        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'quantity' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cod_available' => ['nullable', 'boolean'],
        ]);

        $imagePath = $request->file('image')->store('products', 'public');
        if (! $imagePath) {
            return back()->withErrors(['image' => 'The product image could not be saved. Please try again.'])->withInput();
        }

        $data['image_path'] = $imagePath;
        $data['cod_available'] = $request->boolean('cod_available');
        unset($data['image']);
        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product->load('images'),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'color' => ['nullable', 'string', 'max:80'],
            'size' => ['nullable', 'string', 'max:80'],
            'price' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cod_available' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $newImagePath = $request->file('image')->store('products', 'public');
            if (! $newImagePath) {
                return back()->withErrors(['image' => 'The product image could not be saved. Please try again.'])->withInput();
            }
            Storage::disk('public')->delete($product->image_path);
            $data['image_path'] = $newImagePath;
        }

        unset($data['image']);
        $data['quantity'] = $data['quantity'] ?? 0;
        $data['price'] = $data['price'] ?? 0;
        $data['discount_price'] = $this->salePrice($data['price'], $data['discount_price'] ?? null);
        $data['cod_available'] = $request->boolean('cod_available');
        $product->update($data);
        $this->storeExtraImages($product, $request->file('images', []));

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        Storage::disk('public')->delete(array_merge([$product->image_path], $product->images()->pluck('image_path')->all()));
        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }

    private function storeRows(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.images' => ['required', 'array', 'min:1', 'max:10'],
            'products.*.images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'products.*.quantity' => ['nullable', 'integer', 'min:0'],
            'products.*.color' => ['nullable', 'string', 'max:80'],
            'products.*.size' => ['nullable', 'string', 'max:80'],
            'products.*.price' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'products.*.discount_price' => ['nullable', 'numeric', 'min:0'],
            'products.*.description' => ['nullable', 'string', 'max:2000'],
            'products.*.cod_available' => ['nullable', 'boolean'],
        ]);

        foreach ($data['products'] as $row) {
            $price = (float) ($row['price'] ?? 0);
            $discount = $row['discount_price'] ?? null;
            if ($discount !== null && (float) $discount > $price) {
                return back()->withErrors(['products' => 'Discount price cannot be greater than the original price.'])->withInput();
            }
        }

        foreach ($data['products'] as $row) {
            $price = (float) ($row['price'] ?? 0);
            $discount = $row['discount_price'] ?? null;
            $files = $row['images'];
            $imagePath = array_shift($files)->store('products', 'public');
            $product = Product::create([
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'image_path' => $imagePath,
                'quantity' => $row['quantity'] ?? 0,
                'color' => $row['color'] ?? null,
                'size' => $row['size'] ?? null,
                'price' => $price,
                'discount_price' => $this->salePrice($price, $discount),
                'description' => $row['description'] ?? null,
                'cod_available' => (bool) ($row['cod_available'] ?? false),
            ]);
            $this->storeExtraImages($product, $files);
        }

        return redirect()->route('admin.products.index')->with('success', count($data['products']).' product(s) created successfully.');
    }

    private function salePrice(float $price, mixed $discount): ?float
    {
        if ($discount === null || $discount === '' || (float) $discount <= 0) {
            return null;
        }

        return max(0, $price - (float) $discount);
    }

    private function storeExtraImages(Product $product, array $files): void
    {
        $nextOrder = (int) $product->images()->max('sort_order') + 1;
        foreach ($files as $file) {
            $path = $file->store('products', 'public');
            if ($path) {
                $product->images()->create(['image_path' => $path, 'sort_order' => $nextOrder++]);
            }
        }
    }
}
