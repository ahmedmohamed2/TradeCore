<?php

namespace App\Http\Requests;

use App\Enums\Permission;
use App\Http\Requests\Concerns\ValidatesSingleMasterTreasury;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreTreasuryRequest extends FormRequest
{
    use ValidatesSingleMasterTreasury;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can(Permission::CreateTreasuries->value) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:32', 'regex:/^[A-Z0-9_-]+$/', Rule::unique('treasuries', 'code')],
            'name' => ['required', 'string', 'max:255', Rule::unique('treasuries', 'name')],
            'is_master' => ['required', 'boolean'],
            'opening_balance' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999999999.99'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'active' => ['required', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => Str::upper(trim((string) $this->input('code'))),
            'name' => trim((string) $this->input('name')),
            'is_master' => $this->boolean('is_master'),
            'active' => $this->boolean('active'),
        ]);
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $this->rejectDuplicateMaster($validator);
            },
        ];
    }
}
