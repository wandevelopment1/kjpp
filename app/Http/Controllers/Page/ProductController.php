<?php

namespace App\Http\Controllers\Page;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Filter berdasarkan tag
        $query = Product::query();

        // Filter berdasarkan category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%$search%");
            $query->orWhere('description', 'like', "%$search%");
            $query->orWhere('content', 'like', "%$search%");
        }

        $categories = ProductCategory::take(5)->withCount('products')->get();
        $products = $query->paginate(10)->withQueryString();
        return view('page.product.index', compact('categories', 'products'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images']);
        $categories = ProductCategory::take(5)->withCount('products')->get();
        $relatedProducts = Product::query()
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
        return view('page.product.show', compact('product', 'categories', 'relatedProducts'));
    }
}
