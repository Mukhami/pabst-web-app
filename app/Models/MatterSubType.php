<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatterSubType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'key',
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

    public function matter_type(): BelongsTo
    {
        return $this->belongsTo(MatterType::class);
    }
}
