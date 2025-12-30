<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendekatanPenilaian;
use Illuminate\Http\Request;

class PendekatanPenilaianController extends Controller
{
    protected $pendekatanPenilaian;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.pendekatan-penilaian.index')->only('index');
        $this->middleware('can:admin.pendekatan-penilaian.create')->only('create', 'store');
        $this->middleware('can:admin.pendekatan-penilaian.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.pendekatan-penilaian.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = PendekatanPenilaian::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.pendekatan-penilaian.index', compact('items'));
    }

    public function create()
    {
        return view('admin.pendekatan-penilaian.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        PendekatanPenilaian::create($data);

        return redirect()->route('admin.pendekatan-penilaian.index')
            ->with('success', 'PendekatanPenilaian created successfully.');
    }

    public function edit(PendekatanPenilaian $pendekatanPenilaian)
    {
        return view('admin.pendekatan-penilaian.form', compact('pendekatanPenilaian'));
    }

    public function update(Request $request, PendekatanPenilaian $pendekatanPenilaian)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $pendekatanPenilaian->update($data);

        return redirect()->route('admin.pendekatan-penilaian.index')
            ->with('success', 'PendekatanPenilaian updated successfully.');
    }

    public function destroy(PendekatanPenilaian $pendekatanPenilaian)
    {
        $pendekatanPenilaian->delete();

        return redirect()->route('admin.pendekatan-penilaian.index')
            ->with('success', 'PendekatanPenilaian deleted successfully.');
    }
}
