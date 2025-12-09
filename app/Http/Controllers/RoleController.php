<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('access_level')->orderBy('id')->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:255', 'unique:roles,name'],
            'access_level' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        Role::create($data);

        return redirect()
            ->route('roles.index')
            ->with('status', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $rules = [
            'access_level' => ['required', 'integer', 'min:0', 'max:100'],
        ];

        // System roles: lock name and access_level to avoid breaking middleware/sidebar
        $isSystem = in_array($role->name, Role::SYSTEM_ROLES, true);

        if (!$isSystem) {
            $rules['name'] = [
                'required', 'string', 'max:255',
                'unique:roles,name,' . $role->id,
            ];
        }

        $data = $request->validate($rules);

        if ($isSystem) {
            unset($data['name'], $data['access_level']); // keep seeded values
        }

        $role->update($data);

        return redirect()
            ->route('roles.index')
            ->with('status', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, Role::SYSTEM_ROLES, true)) {
            return back()->withErrors([
                'delete' => 'System roles cannot be deleted.',
            ]);
        }

        if ($role->users()->exists()) {
            return back()->withErrors([
                'delete' => 'This role is assigned to users and cannot be deleted.',
            ]);
        }

        $role->delete();

        return redirect()
            ->route('roles.index')
            ->with('status', 'Role deleted successfully.');
    }
}
