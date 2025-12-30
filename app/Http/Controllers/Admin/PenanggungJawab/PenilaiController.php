<?php

namespace App\Http\Controllers\Admin\PenanggungJawab;

use App\Http\Controllers\Controller;
use App\Models\PenanggungJawab\Penilai;
use Illuminate\Http\Request;

class PenilaiController extends Controller
{
    private string $title = 'Penilai';
    private string $routeBase = 'admin.penanggung-jawab.penilai';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.penanggung-jawab.penilai.index')->only('index');
        $this->middleware('can:admin.penanggung-jawab.penilai.create')->only('create', 'store');
        $this->middleware('can:admin.penanggung-jawab.penilai.edit')->only('edit', 'update');
        $this->middleware('can:admin.penanggung-jawab.penilai.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Penilai::query();

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

        Penilai::create($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' created successfully.');
    }

    public function edit(Penilai $penilai)
    {
        return view('admin.penanggung-jawab.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'model' => $penilai,
        ]);
    }

    public function update(Request $request, Penilai $penilai)
    {
        $data = $request->validate($this->rules());

        $penilai->update($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' updated successfully.');
    }

    public function destroy(Penilai $penilai)
    {
        $penilai->delete();

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
