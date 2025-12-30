<?php

namespace App\Http\Controllers\Page;

use App\Models\Post;
use App\Models\Slider;
use App\Models\Product;
use App\Models\Partnership;
use App\Models\Testimonial;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(){
        $sliders = Slider::orderBy('order')->get();
        $partnerships = Partnership::get();
        $testimonials = Testimonial::get();
        $products = Product::latest()->take(15)->get();
        $blogs = Post::query()->latest()->with('user')->where('published_at', '<=', now())->orWhereNull('published_at')->where('status', 'published')->take(3)->get();
        return view('page.home.index', compact('sliders', 'partnerships', 'testimonials', 'products', 'blogs'));
    }
}
