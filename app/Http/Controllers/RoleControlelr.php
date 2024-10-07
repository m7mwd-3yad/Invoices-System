<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\User;
use Spatie\Permission\Models\Permission;

class RoleControlelr extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('permission.all_permission', compact('permissions'));
    }

    public function create()
    {
        return view('permission.add_permission');
    }

    public function store(Request $request)
    {
        $permission = Permission::create([
            'name' => $request->permission_name,
            'group_name' => $request->group_name,

        ]);

        $notification = [
            'message' => 'تم اضافة الصلاحية بنجاح',
            'alert-type' => 'success'
        ];



        return redirect()->route('permission.index')->with($notification);
    }

    public function edit($id)
    {
        $permission = Permission::find($id);
        return view('permission.edit_permission', compact('permission'));
    }

    public function update($id, Request $request)
    {
        $permission = Permission::find($id);
        $permission->update([
            'name' => $request->permission_name,
            'group_name' => $request->group_name,

        ]);

        $notification = [
            'message' => 'تم تعديل الصلاحية بنجاح',
            'alert-type' => 'success'
        ];
        return redirect()->route('permission.index')->with($notification);
    }

    public function destroy($id)
    {
        $permission = Permission::find($id)->delete();
        $notification = [
            'message' => 'تم حذف الصلاحية بنجاح',
            'alert-type' => 'success'
        ];
        return redirect()->route('permission.index')->with($notification);
    }

    ///////////////////////////////

    public function indexrole()
    {
        $roles = Role::all();
        return view('role.all_role', compact('roles'));
    }

    public function createrole()
    {
        return view('role.add_role');
    }

    public function storerole(Request $request)
    {
        $request->validate([
            'role_name' => 'required|unique:roles,name',
        ]);

        $role = new Role();
        $role->name = $request->role_name;
        $role->save();

        $notificatons = [
            'message' => 'تم إضافة الدور بنجاح',
            'alert-type' => 'success'
        ];
        return redirect()->route('role.index')->with($notificatons);
    }

    public function destroyrole($id)
    {
        $role = Role::find($id)->delete();
        $notification = [
            'message' => 'تم حذف الدور بنجاح',
            'alert-type' => 'success'
        ];
        return redirect()->route('role.index')->with($notification);
    }

    public function editrole($id)
    {
        $role = Role::find($id);
        return view('role.edit_role', compact('role'));
    }

    public function updaterole($id, Request $request)
    {
        $request->validate([
            'role_name' => 'required|unique:roles,name,' . $id,
        ]);
        $role = Role::find($id);
        $role->name = $request->role_name;
        $role->save();
        $notification = [
            'message' => 'تم تعديل الدور بنجاح',
            'alert-type' => 'success'
        ];
        return redirect()->route('role.index')->with($notification);
    }

    ////////////////////////////

    public function AllRolePermission()
    {
        $role = Role::all();
        return view('rolesetup.all_role_permission', compact('role'));
    }

    public function AddRolePermission()
    {
        $role = Role::all();
        $permission = Permission::all();
        $permission_group = \App\Models\User::getPermissionGroups();
        return view('rolesetup.add_role_permission', compact('role', 'permission', 'permission_group'));
    }


    public function StoreRolePermission(Request $request)
    {
        $data = array();
        $permission = $request->permission;

        foreach ($permission as $item) {
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;
            DB::table('role_has_permissions')->insert($data);
        }

        $notification = [
            'message' => 'Role Permission Added Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.role.permission')->with($notification);

    }


    public function DeleteRolePermission($id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->detach();
        $notification = [
            'message' => 'Role Permission Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }

    public function EditRolePermission($id)
    {
        $role = Role::findOrFail($id);
        $permission = Permission::all();
        $permission_group = \App\Models\User::getPermissionGroups();
        return view('rolesetup.edit_role_permission', compact('role', 'permission', 'permission_group'));
    }

    public function UpdateRolePermission(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permission = $request->permission;

        if (!empty($permission)) {
            $validPermissions = Permission::whereIn('id', $permission)->get();
            $role->syncPermissions($validPermissions);
        }


        $notification = [
            'message' => 'Role Permission Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.role.permission')->with($notification);

    }




}
