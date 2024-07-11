<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatterRequest extends Model
{
    use HasFactory, SoftDeletes;

    const ENTITY_SIZES = [
//        'nano',
        'micro',
        'small',
//        'medium',
        'large',
//        'enterprise',
    ];

    protected $fillable = [
        'ppg_client_matter_no',
        'ppg_ref',
        'client_ref',
        'client_name',
        'client_main_contact',
        'client_secondary_contacts',
        'title_of_invention',
//        'matter_type_id',
//        'sub_type_id',
        'entity_size',
        'bar_date',
        'goal_date',
        'conversion_date',
        'inventors',
        'licensees',
        'assignees',
        'co_owners',
        'adverse_parties',
        'entity_size',
        'renewal_fees_handled_elsewhere',
        'other_related_parties',
        'key_terms_for_conflict_search',
        'conducted_by',
        'conducted_date',
        'reviewed_by',
        'reviewed_date',
        'conflict_search_needed_explanation',
        'related_cases',
//        'responsible_attorney_id',
//        'additional_staff_id',
    ];

    protected function casts(): array
    {
        return [
            'renewal_fees_handled_elsewhere' => 'boolean',
            'created_at' => 'date:d-m-Y',
            'updated_at' => 'datetime:d-m-Y h:i:s',
        ];
    }

    public function matter_type(): BelongsTo
    {
        return $this->belongsTo(MatterType::class);
    }

    public function matter_sub_type(): BelongsTo
    {
        return $this->belongsTo(MatterSubType::class, 'sub_type_id');
    }

    public function responsible_attorney(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function additional_staff(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conductor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conducted_by', 'id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id', 'id');
    }

    public function secondary_partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secondary_partner_id', 'id');
    }

    public function docketing_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'docketing_user_id', 'id');
    }

    public function conflict_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conflict_user_id', 'id');
    }

    public function matter_request_approvals(): HasMany
    {
        return $this->hasMany(MatterRequestApproval::class);
    }
}
