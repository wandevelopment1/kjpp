<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MetodePenilaian;
use Illuminate\Http\Request;

class MetodePenilaianController extends Controller
{
    protected $metodePenilaian;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.metode-penilaian.index')->only('index');
        $this->middleware('can:admin.metode-penilaian.create')->only('create', 'store');
        $this->middleware('can:admin.metode-penilaian.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.metode-penilaian.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = MetodePenilaian::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.metode-penilaian.index', compact('items'));
    }

    public function create()
    {
        return view('admin.metode-penilaian.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        MetodePenilaian::create($data);

        return redirect()->route('admin.metode-penilaian.index')
            ->with('success', 'MetodePenilaian created successfully.');
    }

    public function edit(MetodePenilaian $metodePenilaian)
    {
        return view('admin.metode-penilaian.form', compact('metodePenilaian'));
    }

    public function update(Request $request, MetodePenilaian $metodePenilaian)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $metodePenilaian->update($data);

        return redirect()->route('admin.metode-penilaian.index')
            ->with('success', 'MetodePenilaian updated successfully.');
    }

    public function destroy(MetodePenilaian $metodePenilaian)
    {
        $metodePenilaian->delete();

        return redirect()->route('admin.metode-penilaian.index')
            ->with('success', 'MetodePenilaian deleted successfully.');
    }
}
