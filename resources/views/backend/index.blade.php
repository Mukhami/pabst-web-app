@extends('backend.admin.layouts.master')
@section('styles')
    <!-- Datatables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.3.1/jszip-2.5.0/dt-1.11.0/af-2.3.7/b-2.0.0/b-colvis-2.0.0/b-html5-2.0.0/b-print-2.0.0/cr-1.5.4/date-1.1.1/fc-3.3.3/fh-3.1.9/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.0/sp-1.4.0/sl-1.3.3/datatables.min.css"/>
@endsection

@section('content')
    @include('backend.partials.page-header', ['title' => $title, 'sub_title' => $sub_title ?? ''])

<!-- Main page content-->
<div class="container-xl px-4 mt-n10">\
    <div class="row">
        {{-- Your Pending Approvals --}}
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small">Your Pending Approvals</div>
                            <div class="text-lg fw-bold">{{ $pending_approvals_count }}</div>
                        </div>
                        <i class="feather-xl text-white-50" data-feather="message-circle"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between small">
                    <a class="text-white stretched-link" href="{{ route('matter-requests.index') }}">View</a>
                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        {{-- Matter Requests --}}
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small">Matter Requests</div>
                            <div class="text-lg fw-bold">{{ $matter_requests_count }}</div>
                        </div>
                        <i class="feather-xl text-white-50" data-feather="check-square"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between small">
                    <a class="text-white stretched-link" href="{{ route('matter-requests.index') }}">View</a>
                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        {{-- Registered Users --}}
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75 small">Registered Users</div>
                            <div class="text-lg fw-bold">{{ $users_count }}</div>
                        </div>
                        <i class="feather-xl text-white-50" data-feather="users"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between small">
                    <a class="text-white stretched-link" href="{{ route('users.index') }}">View Users</a>
                    <div class="text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card card-header-actions mx-auto mb-4">
            <div class="card-header">
                {{__('Matter Requests Pending Your Approval')}}
                <div>
                    <a class="btn btn-primary btn-sm mr-2" href="{{ route('matter-requests.create') }}">
                        Create New Matter Request &nbsp; <i data-feather="plus"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="matterTypesDataTable" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Resp. Attorney</th>
                        <th>Conductor</th>
                        <th>Matter No.</th>
                        <th>PPG Docket Ref</th>
                        <th>Client Name</th>
                        <th>Status</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Resp. Attorney</th>
                        <th>Conductor</th>
                        <th>Matter No.</th>
                        <th>PPG Docket Ref</th>
                        <th>Client Name</th>
                        <th>Status</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                    </tfoot>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js" defer></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js" defer></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.3.1/jszip-2.5.0/dt-1.11.0/af-2.3.7/b-2.0.0/b-colvis-2.0.0/b-html5-2.0.0/b-print-2.0.0/cr-1.5.4/date-1.1.1/fc-3.3.3/fh-3.1.9/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.0/sp-1.4.0/sl-1.3.3/datatables.min.js" defer></script>

    <script type="text/javascript">
        $(document).ready(function() {
            const dtTable =
                $('#matterTypesDataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    order: [0, 'DESC'],
                    ajax: {
                        url: "{{ route('matter-requests.users-pending-approval.data') }}",
                        dataType: 'json',
                        type:'GET'
                    },
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'resp_attorney', name: 'users.name' },
                        { data: 'conductor', name: 'users.name' },
                        { data: 'ppg_client_matter_no', name: 'ppg_client_matter_no' },
                        { data: 'ppg_ref', name: 'ppg_ref'},
                        { data: 'client_name', name: 'client_name'},
                        { data: 'status', name: 'status'},
                        { data: 'created_at', name: 'created_at'},
                        { data: 'action', name: 'action', searchable:false, orderable:false }
                    ],
                });
        });
    </script>
@endsection
