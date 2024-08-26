<?php

namespace App\Http\Requests;

use App\Models\MatterRequest;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreMatterRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): Response
    {
        return Gate::authorize('create', MatterRequest::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ppg_client_matter_no' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9]+\/[a-zA-Z0-9]+$/'
            ],
            'ppg_ref' => 'nullable|string|max:255',
            'client_ref' => 'nullable|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_main_contact' => 'required|string|max:255',
            'client_secondary_contacts' => 'nullable|string|max:255',
            'title_of_invention' => 'required|string|max:255',
            'matter_type_id' => 'required|exists:matter_types,id',
            'sub_type_id' => 'nullable|exists:matter_sub_types,id',
            'bar_date' => 'nullable|date',
            'goal_date' => 'required|date',
            'conversion_date' => 'nullable|date',
            'inventors' => 'nullable|string',
            'licensees' => 'nullable|string',
            'assignees' => 'nullable|string',
            'co_owners' => 'nullable|string',
            'adverse_parties' => 'nullable|string',
            'entity_size' => 'nullable|string|max:255',
            'renewal_fees_handled_elsewhere' => 'nullable:boolean',
            'other_related_parties' => 'nullable|string',
            'key_terms_for_conflict_search' => 'nullable|string',
            'conducted_by' => 'nullable|string|max:255',
            'conducted_date' => 'nullable|date',
            'reviewed_by' => 'nullable|string|max:255',
            'reviewed_date' => 'nullable|date',
            'conflict_search_needed_explanation' => 'nullable|string',
            'related_cases' => 'nullable|string',
            'responsible_attorney_id' => 'required|exists:users,id',
            'additional_staff_id' => 'nullable|exists:users,id',
            'partner_id' => 'nullable|exists:users,id',
            'secondary_partner_id' => 'nullable|exists:users,id',
            'conflict_user_id' => 'nullable|exists:users,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,rtf,zip,png,jpeg,jpg,csv|max:15360', // 15360 KB = 15 MB
        ];
    }
}
