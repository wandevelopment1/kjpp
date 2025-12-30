<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeFullCrud extends Command
{
    protected $signature = 'make:fullcrud {name}';
    protected $description = 'Create Controller, Model, Migration, Views, and Route for admin resource';

    public function handle()
    {
        $name = Str::studly($this->argument('name')); // Contoh: IniJudul
        $table = Str::snake(Str::plural($name)); // Contoh: ini_juduls
        $routeName = Str::kebab($name); // Contoh: ini-judul
        $variable = Str::camel($name); // Contoh: iniJudul

        // 1. Model
        $modelPath = app_path("Models/{$name}.php");
        if (!File::exists($modelPath)) {
            File::put($modelPath, "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    use HasFactory;

    protected \$fillable = [];
}
");
            $this->info("Model created: {$modelPath}");
        }

        // 2. Controller
$variable = Str::camel($name); // contoh: iniJudul
$controllerPath = app_path("Http/Controllers/Admin/{$name}Controller.php");

if (!File::exists($controllerPath)) {
    File::put($controllerPath, "<?php

namespace App\Http\\Controllers\\Admin;

use App\\Http\\Controllers\\Controller;
use App\\Models\\{$name};
use App\\Helpers\\UploadManager;
use Illuminate\\Http\\Request;

class {$name}Controller extends Controller
{
    protected \${$variable};

    public function __construct()
    {
        \$this->middleware('auth');
        \$this->middleware('can:admin.{$routeName}.index')->only('index');
        \$this->middleware('can:admin.{$routeName}.create')->only('create', 'store');
        \$this->middleware('can:admin.{$routeName}.edit')->only('edit', 'update', 'sort');
        \$this->middleware('can:admin.{$routeName}.delete')->only('destroy');
    }

    public function index(Request \$request)
    {
        \$query = {$name}::query();

        if (\$request->filled('search')) {
            // tambahkan kondisi pencarian sesuai kebutuhan
            \$query->where('title', 'like', '%' . \$request->search . '%');
        }

        \$perPage = \$request->input('per_page', 10);
        \$items = \$query->latest()->paginate(\$perPage)->appends(\$request->all());

        return view('admin.{$routeName}.index', compact('items'));
    }

    public function create()
    {
        return view('admin.{$routeName}.form');
    }

    public function store(Request \$request)
    {
        \$request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // tambahkan aturan validasi sesuai kebutuhan
        ]);

        \${$variable} = new {$name}();
        \${$variable}->title = \$request->input('title');
        \${$variable}->description = \$request->input('description');

        if (\$request->hasFile('image')) {
            \${$variable}->image = UploadManager::default(\$request->file('image'), '{$routeName}');
        }

        // isi kolom sesuai kebutuhan
        \${$variable}->save();

        return redirect()->route('admin.{$routeName}.index')
            ->with('success', '{$name} created successfully.');
    }

    public function edit({$name} \${$variable})
    {
        return view('admin.{$routeName}.form', compact('{$variable}'));
    }

    public function update(Request \$request, {$name} \${$variable})
    {
        \$request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            // tambahkan aturan validasi sesuai kebutuhan
        ]);

        \${$variable}->title = \$request->input('title');
        \${$variable}->description = \$request->input('description');

        if (\$request->hasFile('image')) {
            if (\${$variable}->image) {
                UploadManager::defaultDelete(\${$variable}->image);
            }
            \${$variable}->image = UploadManager::default(\$request->file('image'), '{$routeName}');
        }

        // isi kolom sesuai kebutuhan
        \${$variable}->save();

        return redirect()->route('admin.{$routeName}.index')
            ->with('success', '{$name} updated successfully.');
    }

    public function destroy({$name} \${$variable})
    {
        if (\${$variable}->image) {
            UploadManager::defaultDelete(\${$variable}->image);
        }
        \${$variable}->delete();

        return redirect()->route('admin.{$routeName}.index')
            ->with('success', '{$name} deleted successfully.');
    }
}
");
    $this->info("Controller created: {$controllerPath}");
}


        // 3. Migration
        $timestamp = date('Y_m_d_His');
        $migrationName = "{$timestamp}_create_{$table}_table.php";
        $migrationPath = database_path("migrations/{$migrationName}");
        File::put($migrationPath, "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$table}', function (Blueprint \$table) {
            \$table->id();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$table}');
    }
};
");
        $this->info("Migration created: {$migrationPath}");

    // 4. Views
$viewFolder = resource_path("views/admin/{$routeName}");
if (!File::exists($viewFolder)) {
    File::makeDirectory($viewFolder, 0755, true);
}

