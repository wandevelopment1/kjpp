<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KepadaKabKota;
use Illuminate\Http\Request;

class KepadaKabKotaController extends Controller
{
    protected $kepadaKabKota;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.kepada-kab-kota.index')->only('index');
        $this->middleware('can:admin.kepada-kab-kota.create')->only('create', 'store');
        $this->middleware('can:admin.kepada-kab-kota.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.kepada-kab-kota.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = KepadaKabKota::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.kepada-kab-kota.index', compact('items'));
    }

    public function create()
    {
        return view('admin.kepada-kab-kota.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        KepadaKabKota::create($data);

        return redirect()->route('admin.kepada-kab-kota.index')
            ->with('success', 'KepadaKabKota created successfully.');
    }

    public function edit(KepadaKabKota $kepadaKabKota)
    {
        return view('admin.kepada-kab-kota.form', compact('kepadaKabKota'));
    }

    public function update(Request $request, KepadaKabKota $kepadaKabKota)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $kepadaKabKota->update($data);

        return redirect()->route('admin.kepada-kab-kota.index')
            ->with('success', 'KepadaKabKota updated successfully.');
    }

    public function destroy(KepadaKabKota $kepadaKabKota)
    {
        $kepadaKabKota->delete();

        return redirect()->route('admin.kepada-kab-kota.index')
            ->with('success', 'KepadaKabKota deleted successfully.');
    }
}
