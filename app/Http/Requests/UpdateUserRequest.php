<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use App\Http\Requests\Concerns\ValidatesRoleAssignment;
use App\Models\User;
use App\Support\RoleName;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class UpdateUserRequest extends FormRequest
{
    use ValidatesRoleAssignment;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $actor = $this->user();
        $subject = $this->route('user');

        if ($actor === null || ! $actor->can(Permission::UpdateUsers->value)) {
            return false;
        }

        return ! ($subject instanceof User
            && $subject->hasRole(RoleName::SuperAdmin)
            && ! $actor->hasRole(RoleName::SuperAdmin));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user') instanceof User ? $this->route('user')->getKey() : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', Password::default(), 'confirmed'],
            'locale' => ['required', 'string', Rule::in(config('locale.available'))],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', Rule::exists('roles', 'name')->where('guard_name', RoleName::Guard)],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('password') === '') {
            $this->merge([
                'password' => null,
                'password_confirmation' => null,
            ]);
        }
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $subject = $this->route('user');

                $this->guardRoleAssignment($validator, $subject instanceof User ? $subject : null);
            },
        ];
    }
}
