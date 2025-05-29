<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user)],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,name'],
            'is_active' => ['boolean'],
        ];

        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', 'string', 'min:8'];
        }

        if ($this->isMethod('PUT') && $this->password) {
            $rules['password'] = ['string', 'min:8'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter',
            'address.required' => 'Alamat wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'roles.required' => 'Role wajib dipilih',
            'roles.array' => 'Format role tidak valid',
            'roles.*.exists' => 'Role yang dipilih tidak valid',
        ];
    }
}
