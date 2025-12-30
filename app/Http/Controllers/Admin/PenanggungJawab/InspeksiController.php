<?php

namespace App\Http\Controllers\Admin\PenanggungJawab;

use App\Http\Controllers\Controller;
use App\Models\PenanggungJawab\Inspeksi;
use Illuminate\Http\Request;

class InspeksiController extends Controller
{
    private string $title = 'Inspeksi';
    private string $routeBase = 'admin.penanggung-jawab.inspeksi';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.penanggung-jawab.inspeksi.index')->only('index');
        $this->middleware('can:admin.penanggung-jawab.inspeksi.create')->only('create', 'store');
        $this->middleware('can:admin.penanggung-jawab.inspeksi.edit')->only('edit', 'update');
        $this->middleware('can:admin.penanggung-jawab.inspeksi.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Inspeksi::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->latest()->paginate($request->input('per_page', 10))->appends($request->all());

        return view('admin.penanggung-jawab.resource.index', [
            'title' => $this->title,
            'items' => $items,
            'routeBase' => $this->routeBase,
        ]);
    }

    public function create()
    {
        return view('admin.penanggung-jawab.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'model' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        Inspeksi::create($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' created successfully.');
    }

    public function edit(Inspeksi $inspeksi)
    {
        return view('admin.penanggung-jawab.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'model' => $inspeksi,
        ]);
    }

    public function update(Request $request, Inspeksi $inspeksi)
    {
        $data = $request->validate($this->rules());

        $inspeksi->update($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' updated successfully.');
    }

    public function destroy(Inspeksi $inspeksi)
    {
        $inspeksi->delete();

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'no_mappi' => 'nullable|string|max:255',
            'no_izin_penilai' => 'nullable|string|max:255',
            'no_rmk' => 'nullable|string|max:255',
        ];
    }
}
