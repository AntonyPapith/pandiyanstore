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

        return view('products-show', ['category' => $category->load('products')]);
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
