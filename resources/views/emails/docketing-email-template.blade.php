<x-mail::message>
# Matter Request Approved

**PPG Client Matter Number:** {{ $matterRequest->ppg_client_matter_no }}

**PPG Reference:** {{ $matterRequest->ppg_ref }}

**Client Reference:** {{ $matterRequest->client_ref }}

**Client Name:** {{ $matterRequest->client_name }}

**Client Main Contact:** {{ $matterRequest->client_main_contact }}

**Client Secondary Contacts:** {{ $matterRequest->client_secondary_contacts }}

**Title of Invention:** {{ $matterRequest->title_of_invention }}

**Entity Size:** {{ $matterRequest->entity_size }}

**Bar Date:** {{ $matterRequest->bar_date }}

**Goal Date:** {{ $matterRequest->goal_date }}

**Conversion Date:** {{ $matterRequest->conversion_date }}

**Inventors:** {{ $matterRequest->inventors }}

**Licensees:** {{ $matterRequest->licensees }}

**Assignees:** {{ $matterRequest->assignees }}

**Co-Owners:** {{ $matterRequest->co_owners }}

**Adverse Parties:** {{ $matterRequest->adverse_parties }}

**Renewal Fees Handled Elsewhere:** {{ $matterRequest->renewal_fees_handled_elsewhere ? 'Yes' : 'No' }}

**Other Related Parties:** {{ $matterRequest->other_related_parties }}

**Key Terms for Conflict Search:** {{ $matterRequest->key_terms_for_conflict_search }}

**Conducted By:** {{ $matterRequest->conductor->name }}

**Conducted Date:** {{ $matterRequest->conducted_date }}


**Conflict Search Needed Explanation:** {{ $matterRequest->conflict_search_needed_explanation }}

**Related Cases:** {{ $matterRequest->related_cases }}

Thank you for your attention to this matter.

Best regards,
{{ config('app.name') }}
</x-mail::message>
