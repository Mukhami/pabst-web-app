<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Picklist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'label',
        'description',
        'identifier',
        'default_item',
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'created_at' => 'datetime:Y-m-d h:i:s',
            'updated_at' => 'datetime:Y-m-d h:i:s',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(PicklistItem::class, 'picklist_id');
    }


}
