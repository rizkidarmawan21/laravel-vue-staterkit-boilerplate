<?php

namespace App\Http\Requests\Roles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'name')->ignore($this->role),
            ],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['required', 'string', 'exists:permissions,name'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama role wajib diisi',
            'name.max' => 'Nama role tidak boleh lebih dari 100 karakter',
            'name.unique' => 'Nama role sudah digunakan',
            'permissions.required' => 'Permissions wajib diisi',
            'permissions.array' => 'Permissions harus berupa array',
            'permissions.*.required' => 'Permission wajib diisi',
            'permissions.*.string' => 'Permission harus berupa string',
            'permissions.*.exists' => 'Permission tidak ditemukan',
        ];
    }
}
