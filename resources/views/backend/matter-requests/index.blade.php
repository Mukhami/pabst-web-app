@extends('backend.admin.layouts.master')
@section('styles')
    <!-- Datatables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.3.1/jszip-2.5.0/dt-1.11.0/af-2.3.7/b-2.0.0/b-colvis-2.0.0/b-html5-2.0.0/b-print-2.0.0/cr-1.5.4/date-1.1.1/fc-3.3.3/fh-3.1.9/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.0/sp-1.4.0/sl-1.3.3/datatables.min.css"/>
@endsection

@section('content')
    @include('backend.partials.page-header', ['title' => $title, 'sub_title' => $sub_title ?? ''])

    <!-- Main page content-->
    <div class="container-xl px-4 mt-n10">
        <div class="card card-header-actions mx-auto mb-4">
            <div class="card-header">
                {{__('Matter Requests')}}
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
                        <th>PPG Billing No.</th>
                        <th>PPG Ref</th>
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
                        <th>PPG Billing No.</th>
                        <th>PPG Ref</th>
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
                        url: "{{ route('matter-requests.data') }}",
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
