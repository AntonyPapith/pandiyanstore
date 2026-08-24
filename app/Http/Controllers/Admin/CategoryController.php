<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', ['categories' => Category::withCount('products')->latest()->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.categories.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['image_path'] = $request->file('image')->store('categories', 'public');
        $data['sort_order'] = (Category::max('sort_order') ?? 0) + 1;
        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validated($request, false);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('categories', 'public');
            Storage::disk('public')->delete($category->image_path);
        }
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        Storage::disk('public')->delete($category->image_path);
        foreach ($category->products as $product) {
            Storage::disk('public')->delete($product->image_path);
        }
        DB::transaction(function () use ($category): void {
            $category->products()->delete();
            $category->delete();
        });

        return back()->with('success', 'Category deleted successfully.');
    }

    private function validated(Request $request, bool $imageRequired = true): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string', 'max:500'],
            'color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'image' => [Rule::requiredIf($imageRequired), 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }
}
