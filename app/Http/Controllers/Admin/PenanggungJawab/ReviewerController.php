<?php

namespace App\Http\Controllers\Admin\PenanggungJawab;

use App\Http\Controllers\Controller;
use App\Models\PenanggungJawab\Reviewer;
use Illuminate\Http\Request;

class ReviewerController extends Controller
{
    private string $title = 'Reviewer';
    private string $routeBase = 'admin.penanggung-jawab.reviewers';

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.penanggung-jawab.reviewers.index')->only('index');
        $this->middleware('can:admin.penanggung-jawab.reviewers.create')->only('create', 'store');
        $this->middleware('can:admin.penanggung-jawab.reviewers.edit')->only('edit', 'update');
        $this->middleware('can:admin.penanggung-jawab.reviewers.delete')->only('destroy');
    }

    public function index(Request $request)
    {
        $query = Reviewer::query();

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

        Reviewer::create($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' created successfully.');
    }

    public function edit(Reviewer $reviewer)
    {
        return view('admin.penanggung-jawab.resource.form', [
            'title' => $this->title,
            'routeBase' => $this->routeBase,
            'model' => $reviewer,
        ]);
    }

    public function update(Request $request, Reviewer $reviewer)
    {
        $data = $request->validate($this->rules());

        $reviewer->update($data);

        return redirect()->route($this->routeBase . '.index')
            ->with('success', $this->title . ' updated successfully.');
    }

    public function destroy(Reviewer $reviewer)
    {
        $reviewer->delete();

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
