<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatterRequestApproval extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CHANGES_REQUESTED = 'changes_requested';


    const TYPE_RESPONSIBLE_ATTORNEY = 'responsible_attorney';
    const TYPE_RESPONSIBLE_ATTORNEY_FINAL = 'responsible_attorney_final';
    const TYPE_CONFLICTS_TEAM = 'conflicts_team';
    const TYPE_SECONDARY_PARTNER = 'secondary_partner';
    const TYPE_DOCKETING_TEAM = 'docketing_team';
    protected $fillable = [
        'matter_request_id',
        'user_id',
        'approval_type',
        'status',
        'status',
        'remarks',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime:Y-m-d h:i:s',
            'created_at' => 'datetime:Y-m-d h:i:s',
            'updated_at' => 'datetime:Y-m-d h:i:s',
        ];
    }

    public function matter_request(): BelongsTo
    {
        return $this->belongsTo(MatterRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
