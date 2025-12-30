<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\PenawaranApprovalMatrix;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.role.index', ['only' => ['index']]);
        $this->middleware('can:admin.role.create', ['only' => ['create', 'store']]);
        $this->middleware('can:admin.role.edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:admin.role.destroy', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Role::query();
        $query->where('id', '!=', 1);

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Tentukan jumlah per halaman
        $perPage = $request->input('per_page', 10);

        $roles = $query->paginate($perPage)->appends($request->all());

        return view('admin.role.index', compact('roles'));
    }


    public function create()
    {
        $permissions = Permission::all();
        $approverCandidates = PenawaranApprovalMatrix::approverCandidates();
        $selectedApprovers = [];

        return view('admin.role.form', compact('permissions', 'approverCandidates', 'selectedApprovers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles',
            'description' => 'required',
            'status' => 'required|boolean',
            'permissions' => 'required|array',
            'approvers' => 'nullable|array',
            'approvers.*' => 'integer',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'guard_name' => 'web',
        ]);

        $role->permissions()->sync($request->permissions);

        $approvers = PenawaranApprovalMatrix::sanitizeApproverIds($request->input('approvers', []));
        PenawaranApprovalMatrix::syncRoleApprovers($role->id, $approvers);

        return redirect()->route('admin.role.index')->with('success', 'Role berhasil dibuat');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $approverCandidates = PenawaranApprovalMatrix::approverCandidates();
        $selectedApprovers = PenawaranApprovalMatrix::roleApproverIds($role->id);

        return view('admin.role.form', compact('role', 'permissions', 'approverCandidates', 'selectedApprovers'));
    }

    public function update(Request $request, $id)
    {
        if ($id == 1) {
            return redirect()->route('admin.role.index')->with('error', 'Role tidak dapat diubah');
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'required|array',
            'status' => 'required|boolean',
            'description' => 'required',
            'approvers' => 'nullable|array',
            'approvers.*' => 'integer',
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        $role->permissions()->sync($request->permissions);

        $approvers = PenawaranApprovalMatrix::sanitizeApproverIds($request->input('approvers', []));
        PenawaranApprovalMatrix::syncRoleApprovers($role->id, $approvers);

        return redirect()->route('admin.role.index')->with('success', 'Role berhasil diubah');
    }

    public function destroy($id)
    {
        if ($id == 1) {
            return redirect()->route('admin.role.index')->with('error', 'Role tidak dapat dihapus');
        }

        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('admin.role.index')->with('success', 'Role berhasil dihapus');
    }


}