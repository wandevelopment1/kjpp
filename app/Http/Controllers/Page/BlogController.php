<?php

namespace App\Http\Controllers\Page;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index(Request $request)
    {

        $query = Post::query()->latest()->with(['user', 'tags'])->published();

        // Filter berdasarkan tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

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

        $categories = Category::take(5)->get();
        $tags = Tag::take(10)->get();
        $posts = $query->paginate(1)->withQueryString();
        return view('page.blog.index', compact('posts', 'categories', 'tags'));
    }

    public function show(Post $post)
    {
        $categories = Category::take(5)->get();
        $tags = Tag::take(10)->get();
        return view('page.blog.show', compact('post', 'categories', 'tags'));
    }
}
