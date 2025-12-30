<?php

namespace App\Http\Controllers\Admin\PenanggungJawab;

use App\Http\Controllers\Controller;
use App\Models\PenanggungJawab\PenanggungPenilai;
use Illuminate\Http\Request;

class PenanggungPenilaiController extends Controller
{
    private string $title = 'Penanggung Jawab Penilai';
    private string $routeBase = 'admin.penanggung-jawab.penanggung-penilai';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.penanggung-jawab.penanggung-penilai.index')->only('index');
        $this->middleware('can:admin.penanggung-jawab.penanggung-penilai.create')->only('create', 'store');
        $this->middleware('can:admin.penanggung-jawab.penanggung-penilai.edit')->only('edit', 'update');
        $this->middleware('can:admin.penanggung-jawab.penanggung-penilai.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = PenanggungPenilai::query();

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

        PenanggungPenilai::create($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' created successfully.');
    }

    public function edit(PenanggungPenilai $penanggungPenilai)
    {
        return view('admin.penanggung-jawab.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'model' => $penanggungPenilai,
        ]);
    }

    public function update(Request $request, PenanggungPenilai $penanggungPenilai)
    {
        $data = $request->validate($this->rules());

        $penanggungPenilai->update($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' updated successfully.');
    }

    public function destroy(PenanggungPenilai $penanggungPenilai)
    {
        $penanggungPenilai->delete();

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
