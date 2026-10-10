<?php

namespace App\Models;

use Database\Factories\TreasuryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treasury extends Model
{
    /** @use HasFactory<TreasuryFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'name',
        'is_master',
        'opening_balance',
        'last_payment_number',
        'last_collection_number',
        'notes',
        'active',
        'created_by',
        'updated_by',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'master_marker',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'is_master' => false,
        'opening_balance' => 0,
        'last_payment_number' => 0,
        'last_collection_number' => 0,
        'active' => true,
    ];

    protected static function booted(): void
    {
        static::saving(function (Treasury $treasury): void {
            $treasury->master_marker = $treasury->is_master ? 1 : null;
        });
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_master' => 'boolean',
            'opening_balance' => 'decimal:2',
            'last_payment_number' => 'integer',
            'last_collection_number' => 'integer',
            'active' => 'boolean',
        ];
    }

    /**
     * @param  Builder<Treasury>  $query
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $like = '%'.addcslashes($term, '%_\\').'%';

        return $query->where(function (Builder $query) use ($like): void {
            $query->whereRaw('code like ? escape ?', [$like, '\\'])
                ->orWhereRaw('name like ? escape ?', [$like, '\\']);
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
