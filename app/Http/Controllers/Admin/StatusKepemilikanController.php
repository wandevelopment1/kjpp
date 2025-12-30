<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatusKepemilikan;
use Illuminate\Http\Request;

class StatusKepemilikanController extends Controller
{
    protected $statusKepemilikan;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.status-kepemilikan.index')->only('index');
        $this->middleware('can:admin.status-kepemilikan.create')->only('create', 'store');
        $this->middleware('can:admin.status-kepemilikan.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.status-kepemilikan.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = StatusKepemilikan::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.status-kepemilikan.index', compact('items'));
    }

    public function create()
    {
        return view('admin.status-kepemilikan.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        StatusKepemilikan::create($data);

        return redirect()->route('admin.status-kepemilikan.index')
            ->with('success', 'StatusKepemilikan created successfully.');
    }

    public function edit(StatusKepemilikan $statusKepemilikan)
    {
        return view('admin.status-kepemilikan.form', compact('statusKepemilikan'));
    }

    public function update(Request $request, StatusKepemilikan $statusKepemilikan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $statusKepemilikan->update($data);

        return redirect()->route('admin.status-kepemilikan.index')
            ->with('success', 'StatusKepemilikan updated successfully.');
    }

    public function destroy(StatusKepemilikan $statusKepemilikan)
    {
        $statusKepemilikan->delete();

        return redirect()->route('admin.status-kepemilikan.index')
            ->with('success', 'StatusKepemilikan deleted successfully.');
    }
}
