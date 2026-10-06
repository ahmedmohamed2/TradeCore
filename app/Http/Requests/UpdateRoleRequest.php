<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use App\Models\Role;
use App\Support\RoleName;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $role = $this->route('role');

        return $this->user()?->can(Permission::UpdateRoles->value) === true
            && $role instanceof Role
            && ! $role->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleId = $this->route('role') instanceof Role ? $this->route('role')->getKey() : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\p{N}\s_-]+$/u',
                Rule::unique('roles', 'name')->where('guard_name', RoleName::Guard)->ignore($roleId),
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (is_string($value) && strcasecmp($value, RoleName::SuperAdmin) === 0) {
                        $fail(__('roles.reserved_name'));
                    }
                },
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(Permission::values())],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => $this->string('name')->trim()->toString(),
            ]);
        }
    }
}
