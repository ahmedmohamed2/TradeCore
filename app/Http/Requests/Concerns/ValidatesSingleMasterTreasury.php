<?php

namespace App\Http\Requests\Concerns;

use App\Models\Treasury;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Validator;

trait ValidatesSingleMasterTreasury
{
    protected function rejectDuplicateMaster(Validator $validator): void
    {
        if (! $this->boolean('is_master')) {
            return;
        }

        $treasury = $this->route('treasury');

        $exists = Treasury::query()
            ->where('is_master', true)
            ->when(
                $treasury instanceof Treasury,
                fn (Builder $query): Builder => $query->whereKeyNot($treasury->getKey()),
            )
            ->exists();

        if ($exists) {
            $validator->errors()->add('is_master', __('treasuries.master_exists'));
        }
    }
}
