<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.user.index', ['only' => ['index']]);
        $this->middleware('can:admin.user.create', ['only' => ['create']]);
        $this->middleware('can:admin.user.edit', ['only' => ['edit','syncRole']]);
        $this->middleware('can:admin.user.delete', ['only' => ['destroy']]);
    }       

    public function index(Request $request)
    {
        $roles= role::get();
        $query = User::query();

        // Filter berdasarkan pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Tentukan jumlah per halaman
        $perPage = $request->input('per_page', 10);

        $users = $query->paginate($perPage)->appends($request->all());

        return view('admin.user.index', compact('users', 'roles'));
    }

    public function create()
    {
        return view('admin.user.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('users', 'name')],
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nik' => $request->nik,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat, 
            'bank' => $request->bank,
            'rekening' => $request->rekening,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil dibuat');
    }   

    public function edit($id)
    {
        $users = User::findOrFail($id);
        return view('admin.user.form', compact('users'));
    }

    public function update(Request $request, $id)
    {
        if ($id == 1) {
            if ($id == Auth::user()->id) {
                $request->validate([
                    'name' => ['required', 'string', Rule::unique('users', 'name')->ignore($id)],
                    'email' => 'required|email|unique:users,email,' . $id,
                    'password' => 'nullable|min:8',
                ]);


                $user = User::findOrFail($id);
                if ($request->password == null) {
                    $request->request->remove('password');
                } else {
                    $request->request->add(['password' => Hash::make($request->password)]);
                }

                $user->update($request->all());
            } else {
                return redirect()->route('admin.user.index')->with('error', 'User tidak dapat diubah');
            }
        }

        $request->validate([
            'name' => ['required', 'string', Rule::unique('users', 'name')->ignore($id)],
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:8',
        ]);

        $user = User::findOrFail($id);

        if($request->password == null){
            $request->request->remove('password');
        }else{
            $request->request->add(['password' => Hash::make($request->password)]);
        }

        $user->update($request->all());
        
        return redirect()->route('admin.user.index')->with('success', 'User berhasil diubah');
    }

    public function destroy($id)
    {
        if($id == 1){
            return redirect()->route('admin.user.index')->with('error', 'User tidak dapat dihapus');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus');
    }

    public function syncRole(Request $request, $id)
    {
        $request->validate([
            'roles' => 'nullable|array',
        ]);
        if($id == 1){
            return redirect()->route('admin.user.index')->with('error', 'User tidak dapat diubah');
        }

        $roleNames = array_filter($request->roles); // hilangkan '', null
        $user = User::findOrFail($id);
        if($request->roles == null){    
            $user->syncRoles([]);
        }else{
            $user->syncRoles($roleNames);
        }

        return redirect()->route('admin.user.index')->with('success', 'Role User berhasil diubah');
    }
}
