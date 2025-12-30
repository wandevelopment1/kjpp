<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KepadaProvinsi;
use Illuminate\Http\Request;

class KepadaProvinsiController extends Controller
{
    protected $kepadaProvinsi;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.kepada-provinsi.index')->only('index');
        $this->middleware('can:admin.kepada-provinsi.create')->only('create', 'store');
        $this->middleware('can:admin.kepada-provinsi.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.kepada-provinsi.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = KepadaProvinsi::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.kepada-provinsi.index', compact('items'));
    }

    public function create()
    {
        return view('admin.kepada-provinsi.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        KepadaProvinsi::create($data);

        return redirect()->route('admin.kepada-provinsi.index')
            ->with('success', 'KepadaProvinsi created successfully.');
    }

    public function edit(KepadaProvinsi $kepadaProvinsi)
    {
        return view('admin.kepada-provinsi.form', compact('kepadaProvinsi'));
    }

    public function update(Request $request, KepadaProvinsi $kepadaProvinsi)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $kepadaProvinsi->update($data);

        return redirect()->route('admin.kepada-provinsi.index')
            ->with('success', 'KepadaProvinsi updated successfully.');
    }

    public function destroy(KepadaProvinsi $kepadaProvinsi)
    {
        $kepadaProvinsi->delete();

        return redirect()->route('admin.kepada-provinsi.index')
            ->with('success', 'KepadaProvinsi deleted successfully.');
    }
}
