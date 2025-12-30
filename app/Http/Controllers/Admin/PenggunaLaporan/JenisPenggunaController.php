<?php

namespace App\Http\Controllers\Admin\PenggunaLaporan;

use App\Http\Controllers\Controller;
use App\Models\PenggunaLaporan\JenisPengguna;
use Illuminate\Http\Request;

class JenisPenggunaController extends Controller
{
    private string $title = 'Jenis Pengguna';
    private string $routeBase = 'admin.pengguna-laporan.jenis-pengguna';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.pengguna-laporan.jenis-pengguna.index')->only('index');
        $this->middleware('can:admin.pengguna-laporan.jenis-pengguna.create')->only('create', 'store');
        $this->middleware('can:admin.pengguna-laporan.jenis-pengguna.edit')->only('edit', 'update');
        $this->middleware('can:admin.pengguna-laporan.jenis-pengguna.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = JenisPengguna::query();

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

        JenisPengguna::create($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' created successfully.');
    }

    public function edit(JenisPengguna $jenisPengguna)
    {
        return view('admin.pengguna-laporan.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'model' => $jenisPengguna,
        ]);
    }

    public function update(Request $request, JenisPengguna $jenisPengguna)
    {
        $data = $request->validate($this->rules());

        $jenisPengguna->update($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' updated successfully.');
    }

    public function destroy(JenisPengguna $jenisPengguna)
    {
        $jenisPengguna->delete();

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
