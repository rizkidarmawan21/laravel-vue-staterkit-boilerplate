<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRequest;
use App\Models\User;
use App\Services\Users\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(protected UserService $service) {}

    public function index(): Response
    {
        return Inertia::render('users/Index');
    }

    public function getData(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $users = $this->service->getPaginated($perPage);
        return response()->json($users);
    }

    public function create(): Response
    {
        $roles = Role::all();
        return Inertia::render('users/Form', [
            'roles' => $roles,
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->validated());
            return to_route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
        } catch (\App\Exceptions\Users\UserServiceException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(User $user): Response
    {
        $roles = Role::all();
        return Inertia::render('users/Form', [
            'user' => $user->load('roles'),
            'roles' => $roles,
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        try {
            $this->service->update($user, $request->validated());
            return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui!');
        } catch (\App\Exceptions\Users\UserServiceException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->service->delete($user);
            return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus!');
        } catch (\App\Exceptions\Users\UserServiceException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
