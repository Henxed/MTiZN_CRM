<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    public function index(){
        return Inertia::render('Settings/Departments/Index', [
            'departments' => Department::orderBy('name')->paginate()
        ]);
    }

    public function create(){
        return Inertia::render('Settings/Departments/Create', [
            'permissions' => Permission::get(),
            'users' => User::get()
        ]);
    }

    public function store(){
        Request::validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $dep = Department::firstOrCreate(Request::only(['name','description','owner']));
        $dep->permissions()->sync(Request::get('permissions'));
        $dep->workers()->sync(Request::get('workers'));

        return Redirect::back()->with('success', 'Отдел добавлен!');
    }

    public function edit(Department $department){

        return Inertia::render('Settings/Departments/Edit', [
            'departments' => [
                'id' => $department->id,
                'name' => $department->name,
                'description' => $department->description,
                'owner' => $department->owner,
                'permissions' => $department->permissions->flatten()->pluck('id'),
                'workers' => $department->workers->flatten()->pluck('id')
            ],
            'permissions' => Permission::get(),
            'users' => User::get()
        ]);
    }

    public function update(Department $department){
        Request::validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $department->update(Request::only(['name','description','owner']));
        $department->permissions()->sync(Request::get('permissions'));
        $department->workers()->sync(Request::get('workers'));

        return Redirect::back()->with('success', 'Отдел обнавлен!');
    }
}