// index.blade.php
$indexView = "{$viewFolder}/index.blade.php";
File::put($indexView, "@extends('layout.admin.layout')

@php
\$model = \$items;
\$routeBase = 'admin.{$routeName}';
\$title = '{$name}';
\$subTitle = '{$name}';
@endphp

@section('title', \$title)

@section('content')
<div class=\"card h-100 p-0 radius-8\">
    <div class=\"card-header border-bottom bg-base py-12 px-16 d-flex align-items-center flex-wrap gap-2 justify-content-between\">
        <h5 class=\"card-title mb-0\">{{ \$subTitle }}</h5>
        <div class=\"d-flex gap-2\">
            @can(\$routeBase . '.create')
            <a href=\"{{ route(\$routeBase . '.create') }}\"
                class=\"btn btn-primary text-xs btn-sm px-8 py-8 radius-6 d-flex align-items-center gap-1\">
                <iconify-icon icon=\"ic:baseline-plus\" class=\"icon-sm line-height-1\"></iconify-icon>
                <span>Create</span>
            </a>
            @endcan
        </div>
    </div>

    <div class=\"card-body p-16\">
        <!-- Filter & Search -->
        <form method=\"GET\" action=\"{{ route(\$routeBase . '.index') }}\"
            class=\"d-flex align-items-center flex-wrap gap-2 mb-16 justify-content-end\">
            <select name=\"per_page\" class=\"form-select form-select-sm w-auto ps-8 py-4 radius-8 h-32-px\"
                onchange=\"this.form.submit()\">
                <option {{ request('per_page')==10 ? 'selected' : '' }}>10</option>
                <option {{ request('per_page')==25 ? 'selected' : '' }}>25</option>
                <option {{ request('per_page')==50 ? 'selected' : '' }}>50</option>
                <option {{ request('per_page')==100 ? 'selected' : '' }}>100</option>
            </select>
            <input type=\"text\" name=\"search\" value=\"{{ request('search') }}\"
                class=\"h-32-px w-auto border border-gray-300 rounded px-2 text-sm\" placeholder=\"Search\">
            <button type=\"submit\"
                class=\"bg-base h-32-px w-32-px d-flex align-items-center justify-content-center radius-8\">
                <iconify-icon icon=\"ion:search-outline\" class=\"icon-sm\"></iconify-icon>
            </button>
        </form>

        <!-- Table -->
        <div class=\"table-responsive scroll-sm\">
            <table class=\"table bordered-table sm-table mb-0 text-sm\">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th class=\"text-center\">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (\$model as \$key => \$item)
                    <tr>
                        <td>{{ (\$model->currentPage() - 1) * \$model->perPage() + \$key + 1 }}</td>
                        <td>
                            <img src=\"{{ asset('storage/' . (\$item->image ?? 'default.png')) }}\"
                                alt=\"{{ \$item->title ?? '-' }}\"
                                style=\"height: 80px;\">
                        </td>
                        <td>{{ Str::limit(\$item->title ?? '-', 50) }}</td>

                        <td class=\"text-center\">
                            <div class=\"d-flex align-items-center gap-8 justify-content-center\">
                                @can(\$routeBase . '.edit')
                                <a href=\"{{ route(\$routeBase . '.edit', \$item->id) }}\"
                                    class=\"bg-success-focus text-success-600 bg-hover-success-200 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle\">
                                    <iconify-icon icon=\"lucide:edit\" class=\"icon-sm\"></iconify-icon>
                                </a>
                                @endcan
                                @can(\$routeBase . '.delete')
                                <form action=\"{{ route(\$routeBase . '.destroy', \$item->id) }}\" method=\"POST\" class=\"d-inline\">
                                    @csrf
                                    @method('DELETE')
                                    <button type=\"submit\"
                                        class=\"bg-danger-focus bg-hover-danger-200 text-danger-600 fw-medium w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border-0\"
                                        onclick=\"deleteData(event, this)\">
                                        <iconify-icon icon=\"fluent:delete-24-regular\" class=\"icon-sm\"></iconify-icon>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class=\"d-flex align-items-center justify-content-between flex-wrap gap-2 mt-16\">
            <span class=\"text-sm\">Showing {{ \$model->count() }} of {{ \$model->total() }} entries</span>
            <x-admin.pagination :paginator=\"\$model\" />
        </div>
    </div>
</div>
@endsection
");

$this->info("View created: {$indexView}");


// form.blade.php
$formView = "{$viewFolder}/form.blade.php";
File::put($formView, "@extends('layout.admin.layout')

@php
\$routeBase = 'admin.{$routeName}';
\$model = isset(\${$variable}) ? \${$variable} : null;

\$title = \$model ? 'Change' : 'Create';
\$subTitle = \$title;
@endphp

@section('title', \$title)

@section('content')
<div class=\"row gy-4\">
    <div class=\"col-lg-12\">
        <div class=\"card mt-24\">
            <div class=\"card-body p-24\">
                <form id=\"form\"
                    action=\"{{ \$model ? route(\$routeBase  . '.update', \$model->id) : route(\$routeBase . '.store') }}\"
                    method=\"POST\" enctype=\"multipart/form-data\" class=\"d-flex flex-column gap-20\">

                    @csrf
                    @if(\$model)
                        @method('PUT')
                    @endif

                    <!-- Example fields kosong -->
                    <x-admin.form-input label=\"Title\" name=\"title\" type=\"text\" 
                        value=\"{{ \$model->title ?? '' }}\" placeholder=\"Enter title\" />

                    <x-admin.form-input label=\"Description\" name=\"description\" type=\"textarea\"
                        value=\"{{ \$model->description ?? '' }}\" placeholder=\"Enter description\" />

                    <x-admin.form-input label=\"Image\" name=\"image\" type=\"file\" 
                        value=\"{{ \$model->image ?? '' }}\" placeholder=\"Upload image\" />

                    <div class=\"d-flex align-items-center justify-content-center gap-3\">
                        <a href=\"{{ route(\$routeBase . '.index') }}\"
                            class=\"border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8\">Back</a>
                        <button id=\"submit\" type=\"submit\"
                            class=\"btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8\">
                            {{ \$title }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
");

$this->info("View created: {$formView}");

        // 5. Route file
$routeFolder = base_path("routes/admin");
if (!File::exists($routeFolder)) {
    File::makeDirectory($routeFolder, 0755, true);
}

$routeFile = "{$routeFolder}/{$routeName}.php";
File::put($routeFile, "<?php

use App\Http\Controllers\Admin\\{$name}Controller;
use Illuminate\Support\Facades\Route;

Route::resource('{$routeName}', {$name}Controller::class);
");
$this->info("Route file created: {$routeFile}");

// 6. Jalankan artisan permission sync
$this->call('permission:sync-from-middleware');

$this->info("✅ Full CRUD skeleton created for {$name} and permissions synced.");
    }
}
