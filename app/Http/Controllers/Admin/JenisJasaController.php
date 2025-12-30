<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisJasa;
use Illuminate\Http\Request;

class JenisJasaController extends Controller
{
    protected $jenisJasa;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.jenis-jasa.index')->only('index');
        $this->middleware('can:admin.jenis-jasa.create')->only('create', 'store');
        $this->middleware('can:admin.jenis-jasa.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.jenis-jasa.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = JenisJasa::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.jenis-jasa.index', compact('items'));
    }

    public function create()
    {
        return view('admin.jenis-jasa.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        JenisJasa::create($data);

        return redirect()->route('admin.jenis-jasa.index')
            ->with('success', 'JenisJasa created successfully.');
    }

    public function edit(JenisJasa $jenisJasa)
    {
        return view('admin.jenis-jasa.form', compact('jenisJasa'));
    }

    public function update(Request $request, JenisJasa $jenisJasa)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $jenisJasa->update($data);

        return redirect()->route('admin.jenis-jasa.index')
            ->with('success', 'JenisJasa updated successfully.');
    }

    public function destroy(JenisJasa $jenisJasa)
    {
        $jenisJasa->delete();

        return redirect()->route('admin.jenis-jasa.index')
            ->with('success', 'JenisJasa deleted successfully.');
    }
}
