<?php

namespace App\Http\Controllers\Page;

use App\Models\Slider;
use App\Models\Gallery;
use App\Models\Advantage;
use App\Models\GalleryCategory;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    public function index(){
        $categories = GalleryCategory::get();
        $galleries = Gallery::query();

        // Filter by category if provided
        if (request('category')) {
            $galleries->where('gallery_category_id', request('category'));
        }

        $galleries = $galleries->paginate(6)->withQueryString();

        return view('page.gallery.index', compact('categories', 'galleries'));
    }
}
