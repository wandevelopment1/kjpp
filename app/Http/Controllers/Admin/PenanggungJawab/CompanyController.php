<?php

namespace App\Http\Controllers\Admin\PenanggungJawab;

use App\Http\Controllers\Controller;
use App\Models\PenanggungJawab\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private string $title = 'Nama Perusahaan';
    private string $routeBase = 'admin.penanggung-jawab.companies';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.penanggung-jawab.companies.index')->only('index');
        $this->middleware('can:admin.penanggung-jawab.companies.create')->only('create', 'store');
        $this->middleware('can:admin.penanggung-jawab.companies.edit')->only('edit', 'update');
        $this->middleware('can:admin.penanggung-jawab.companies.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Company::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $items = $query->latest()->paginate($request->input('per_page', 10))->appends($request->all());

        return view('admin.penanggung-jawab.resource.index', [
            'title' => $this->title,
            'items' => $items,
            'routeBase' => $this->routeBase,
            'showActions' => false,
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

        Company::create($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' created successfully.');
    }

    public function edit(Company $company)
    {
        return view('admin.penanggung-jawab.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'model' => $company,
        ]);
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate($this->rules());

        $company->update($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' updated successfully.');
    }

    public function destroy(Company $company)
    {
        $company->delete();

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
