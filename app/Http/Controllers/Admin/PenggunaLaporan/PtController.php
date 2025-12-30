<?php

namespace App\Http\Controllers\Admin\PenggunaLaporan;

use App\Http\Controllers\Controller;
use App\Models\PenggunaLaporan\Pt;
use Illuminate\Http\Request;

class PtController extends Controller
{
    private string $title = 'PT';
    private string $routeBase = 'admin.pengguna-laporan.pts';
    private array $columns = [
        ['label' => 'Nama', 'field' => 'name'],
        ['label' => 'Alamat', 'field' => 'alamat'],
        ['label' => 'Kab./Kota', 'field' => 'kab_kota'],
        ['label' => 'Provinsi', 'field' => 'provinsi'],
        ['label' => 'Kode Pos', 'field' => 'kode_pos'],
    ];
    private array $formFields = [
        ['label' => 'Alamat', 'name' => 'alamat', 'type' => 'textarea', 'placeholder' => 'Masukkan alamat'],
        ['label' => 'Kab./Kota', 'name' => 'kab_kota', 'type' => 'text', 'placeholder' => 'Masukkan kabupaten/kota'],
        ['label' => 'Provinsi', 'name' => 'provinsi', 'type' => 'text', 'placeholder' => 'Masukkan provinsi'],
        ['label' => 'Kode Pos', 'name' => 'kode_pos', 'type' => 'text', 'placeholder' => 'Masukkan kode pos'],
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.pengguna-laporan.pts.index')->only('index');
        $this->middleware('can:admin.pengguna-laporan.pts.create')->only('create', 'store');
        $this->middleware('can:admin.pengguna-laporan.pts.edit')->only('edit', 'update');
        $this->middleware('can:admin.pengguna-laporan.pts.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Pt::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->latest()->paginate($request->input('per_page', 10))->appends($request->all());

        return view('admin.pengguna-laporan.resource.index', [
            'title' => $this->title,
            'items' => $items,
            'routeBase' => $this->routeBase,
            'columns' => $this->columns,
        ]);
    }

    public function create()
    {
        return view('admin.pengguna-laporan.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'model' => null,
            'extraFields' => $this->formFields,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        Pt::create($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' created successfully.');
    }

    public function edit(Pt $pt)
    {
        return view('admin.pengguna-laporan.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'model' => $pt,
            'extraFields' => $this->formFields,
        ]);
    }

    public function update(Request $request, Pt $pt)
    {
        $data = $request->validate($this->rules());

        $pt->update($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' updated successfully.');
    }

    public function destroy(Pt $pt)
    {
        $pt->delete();

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'kab_kota' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:50',
        ];
    }
}
