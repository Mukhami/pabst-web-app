@extends('backend.admin.layouts.master')

@section('styles')
    <!-- Datatables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.3.1/jszip-2.5.0/dt-1.11.0/af-2.3.7/b-2.0.0/b-colvis-2.0.0/b-html5-2.0.0/b-print-2.0.0/cr-1.5.4/date-1.1.1/fc-3.3.3/fh-3.1.9/kt-2.6.4/r-2.2.9/rg-1.1.3/rr-1.2.8/sc-2.0.5/sb-1.2.0/sp-1.4.0/sl-1.3.3/datatables.min.css"/>
@endsection

@section('content')
    @include('backend.partials.page-header', ['title' => $title, 'sub_title' => $sub_title ?? ''])
    <div class="container-xl px-4 mt-n10">
        <div class="card card-header-actions mx-auto mb-4">
            <div class="card-header">
                {{__('Matter Type Details')}}
                <div>
                    <a class="btn btn-primary btn-icon mr-2" href="{{ route('matter-types.edit', $matterType) }}">
                        <i data-feather="edit"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <p class="fw-bold mb-1">{{__('Matter Type Name')}}</p>
                        <p class=" mb-1">{{ $matterType->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-bold mb-1">{{__('Matter Type Status')}}</p>
                        <p class=" mb-1">
                            @if($matterType->status)
                                <span class="badge bg-success">{{__('Active')}}</span>
                            @else
                                <span class="badge bg-danger">{{__('Inactive')}}</span>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-12">
                        <p class="fw-bold mb-1">{{__('Description')}}</p>
                        <p class=" mb-1">{{ $matterType->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-header-actions mx-auto mb-4">
            <div class="card-header">
                {{ $matterType->name . __(' Sub Types')}}
                <div>
                    <button class="btn btn-primary btn-icon mr-2" type="button" data-bs-toggle="modal" data-bs-target="#addMatterSubTypeModal">
                        <i data-feather="plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table id="matterSubTypesDataTables" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                    </tfoot>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addMatterSubTypeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{__('Add New Matter Sub Type to ') . $matterType->name }}
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('matter-sub-types.store') }}" >
                    <input type="hidden" name="matter_type_id" value="{{ $matterType->id }}">
                    <div class="modal-body">
                            @csrf
                            <!-- Form Row  -->
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-1" for="name">{{__('Sub Type Name')}}</label>
                                    <input required class="form-control @error('name') is-invalid @enderror" id="name" type="text" placeholder="Enter Matter Type Name" value="{{ old('name') }}" name="name"/>
                                    @error('name')
                                    <div class="text-sm text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <label class="small mb-1" for="email">{{__('Description')}}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description"  placeholder="Enter a short description" name="description" rows="10">
                                    {{ old('description') }}
                                </textarea>
                                    @error('description')
                                    <div class="text-sm text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row gx-3 mb-3">
                                <div class="col-md-12">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input  @error('status') is-invalid @enderror" type="checkbox" value="active" id="matterTypeStatus" name="status" {{ (old('status')) ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="matterTypeStatus">
                                            {{__('Status')}}
                                        </label>
                                        <p class="text-muted">
                                            {{__('Status determines if the Matter Type is active or inactive.')}}
                                        </p>
                                    </div>
                                    @error('status')
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
                        url: "{{ route('matter-subtypes.data', $matterType) }}",
                        dataType: 'json',
                        type:'GET'
                    },
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name', name: 'name' },
                        { data: 'description', name: 'description' },
                        { data: 'status', name: 'status'},
                        { data: 'created_at', name: 'created_at'},
                        { data: 'updated_at', name: 'updated_at'},
                        { data: 'action', name: 'action', searchable:false, orderable:false }
                    ],
                });
        });
    </script>
@endsection
