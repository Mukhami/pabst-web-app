<html>
<head>
    <title>Matter Request Approved - {{ $matterRequest->ppg_client_matter_no }}</title>
{{--    <link rel="stylesheet" href="{{ asset('backend/assets/css/styles.css') }}">--}}
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .jumbotron {
            background-color: #285f5d;
            color: #fff;
            padding: 2rem 1rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        .jumbotron img {
            max-width: 200px;
            margin-bottom: 1rem;
        }
        .table th, .table td {
            border: 1px solid #dee2e6;
            padding: .75rem;
        }
    </style>
</head>
<body>
<div class="jumbotron">
    <h1 class="text-white">Matter Request Approved</h1>
</div>

<div class="container">
    <h2 class="text-center" style="color: #285f5d">Matter Details</h2>
    <table class="table table-bordered">
        <tr>
            <th>PPG Billing Number</th>
            <td>{{ $matterRequest->ppg_client_matter_no }}</td>
        </tr>
        <tr>
            <th>PPG Reference</th>
            <td>{{ $matterRequest->ppg_ref }}</td>
        </tr>
        <tr>
            <th>Client Reference</th>
            <td>{{ $matterRequest->client_ref }}</td>
        </tr>
        <tr>
            <th>Client Name</th>
            <td>{{ $matterRequest->client_name }}</td>
        </tr>
        <tr>
            <th>Client Main Contact</th>
            <td>{{ $matterRequest->client_main_contact }}</td>
        </tr>
        <tr>
            <th>Client Secondary Contacts</th>
            <td>{{ $matterRequest->client_secondary_contacts }}</td>
        </tr>
        <tr>
            <th>Title of Invention</th>
            <td>{{ $matterRequest->title_of_invention }}</td>
        </tr>
        <tr>
            <th>Entity Size</th>
            <td>{{ $matterRequest->entity_size }}</td>
        </tr>
        <tr>
            <th>Bar Date</th>
            <td>{{ ($matterRequest->bar_date) ? \Carbon\Carbon::parse($matterRequest->bar_date)->format('m-d-Y') : '--' }}</td>
        </tr>
        <tr>
            <th>Goal Date</th>
            <td>{{ ($matterRequest->goal_date) ? \Carbon\Carbon::parse($matterRequest->goal_date)->format('m-d-Y') : '--' }}</td>
        </tr>
        <tr>
            <th>Conversion Date</th>
            <td>{{ ($matterRequest->conversion_date) ? \Carbon\Carbon::parse($matterRequest->conversion_date)->format('m-d-Y') : '--' }}</td>
        </tr>
        <tr>
            <th>Inventors</th>
            <td>{{ $matterRequest->inventors }}</td>
        </tr>
        <tr>
            <th>Responsible Attorney</th>
            <td>{{ ($matterRequest->responsible_attorney) ? $matterRequest->responsible_attorney->name : '--'}}</td>
        </tr>
        <tr>
            <th>Additional Staff</th>
            <td>{{ ($matterRequest->additional_staff) ? $matterRequest->additional_staff->name : '--' }}</td>
        </tr>
        <tr>
            <th>Conducted By</th>
            <td>{{ ($matterRequest->conductor) ? $matterRequest->conductor->name : '--' }}</td>
        </tr>
        <tr>
            <th>Licensees</th>
            <td>{{ $matterRequest->licensees }}</td>
        </tr>
        <tr>
            <th>Assignees</th>
            <td>{{ $matterRequest->assignees }}</td>
        </tr>
        <tr>
            <th>Co-Owners</th>
            <td>{{ $matterRequest->co_owners }}</td>
        </tr>
        <tr>
            <th>Adverse Parties</th>
            <td>{{ $matterRequest->adverse_parties }}</td>
        </tr>
        <tr>
            <th>Renewal Fees Handled Elsewhere</th>
            <td>{{ $matterRequest->renewal_fees_handled_elsewhere ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <th>Other Related Parties</th>
            <td>{{ $matterRequest->other_related_parties }}</td>
        </tr>
        <tr>
            <th>Key Terms for Conflict Search</th>
            <td>{{ $matterRequest->key_terms_for_conflict_search }}</td>
        </tr>
        <tr>
            <th>Conducted By</th>
            <td>{{ ($matterRequest->conductor) ? $matterRequest->conductor->name : '--' }}</td>
        </tr>
        <tr>
            <th>Conducted Date</th>
            <td>{{ ($matterRequest->conducted_date) ? \Carbon\Carbon::parse($matterRequest->conducted_date)->format('m-d-Y') : '--' }}</td>
        </tr>
        <tr>
            <th>Conflict Search Needed Explanation</th>
            <td>{{ $matterRequest->conflict_search_needed_explanation }}</td>
        </tr>
        <tr>
            <th>Related Cases</th>
            <td>{{ $matterRequest->related_cases }}</td>
        </tr>
    </table>

    <h2 class="text-center" style="color: #285f5d">Matter Request Approvals</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Stage</th>
            <th>Status</th>
            <th>Remarks</th>
            <th>Submitted At</th>
        </tr>
        </thead>
        <tbody>
        @foreach($matterRequest->matter_request_approvals as $key => $approval)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $approval->user->name }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $approval->approval_type)) }}</td>
                <td>{{  ucwords(str_replace('_', ' ', $approval->status)) }}</td>
                <td>{{ $approval->remarks }}</td>
                <td>{{ $approval->created_at->format('m-d-Y H:i:s') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
