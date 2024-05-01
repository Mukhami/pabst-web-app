<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PicklistItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'label',
        'description',
        'identifier',
        'sequence',
        'is_system',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'created_at' => 'datetime:Y-m-d h:i:s',
            'updated_at' => 'datetime:Y-m-d h:i:s',
        ];
    }

    public function picklist(): BelongsTo
    {
        return $this->belongsTo(Picklist::class);
    }
}
