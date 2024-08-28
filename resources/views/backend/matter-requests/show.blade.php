@extends('backend.admin.layouts.master')

@section('styles')
    <!-- Datatables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.3.1/jszip-2.5.0/dt-1.11.0/af-2.3.7/b-2.0.0/b-colvis-2.0.0/b-html5-2.0.0/b-print-2.0.0/cr-1.5.4/date-1.1.1/fc-3.3.3/fh-3.1.9/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.0/sp-1.4.0/sl-1.3.3/datatables.min.css"/>
@endsection

@section('content')
    @include('backend.partials.page-header', ['title' => $title, 'sub_title' => $sub_title ?? ''])
    <div class="container-xl px-4 mt-n10">

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="small text-muted mb-2">{{__('Responsible Attorney')}}:</div>
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-lg">
                                <img class="avatar-img img-fluid" src="https://eu.ui-avatars.com/api/?name={{urlencode($matterRequest->responsible_attorney->name)}}" alt="" />
                            </div>
                            <div class="ms-3">
                                <div class="fs-4 text-dark fw-500">{{ $matterRequest->responsible_attorney->name }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="small text-muted mb-2">{{__('Additional Staff Member')}}:</div>
                        @if($matterRequest->additional_staff)
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-lg">
                                    <img class="avatar-img img-fluid" src="https://eu.ui-avatars.com/api/?name={{urlencode($matterRequest->additional_staff->name)}}" alt="" />
                                </div>
                                <div class="ms-3">
                                    <div class="fs-4 text-dark fw-500">{{ $matterRequest->additional_staff->name}}</div>
                                </div>
                            </div>
                        @else
                            <div class="ms-3">
                                <div class="fs-4 text-danger fw-500">{{ __('Additional Staff not Assigned') }}</div>
                            </div>
                        @endif


                    </div>

                    <div class="col-lg-4">
                        <div class="small text-muted mb-2">{{__('Conflict User')}}:</div>
                        @if($matterRequest->conflict_user)
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-lg">
                                    <img class="avatar-img img-fluid" src="https://eu.ui-avatars.com/api/?name={{urlencode($matterRequest->conflict_user->name)}}" alt="" />
                                </div>
                                <div class="ms-3">
                                    <div class="fs-4 text-dark fw-500">{{ $matterRequest->conflict_user->name }}</div>
                                </div>
                            </div>
                        @else
                            <div class="ms-3">
                                <div class="fs-4 text-danger fw-500">{{ __('Conflict User not Assigned') }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-lg-4 ">
                        <div class="small text-muted mb-2">{{__('Secondary Partner')}}:</div>
                        @if($matterRequest->partner)
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-lg">
                                    <img class="avatar-img img-fluid" src="https://eu.ui-avatars.com/api/?name={{urlencode($matterRequest->partner->name)}}" alt="" />
                                </div>
                                <div class="ms-3">
                                    <div class="fs-4 text-dark fw-500">{{ $matterRequest->partner->name }}</div>
                                </div>
                            </div>
                        @else
                            <div class="ms-3">
                                <div class="fs-4 text-danger fw-500">{{ __('Partner not Assigned') }}</div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <div class="card card-header-actions mx-auto mb-4">
            <div class="card-header">
                {{__('Matter Type Details')}}
                <div>
                    <a class="btn btn-primary btn-sm mr-2" href="{{ route('matter-requests.downloadPDF', $matterRequest) }}">
                        View Matter Request Document &nbsp; <i data-feather="eye"></i>
                    </a>

                    <a class="btn btn-primary btn-sm mr-2" href="{{ route('matter-requests.edit', $matterRequest) }}">
                        Edit Matter Request Details &nbsp; <i data-feather="edit"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row gx-3 mb-3">
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('PPG Billing Number')}}</p>
                        <p class=" mb-1">{{ $matterRequest->ppg_client_matter_no }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('PPG Ref')}}</p>
                        <p class=" mb-1">{{ $matterRequest->ppg_ref }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Client Ref')}}</p>
                        <p class=" mb-1">{{ $matterRequest->client_ref }}</p>
                    </div>
                </div>
                <hr>

                <div class="row gx-3 mb-3">
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Client Name')}}</p>
                        <p class=" mb-1">{{ $matterRequest->client_name }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Client Main Contact')}}</p>
                        <p class=" mb-1">{{ $matterRequest->client_main_contact }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Client Secondary Contact')}}</p>
                        <p class=" mb-1">{{ $matterRequest->client_secondary_contacts }}</p>
                    </div>
                </div>
                <hr>
                <div class="row gx-3 mb-3">
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Title of Invention')}}</p>
                        <p class=" mb-1">{{ $matterRequest->title_of_invention }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Matter Type')}}</p>
                        <p class=" mb-1">{{ $matterRequest->matter_type->name }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Matter Sub Type')}}</p>
                        <p class=" mb-1">{{ ($matterRequest->matter_sub_type) ? $matterRequest->matter_sub_type->name : 'N/A' }}</p>
                    </div>
                </div>

                <hr>

                <div class="row gx-3 mb-3 ">
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Bar Date')}}</p>
                        <p class=" mb-1">{{ ($matterRequest->bar_date) ? \Carbon\Carbon::parse($matterRequest->bar_date)->format('d-m-Y') : '--' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Goal Date')}}</p>
                        <p class=" mb-1">{{ ($matterRequest->goal_date) ? \Carbon\Carbon::parse($matterRequest->goal_date)->format('d-m-Y') : '--' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Conversion Date')}}</p>
                        <p class=" mb-1">{{ ($matterRequest->conversion_date) ? \Carbon\Carbon::parse($matterRequest->conversion_date)->format('d-m-Y') : '--' }}</p>
                    </div>
                </div>
                <hr>

                <div class="row gx-3 mb-3">
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Inventors')}}</p>
                        <p class=" mb-1">{{ $matterRequest->inventors }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Licensee(s)')}}</p>
                        <p class=" mb-1">{{ $matterRequest->licensees }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Assignee(s)')}}</p>
                        <p class=" mb-1">{{ $matterRequest->assignees }}</p>
                    </div>
                </div>
                <hr>

                <div class="row gx-3 mb-3">
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Co-Owners')}}</p>
                        <p class=" mb-1">{{ $matterRequest->co_owners }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Adverse Party(ies)')}}</p>
                        <p class=" mb-1">{{ $matterRequest->adverse_parties }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Entity Size')}}</p>
                        <p class=" mb-1">{{ $matterRequest->entity_size }}</p>
                    </div>
                </div>
                <hr>

                <div class="row gx-3 mb-3">
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Renewal Fees Handled Elsewhere')}}</p>
                        <p class=" mb-1">{{ ($matterRequest->renewal_fees_handled_elsewhere) ? 'Yes' : 'No' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Other Related Party(ies)')}}</p>
                        <p class=" mb-1">{{ $matterRequest->other_related_parties }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="fw-bold mb-1">{{__('Key Terms for Conflict Search')}}</p>
                        <p class=" mb-1">{{ $matterRequest->key_terms_for_conflict_search }}</p>
                    </div>
                </div>
                <hr>
                <div class="row gx-3 mb-3">
                    <div class="col-md-12">
                        <p class="fw-bold mb-1">{{__('Matter & Conflicts Notes')}}</p>
                        <p class=" mb-1">{{ $matterRequest->conflict_search_needed_explanation }}</p>
                    </div>
                </div>
                <hr>
                <div class="row gx-3 mb-3">
                    <div class="col-md-12">
                        <p class="fw-bold mb-1">{{__('Related Cases (for conflicts or to cross-cite art)')}}</p>
                        <p class=" mb-1">{{ $matterRequest->related_cases }}</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="card card-header-actions mx-auto mb-4">
            <div class="card-header">
                {{__('Attached Files')}}
            </div>
            <div class="card-body">
                @if($matterRequest->files->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>{{__('File Name')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($matterRequest->files as $file)
                                <tr>
                                    <td>{{ $file->name }}</td>
                                    <td>{{ $file->mime_type }}</td>
                                    <td class="d-flex align-items-center">
                                        <a href="{{ route('file.download', $file) }}" class="btn btn-sm btn-primary">{{__('Download')}} &nbsp; <i class="fa-solid fa-arrow-down"></i></a>
                                        &nbsp;
                                        <form action="{{ route('file.delete', $file) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">{{__('Delete')}} &nbsp; <i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>{{__('No files attached')}}</p>
                @endif
            </div>
        </div>

        <div class="card card-header-actions mx-auto mb-4">
            <div class="card-header">
                {{  __('Matter Request Approvals')}}
            </div>
            <div class="card-body">
                <table id="matterSubTypesDataTables" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Stage</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Stage</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createApprovalModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{__(' Create Matter Request Approval ') }}
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('matter-requests.post-approval') }}" >
                    <input type="hidden" name="approval_id" id="approval_id" value="">
                    <div class="modal-body">
                        @csrf
                        <div class="row gx-3 mb-3">
                            <div class="col-md-12">
                                <label class="small mb-1" for="responsible_attorney_id">{{__('Status')}}</label>
                                <select required class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="" selected disabled>Select Status</option>
                                    <option value="{{ \App\Models\MatterRequestApproval::STATUS_APPROVED }}">{{ __('Approved') }}</option>
                                    <option value="{{ \App\Models\MatterRequestApproval::STATUS_CHANGES_REQUESTED }}">{{ __('Request Changes') }}</option>
                                    <option value="{{ \App\Models\MatterRequestApproval::STATUS_REJECTED }}">{{ __('Rejected') }}</option>
                                </select>
                                @error('status')
                                <div class="text-sm text-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="col-md-12">
                                <label class="small mb-1" for="email">{{__('Remarks')}}</label>
                                <textarea required class="form-control @error('remarks') is-invalid @enderror" id="remarks"  placeholder="Enter a short remark" name="remarks" rows="10">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <div class="text-sm text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Save</button>
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
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
                $('#matterSubTypesDataTables').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    order: [0, 'DESC'],
                    ajax: {
                        url: "{{ route('matter-request-approvals.data', $matterRequest) }}",
                        dataType: 'json',
                        type:'GET'
                    },
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'user', name: 'user' },
                        { data: 'approval_type', name: 'approval_type' },
                        { data: 'status', name: 'status'},
                        { data: 'remarks', name: 'remarks'},
                        { data: 'submitted_at', name: 'submitted_at'},
                        { data: 'action', name: 'action', searchable:false, orderable:false }
                    ],
                });

            $(document).on('click', '.create-approval', function() {
                var approvalId = $(this).data('approval_id');
                $('#approval_id').val(approvalId);
            });
        });
    </script>
@endsection
