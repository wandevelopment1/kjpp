<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kepemilikan;
use Illuminate\Http\Request;

class KepemilikanController extends Controller
{
    protected $kepemilikan;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.kepemilikan.index')->only('index');
        $this->middleware('can:admin.kepemilikan.create')->only('create', 'store');
        $this->middleware('can:admin.kepemilikan.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.kepemilikan.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Kepemilikan::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.kepemilikan.index', compact('items'));
    }

    public function create()
    {
        return view('admin.kepemilikan.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Kepemilikan::create($data);

        return redirect()->route('admin.kepemilikan.index')
            ->with('success', 'Kepemilikan created successfully.');
    }

    public function edit(Kepemilikan $kepemilikan)
    {
        return view('admin.kepemilikan.form', compact('kepemilikan'));
    }

    public function update(Request $request, Kepemilikan $kepemilikan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $kepemilikan->update($data);

        return redirect()->route('admin.kepemilikan.index')
            ->with('success', 'Kepemilikan updated successfully.');
    }

    public function destroy(Kepemilikan $kepemilikan)
    {
        $kepemilikan->delete();

        return redirect()->route('admin.kepemilikan.index')
            ->with('success', 'Kepemilikan deleted successfully.');
    }
}
