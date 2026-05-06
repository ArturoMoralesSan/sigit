<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\UserAdminRequest;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Hash;
use Auth;
use Illuminate\Support\Collection;


class UserController extends Controller
{

    public function index()
    {
       abort_unless(Gate::allows('view.users') || Gate::allows('create.users'), 403);

        $users = User::with('role')->get();

        return view('admin.usuarios.index', compact('users'));
    }

    public function create()
    {
        abort_unless(Gate::allows('view.users') || Gate::allows('create.users'), 403);

        $roles = Role::all();

        return view('admin.usuarios.crear', compact('roles'));
    }


    public function save(UserAdminRequest $request)
    {
        abort_unless(Gate::allows('view.users') || Gate::allows('create.users'), 403);

        $password = $request->password;

        $user = User::create([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'password'  => Hash::make($password),
            'role_id'   => $request->roles,
            'is_active' => 1
        ]);

        alert('Se ha agregado un usuario.');

        return response('', 204, [
            'Redirect-To' => url('admin/usuarios/')
        ]);
    }

    public function edit($id)
    {
        abort_unless(Gate::allows('view.users') || Gate::allows('create.users'), 403);

        $user     = User::find($id);
        $roles    = Role::all();
        $userRole = collect([ $user->role_id ]);

        return view('admin.usuarios.editar', compact('roles', 'user', 'userRole'));
    }

    public function update(UserAdminRequest $request, $id)
    {
        abort_unless(Gate::allows('view.users') || Gate::allows('create.users'), 403);

        $user      = User::find($id);
        $is_active = $user->is_active;
        $password  = $user->password;

        if ($request->is_active == 'true') {
            $is_active = 1;
        } elseif ($request->is_active == 'false') {
            $is_active = 0;
        }
        if ($request->password) {
            $password = Hash::make($request->password);
        }

        $user->update([
            'full_name' => $request->full_name,
            'email'     => $request->email,
            'password'  => $password,
            'is_active' => $is_active,
            'role_id'   => $request->roles
        ]);

        alert('Se ha actualizado un usuario.');

        return response('', 204, [
            'Redirect-To' => url('admin/usuarios/')
        ]);
    }

    public function destroy($id)
    {
        abort_unless(Gate::allows('view.users') || Gate::allows('create.users'), 403);

        if (Auth::user()->id !== $id) {
            $user = User::find($id);
            $user->delete();
            alert('Se ha eliminado un usuario.');
        }

        alert('No se ha podido eliminar un usuario.');
        return response('', 204);

    }
}
