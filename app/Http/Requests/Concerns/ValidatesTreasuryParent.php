<?php

namespace App\Http\Requests\Concerns;

use App\Models\Treasury;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

trait ValidatesTreasuryParent
{
    /**
     * @return array<string, array<int, mixed>>
     */
    protected function parentRules(): array
    {
        $rules = [
            'nullable',
            'integer',
            Rule::exists('treasuries', 'id')->whereNull('parent_id'),
        ];

        $treasury = $this->route('treasury');

        if ($treasury instanceof Treasury) {
            $rules[] = Rule::notIn([$treasury->getKey()]);
        }

        return [
            'parent_id' => $rules,
        ];
    }

    protected function rejectInvalidParent(Validator $validator): void
    {
        $parentId = $this->input('parent_id');

        if (! $parentId) {
            return;
        }

        if ($this->boolean('is_master')) {
            $validator->errors()->add('parent_id', __('treasuries.sub_cannot_be_master'));
        }

        $treasury = $this->route('treasury');

        if ($treasury instanceof Treasury && $treasury->children()->exists()) {
            $validator->errors()->add('parent_id', __('treasuries.parent_has_children'));
        }
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'parent_id' => __('treasuries.parent'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'parent_id.exists' => __('treasuries.parent_invalid'),
            'parent_id.not_in' => __('treasuries.parent_invalid'),
        ];
    }
}
