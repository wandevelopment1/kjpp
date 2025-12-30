<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tujuan;
use Illuminate\Http\Request;

class TujuanController extends Controller
{
    protected $tujuan;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.tujuan.index')->only('index');
        $this->middleware('can:admin.tujuan.create')->only('create', 'store');
        $this->middleware('can:admin.tujuan.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.tujuan.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Tujuan::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.tujuan.index', compact('items'));
    }

    public function create()
    {
        return view('admin.tujuan.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Tujuan::create($data);

        return redirect()->route('admin.tujuan.index')
            ->with('success', 'Tujuan created successfully.');
    }

    public function edit(Tujuan $tujuan)
    {
        return view('admin.tujuan.form', compact('tujuan'));
    }

    public function update(Request $request, Tujuan $tujuan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tujuan->update($data);

        return redirect()->route('admin.tujuan.index')
            ->with('success', 'Tujuan updated successfully.');
    }

    public function destroy(Tujuan $tujuan)
    {
        $tujuan->delete();

        return redirect()->route('admin.tujuan.index')
            ->with('success', 'Tujuan deleted successfully.');
    }
}
