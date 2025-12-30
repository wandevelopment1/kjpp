<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisLaporan;
use Illuminate\Http\Request;

class JenisLaporanController extends Controller
{
    protected $jenisLaporan;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.jenis-laporan.index')->only('index');
        $this->middleware('can:admin.jenis-laporan.create')->only('create', 'store');
        $this->middleware('can:admin.jenis-laporan.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.jenis-laporan.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = JenisLaporan::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.jenis-laporan.index', compact('items'));
    }

    public function create()
    {
        return view('admin.jenis-laporan.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        JenisLaporan::create($data);

        return redirect()->route('admin.jenis-laporan.index')
            ->with('success', 'JenisLaporan created successfully.');
    }

    public function edit(JenisLaporan $jenisLaporan)
    {
        return view('admin.jenis-laporan.form', compact('jenisLaporan'));
    }

    public function update(Request $request, JenisLaporan $jenisLaporan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $jenisLaporan->update($data);

        return redirect()->route('admin.jenis-laporan.index')
            ->with('success', 'JenisLaporan updated successfully.');
    }

    public function destroy(JenisLaporan $jenisLaporan)
    {
        $jenisLaporan->delete();

        return redirect()->route('admin.jenis-laporan.index')
            ->with('success', 'JenisLaporan deleted successfully.');
    }
}
