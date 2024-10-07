<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AddAdminController extends Controller
{
    public function index()
    {
        $admins = User::all();
        return view('admins.all_admin', compact('admins'));
    }
    public function create()
    {
        $roles = Role::all();
        return view('admins.add_admin', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($request->roles) {
            $role = Role::find($request->roles);
            if ($role) {
                $user->assignRole(roles: $role->name);
            }
        }


        $user->save();

        $notifications = [
            'message' => 'تم اضافة المستخدم بنجاح',
            'alert-type' => 'success'
        ];



        return redirect()->route('admin.index')->with($notifications);

    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        $notifications = [
            'message' => 'تم حذف المستخدم بنجاح',
            'alert-type' => 'success'
        ];
        return redirect()->route('admin.index')->with($notifications);

    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('admins.edit_admin', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required|min:8',
        ]);


        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->roles()->detach();
        if ($request->roles) {
            $role = Role::find($request->roles);
            if ($role) {
                $user->syncRoles($role->name);
            }
        }
        $user->save();
        $notifications = [
            'message' => 'تم تعديل المستخدم بنجاح',
            'alert-type' => 'success'
        ];
        return redirect()->route('admin.index')->with($notifications);
    }

}
