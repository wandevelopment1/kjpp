<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidangUsaha;
use Illuminate\Http\Request;

class BidangUsahaController extends Controller
{
    protected $bidangUsaha;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.bidang-usaha.index')->only('index');
        $this->middleware('can:admin.bidang-usaha.create')->only('create', 'store');
        $this->middleware('can:admin.bidang-usaha.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.bidang-usaha.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = BidangUsaha::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.bidang-usaha.index', compact('items'));
    }

    public function create()
    {
        return view('admin.bidang-usaha.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        BidangUsaha::create($data);

        return redirect()->route('admin.bidang-usaha.index')
            ->with('success', 'BidangUsaha created successfully.');
    }

    public function edit(BidangUsaha $bidangUsaha)
    {
        return view('admin.bidang-usaha.form', compact('bidangUsaha'));
    }

    public function update(Request $request, BidangUsaha $bidangUsaha)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $bidangUsaha->update($data);

        return redirect()->route('admin.bidang-usaha.index')
            ->with('success', 'BidangUsaha updated successfully.');
    }

    public function destroy(BidangUsaha $bidangUsaha)
    {
        $bidangUsaha->delete();

        return redirect()->route('admin.bidang-usaha.index')
            ->with('success', 'BidangUsaha deleted successfully.');
    }
}
