<?php

namespace App\Http\Controllers\Admin\PenggunaLaporan;

use App\Http\Controllers\Controller;
use App\Models\PenggunaLaporan\JenisIndustri;
use Illuminate\Http\Request;

class JenisIndustriController extends Controller
{
    private string $title = 'Jenis Industri';
    private string $routeBase = 'admin.pengguna-laporan.jenis-industri';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.pengguna-laporan.jenis-industri.index')->only('index');
        $this->middleware('can:admin.pengguna-laporan.jenis-industri.create')->only('create', 'store');
        $this->middleware('can:admin.pengguna-laporan.jenis-industri.edit')->only('edit', 'update');
        $this->middleware('can:admin.pengguna-laporan.jenis-industri.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = JenisIndustri::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->latest()->paginate($request->input('per_page', 10))->appends($request->all());

        return view('admin.pengguna-laporan.resource.index', [
            'title' => $this->title,
            'items' => $items,
            'routeBase' => $this->routeBase,
        ]);
    }

    public function create()
    {
        return view('admin.pengguna-laporan.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'model' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        JenisIndustri::create($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' created successfully.');
    }

    public function edit(JenisIndustri $jenisIndustri)
    {
        return view('admin.pengguna-laporan.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'model' => $jenisIndustri,
        ]);
    }

    public function update(Request $request, JenisIndustri $jenisIndustri)
    {
        $data = $request->validate($this->rules());

        $jenisIndustri->update($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' updated successfully.');
    }

    public function destroy(JenisIndustri $jenisIndustri)
    {
        $jenisIndustri->delete();

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
