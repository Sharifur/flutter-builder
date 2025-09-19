<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('admin')->check() && auth('admin')->user()->is_active;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'password' => 'required|string|min:8|confirmed',
        ];

        // If changing own password, require current password
        if ($this->isChangingOwnPassword()) {
            $rules['current_password'] = 'required|string';
        }

        return $rules;
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Your current password is required.',
            'password.required' => 'A new password is required.',
            'password.min' => 'The new password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->isChangingOwnPassword() && $this->current_password) {
                if (!Hash::check($this->current_password, auth('admin')->user()->password)) {
                    $validator->errors()->add('current_password', 'The current password is incorrect.');
                }
            }
        });
    }

    /**
     * Check if the user is changing their own password.
     */
    protected function isChangingOwnPassword(): bool
    {
        // If this is the profile route, it's always changing own password
        if ($this->route()->getName() === 'admin.profile.update-password') {
            return true;
        }
        
        // For admin/user management routes, check if targeting self
        $targetAdmin = $this->route('admin') ?? $this->route('user');
        return $targetAdmin && $targetAdmin->id === auth('admin')->id();
    }
}
