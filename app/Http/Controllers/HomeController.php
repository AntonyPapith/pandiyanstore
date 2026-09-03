<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', ['categories' => Category::orderBy('sort_order')->latest()->get()]);
    }

    public function products(Request $request, Category $category): View
    {
        $request->session()->put('continue_shopping_url', route('categories.products', $category));
        $products = $category->products()->with('images')->orderBy('id')->get()
            ->groupBy(fn (Product $product) => mb_strtolower($product->name))
            ->map(function ($variants): Product {
           $product = $variants->firstWhere('quantity', '>', 0) ?? $variants->first();
                $product->variant_stock = $variants->sum('quantity');

                return $product;
            })->values();

        return view('products-show', compact('category', 'products'));
    }

    public function product(Product $product): View
    {
        $variants = Product::with('images')->where('category_id', $product->category_id)
            ->where('name', $product->name)
            ->orderBy('id')->get();

        return view('product-detail', [
            'product' => $product->load('category'),
            'variants' => $variants,
        ]);
    }

    public function search(Request $request): View|RedirectResponse
    {
        $query = trim((string) $request->query('q'));
        if ($query === '') {
            return redirect()->route('home');
        }

        $products = Product::with('category')
            ->where(function ($builder) use ($query): void {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$query}%"));
            })
            ->orderByRaw('CASE WHEN LOWER(name) = LOWER(?) THEN 0 WHEN LOWER(name) LIKE LOWER(?) THEN 1 ELSE 2 END', [$query, $query.'%'])
            ->orderBy('name')
            ->paginate(24)
            ->withQueryString();

        return view('search-results', compact('products', 'query'));
    }
}
