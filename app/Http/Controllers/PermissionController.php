<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Redirect;

class PermissionController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/Access/Permissions/Index', [
            'filters' => Request::all('search'),
            'permission' => Permission::orderBy('name')
            ->paginate()
        ]);
    }

    public function create()
    {
        return Inertia::render('Settings/Access/Permissions/Create', [
            'roles' => Role::get()
        ]);
    }

    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:50'],
        ]);

        $permission = Permission::create([
            'name' => Request::get('name')
        ]);

        $permission->syncRoles(Request::only('role'));


        return Redirect::route('permissions.index')->with('success', 'Право доступа создано.');
    }

    public function edit(Permission $permission)
    {

        return Inertia::render('Settings/Access/Permissions/Edit', [
            'permission' => [
                'id' => $permission->id,
                'name' => $permission->name,
                'role' => $permission->roles->flatten()->pluck('id')->all()
            ],
            'roles' => Role::all()
        ]);
    }


    public function update(Permission $permission)
    {
        Request::validate([
            'name' => ['required', 'max:50'],
        ]);

        $permission->update(Request::only('name'));
        $permission->syncRoles(Request::only('role'));

        return Redirect::back()->with('success', 'Право доступа обновлено');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return Redirect::route('permission.index')->with('success', 'Право доступа удалено.');
    }
}
