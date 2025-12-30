<?php

namespace App\Http\Controllers\Admin\PenggunaLaporan;

use App\Http\Controllers\Controller;
use App\Models\PenggunaLaporan\Nama;
use Illuminate\Http\Request;

class NamaController extends Controller
{
    private string $title = 'Nama Pengguna Laporan';
    private string $routeBase = 'admin.pengguna-laporan.nama';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.pengguna-laporan.nama.index')->only('index');
        $this->middleware('can:admin.pengguna-laporan.nama.create')->only('create', 'store');
        $this->middleware('can:admin.pengguna-laporan.nama.edit')->only('edit', 'update');
        $this->middleware('can:admin.pengguna-laporan.nama.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Nama::query();

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

        Nama::create($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' created successfully.');
    }

    public function edit(Nama $nama)
    {
        return view('admin.pengguna-laporan.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'model' => $nama,
        ]);
    }

    public function update(Request $request, Nama $nama)
    {
        $data = $request->validate($this->rules());

        $nama->update($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' updated successfully.');
    }

    public function destroy(Nama $nama)
    {
        $nama->delete();

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
