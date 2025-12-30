<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obyek;
use Illuminate\Http\Request;

class ObyekController extends Controller
{
    protected $obyek;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.obyek.index')->only('index');
        $this->middleware('can:admin.obyek.create')->only('create', 'store');
        $this->middleware('can:admin.obyek.edit')->only('edit', 'update', 'sort');
        $this->middleware('can:admin.obyek.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Obyek::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 10);
        $items = $query->latest()->paginate($perPage)->appends($request->all());

        return view('admin.obyek.index', compact('items'));
    }

    public function create()
    {
        return view('admin.obyek.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Obyek::create($data);

        return redirect()->route('admin.obyek.index')
            ->with('success', 'Obyek created successfully.');
    }

    public function edit(Obyek $obyek)
    {
        return view('admin.obyek.form', compact('obyek'));
    }

    public function update(Request $request, Obyek $obyek)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $obyek->update($data);

        return redirect()->route('admin.obyek.index')
            ->with('success', 'Obyek updated successfully.');
    }

    public function destroy(Obyek $obyek)
    {
        $obyek->delete();

        return redirect()->route('admin.obyek.index')
            ->with('success', 'Obyek deleted successfully.');
    }
}
