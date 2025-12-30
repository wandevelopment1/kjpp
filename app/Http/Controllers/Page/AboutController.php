<?php

namespace App\Http\Controllers\Page;

use App\Models\History;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function index(){

        $histories = History::orderBy('date')->get();
        return view('page.about.index',compact('histories'));
    }
}
