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
    public function index(): View
    {
        return view('admin.products.index', ['products' => Product::with('category')->latest()->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.products.create', ['categories' => Category::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'quantity' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $imagePath = $request->file('image')->store('products', 'public');
        if (! $imagePath) {
            return back()->withErrors(['image' => 'The product image could not be saved. Please try again.'])->withInput();
        }

        $data['image_path'] = $imagePath;
        unset($data['image']);
        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'quantity' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'description' => ['nullable', 'string', 'max:2000'],
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
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        Storage::disk('public')->delete($product->image_path);
        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }
}
