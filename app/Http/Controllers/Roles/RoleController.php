<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\CreateRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Services\Roles\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(protected RoleService $service) {}

    public function index(): Response
    {
        return Inertia::render('roles/Index');
    }

    public function getData(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $roles = $this->service->getAll($perPage, true);
        return response()->json($roles);
    }

    public function create(): Response
    {
        $permissions = Permission::all();
        return Inertia::render('roles/Form', [
            'permissions' => $permissions,
        ]);
    }

    public function store(CreateRoleRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->validated());
            return to_route('roles.index')->with('success', 'Role berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(Role $role): Response
    {
        $permissions = Permission::all();
        return Inertia::render('roles/Form', [
            'role' => $role->load('permissions'),
            'permissions' => $permissions,
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        try {
            $this->service->update($role, $request->validated());
            return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Role $role): RedirectResponse
    {
        try {
            $this->service->delete($role);
            return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
