<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use App\Support\RoleName;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::CreateRoles->value) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\p{N}\s_-]+$/u',
                Rule::unique('roles', 'name')->where('guard_name', RoleName::Guard),
                $this->reservedRoleName(),
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

    private function reservedRoleName(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if (is_string($value) && strcasecmp($value, RoleName::SuperAdmin) === 0) {
                $fail(__('roles.reserved_name'));
            }
        };
    }
}
