<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipeProperti;
use Illuminate\Http\Request;

class TipePropertiController extends Controller
{
    protected $tipeProperti;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.tipe-properti.index')->only('index');
        $this->middleware('can:admin.tipe-properti.create')->only('create', 'store');
        $this->middleware('can:admin.tipe-properti.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.tipe-properti.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = TipeProperti::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.tipe-properti.index', compact('items'));
    }

    public function create()
    {
        return view('admin.tipe-properti.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        TipeProperti::create($data);

        return redirect()->route('admin.tipe-properti.index')
            ->with('success', 'TipeProperti created successfully.');
    }

    public function edit(TipeProperti $tipeProperti)
    {
        return view('admin.tipe-properti.form', compact('tipeProperti'));
    }

    public function update(Request $request, TipeProperti $tipeProperti)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tipeProperti->update($data);

        return redirect()->route('admin.tipe-properti.index')
            ->with('success', 'TipeProperti updated successfully.');
    }

    public function destroy(TipeProperti $tipeProperti)
    {
        $tipeProperti->delete();

        return redirect()->route('admin.tipe-properti.index')
            ->with('success', 'TipeProperti deleted successfully.');
    }
}
