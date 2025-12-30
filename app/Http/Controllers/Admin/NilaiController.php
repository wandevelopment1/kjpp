<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    protected $nilai;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.nilai.index')->only('index');
        $this->middleware('can:admin.nilai.create')->only('create', 'store');
        $this->middleware('can:admin.nilai.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.nilai.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Nilai::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.nilai.index', compact('items'));
    }

    public function create()
    {
        return view('admin.nilai.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Nilai::create($data);

        return redirect()->route('admin.nilai.index')
            ->with('success', 'Nilai created successfully.');
    }

    public function edit(Nilai $nilai)
    {
        return view('admin.nilai.form', compact('nilai'));
    }

    public function update(Request $request, Nilai $nilai)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $nilai->update($data);

        return redirect()->route('admin.nilai.index')
            ->with('success', 'Nilai updated successfully.');
    }

    public function destroy(Nilai $nilai)
    {
        $nilai->delete();

        return redirect()->route('admin.nilai.index')
            ->with('success', 'Nilai deleted successfully.');
    }
}
