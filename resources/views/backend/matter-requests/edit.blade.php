@extends('backend.admin.layouts.master')

@section('content')
    @include('backend.partials.page-header', ['title' => $title, 'sub_title' => $sub_title ?? '', 'icon' => 'life-buoy'])
    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">{{__('Create a New Matter Request')}}</div>
            <div class="card-body">
                <p class="text-muted">
                    {{__('Kindly fill in the form below to create a new Matter Request.')}}
                </p>
                <form method="post" action="{{ route('matter-requests.update', $matterRequest) }}" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <!-- Form Row  -->
                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label class="small mb-1" for="ppg_client_matter_no">{{__('PPG Billing Number')}} <span class="text-danger">*</span></label>
                            <input required class="form-control @error('ppg_client_matter_no') is-invalid @enderror" id="ppg_client_matter_no" type="text" placeholder="Enter PPG Billing Number" value="{{ old('ppg_client_matter_no', $matterRequest->ppg_client_matter_no) }}" name="ppg_client_matter_no"/>
                            <small>{{__('Accepted format: [ClientID]/[MatterID], e.g., 012345/67890')}}</small>
                            @error('ppg_client_matter_no')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="ppg_ref">{{__('PPG Ref')}} </label>
                            <input class="form-control @error('ppg_ref') is-invalid @enderror" id="ppg_ref" type="text" placeholder="Enter PPG Ref" value="{{ old('ppg_ref', $matterRequest->ppg_ref) }}" name="ppg_ref"/>
                            @error('ppg_ref')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="client_ref">{{__('Client Ref')}}</label>
                            <input class="form-control @error('client_ref') is-invalid @enderror" id="client_ref" type="text" placeholder="Enter Client Ref" value="{{ old('client_ref', $matterRequest->client_ref) }}" name="client_ref"/>
                            @error('client_ref')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label class="small mb-1" for="client_name">{{__('Client Name')}} <span class="text-danger">*</span></label>
                            <input required class="form-control @error('client_name') is-invalid @enderror" id="client_name" type="text" placeholder="Enter Client Name" value="{{ old('client_name', $matterRequest->client_name) }}" name="client_name"/>
                            @error('client_name')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="small mb-1" for="client_name">{{__('Client Main Contact')}} <span class="text-danger">*</span></label>
                            <input required class="form-control @error('client_main_contact') is-invalid @enderror" id="client_main_contact" type="text" placeholder="Enter Client Main Contact" value="{{ old('client_main_contact', $matterRequest->client_main_contact) }}" name="client_main_contact"/>
                            @error('client_main_contact')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="small mb-1" for="entity_size">{{__('Entity Size')}} </label>
                            <select class="form-select @error('entity_size') is-invalid @enderror" id="entity_size" name="entity_size">
                                <option value="" selected disabled>Select Entity Size</option>
                                @foreach($entity_sizes as $entity_size)
                                    <option value="{{ $entity_size }}" {{ (old('entity_size', $matterRequest->entity_size) == $entity_size) ? 'selected' : '' }}>{{  ucfirst($entity_size) }}</option>
                                @endforeach
                            </select>
                            @error('entity_size')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1" for="title_of_invention">{{__('Title of Invention')}} <span class="text-danger">*</span></label>
                            <input required class="form-control @error('title_of_invention') is-invalid @enderror" id="title_of_invention" type="text" placeholder="Enter Title of Invention" value="{{ old('title_of_invention', $matterRequest->title_of_invention) }}" name="title_of_invention"/>
                            @error('title_of_invention')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="matter_type_id">{{__('Matter Type')}} <span class="text-danger">*</span></label>
                            <select required class="form-select @error('matter_type_id') is-invalid @enderror" id="matter_type_id" name="matter_type_id">
                                <option value="" selected disabled>Select Matter Type</option>
                                @foreach($matter_types as $matterType)
                                    <option value="{{ $matterType->id }}" {{ (old('matter_type_id', $matterRequest->matter_type_id) == $matterType->id) ? 'selected' : '' }}>{{ $matterType->name }}</option>
                                @endforeach
                            </select>
                            @error('matter_type_id')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="sub_type_id">{{__('Matter Sub Type')}}</label>
                            <select class="form-select @error('sub_type_id') is-invalid @enderror" id="sub_type_id" name="sub_type_id">
                                <option value="" selected disabled>Select Matter Type</option>
                                @foreach($matter_sub_types as $matter_sub_type)
                                    <option value="{{ $matter_sub_type->id }}" data-type-id="{{ $matter_sub_type->matter_type_id }}" {{ (old('sub_type_id', $matterRequest->sub_type_id) == $matter_sub_type->id) ? 'selected' : '' }}>{{ $matter_sub_type->name }}</option>
                                @endforeach
                            </select>
                            @error('sub_type_id')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label class="small mb-1" for="bar_date">{{__('Bar Date')}} </label>
                            <input class="form-control @error('bar_date') is-invalid @enderror" id="bar_date" type="date" placeholder="Enter Bar Date" value="{{ old('bar_date', $matterRequest->bar_date) }}" name="bar_date"/>
                            @error('bar_date')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="ppg_ref">{{__('Goal Date')}} </label>
                            <input class="form-control @error('goal_date') is-invalid @enderror" id="goal_date" type="date" placeholder="Enter Goal Date" value="{{ old('goal_date', $matterRequest->goal_date) }}" name="goal_date"/>
                            @error('goal_date')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="client_ref">{{__('Conversion Date')}}</label>
                            <input class="form-control @error('conversion_date') is-invalid @enderror" id="conversion_date" type="date" placeholder="Enter Conversion Date" value="{{ old('conversion_date', $matterRequest->conversion_date) }}" name="conversion_date"/>
                            @error('conversion_date')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label class="small mb-1" for="inventors">{{__('Inventors')}} </label>
                            <input class="form-control @error('inventors') is-invalid @enderror" id="inventors" type="text" placeholder="Enter Inventors" value="{{ old('inventors', $matterRequest->inventors) }}" name="inventors"/>
                            @error('inventors')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="licensees">{{__('Licensee(s)')}}</label>
                            <input class="form-control @error('licensees') is-invalid @enderror" id="licensees" type="text" placeholder="Enter Licensee(s)" value="{{ old('licensees', $matterRequest->licensees) }}" name="licensees"/>
                            @error('licensees')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="assignees">{{__('Applicant(s)')}} </label>
                            <input class="form-control @error('assignees') is-invalid @enderror" id="assignees" type="text" placeholder="Enter Applicant(s)" value="{{ old('assignees', $matterRequest->assignees) }}" name="assignees"/>
                            @error('assignees')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label class="small mb-1" for="co_owners">{{__('Co-Owners')}}</label>
                            <input class="form-control @error('co_owners') is-invalid @enderror" id="co_owners" type="text" placeholder="Enter Co-Owners" value="{{ old('co_owners', $matterRequest->co_owners) }}" name="co_owners"/>
                            @error('co_owners')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="adverse_parties">{{__('Adverse Party(ies)')}}</label>
                            <input class="form-control @error('adverse_parties') is-invalid @enderror" id="adverse_parties" type="text" placeholder="Enter Adverse Party(ies)" value="{{ old('adverse_parties', $matterRequest->adverse_parties) }}" name="adverse_parties"/>
                            @error('adverse_parties')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1">{{__('Renewal Fees Handled Elsewhere')}} <span class="text-danger">*</span></label>
                            <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('renewal_fees_handled_elsewhere') is-invalid @enderror" {{ ($matterRequest->renewal_fees_handled_elsewhere) ? 'checked' : '' }} type="radio" name="renewal_fees_handled_elsewhere" id="renewal_fees_handled_elsewhere_yes" value="true">
                                <label class="form-check-label" for="renewal_fees_handled_elsewhere_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('renewal_fees_handled_elsewhere') is-invalid @enderror" {{ (!$matterRequest->renewal_fees_handled_elsewhere) ? 'checked' : '' }} type="radio" name="renewal_fees_handled_elsewhere" id="renewal_fees_handled_elsewhere_no" value="false">
                                <label class="form-check-label" for="renewal_fees_handled_elsewhere_no">No</label>
                            </div>
                            @error('renewal_fees_handled_elsewhere')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="other_related_parties">{{__('Other Related Party(ies)')}}</label>
                            <input  class="form-control @error('other_related_parties') is-invalid @enderror" id="other_related_parties" type="text" placeholder="Enter Other Related Party(ies)" value="{{ old('other_related_parties', $matterRequest->other_related_parties) }}" name="other_related_parties"/>
                            @error('other_related_parties')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="key_terms_for_conflict_search">{{__('Key Terms for Conflict Search')}} </label>
                            <input  class="form-control @error('key_terms_for_conflict_search') is-invalid @enderror" id="key_terms_for_conflict_search" type="text" placeholder="Enter Key Terms for Conflict Search" value="{{ old('key_terms_for_conflict_search', $matterRequest->key_terms_for_conflict_search) }}" name="key_terms_for_conflict_search"/>
                            @error('key_terms_for_conflict_search')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1" for="conflict_search_needed_explanation">{{__('Matter & Conflicts Notes')}}</label>
                            <textarea class="form-control @error('conflict_search_needed_explanation') is-invalid @enderror" id="conflict_search_needed_explanation" name="conflict_search_needed_explanation" rows="5">{{ old('conflict_search_needed_explanation', $matterRequest->conflict_search_needed_explanation) }}</textarea>
                            @error('conflict_search_needed_explanation')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1" for="related_cases">{{__('Related Cases (for conflicts or to cross-cite art)')}}</label>
                            <textarea class="form-control @error('related_cases') is-invalid @enderror" id="related_cases"  name="related_cases" rows="5">{{ old('related_cases', $matterRequest->related_cases) }}</textarea>
                            @error('related_cases')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1" for="attachments">{{__('Add Attachments')}}</label>
                            <input type="file" multiple class="form-control @error('attachments') is-invalid @enderror" id="attachments"  name="attachments[]"/>
                            @error('attachments')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label class="small mb-1" for="responsible_attorney_id">{{__('Responsible Attorney')}} <span class="text-danger">*</span></label>
                            <select required class="form-select @error('responsible_attorney_id') is-invalid @enderror" id="responsible_attorney_id" name="responsible_attorney_id">
                                <option value="" selected disabled>Select Responsible Attorney</option>
                                @foreach($responsible_attorneys as $responsible_attorney)
                                    <option value="{{ $responsible_attorney->id }}" {{ (old('responsible_attorney_id', $matterRequest->responsible_attorney_id) == $responsible_attorney->id) ? 'selected' : '' }}>{{ $responsible_attorney->name }}</option>
                                @endforeach
                            </select>
                            @error('responsible_attorney_id')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="sub_type_id">{{__('Additional Staff')}} </label>
                            <select class="form-select @error('additional_staff_id') is-invalid @enderror" id="additional_staff_id" name="additional_staff_id">
                                <option value="" selected disabled>Select Matter Type</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}" {{ (old('additional_staff_id', $matterRequest->additional_staff_id) == $member->id) ? 'selected' : '' }}>{{ $member->name }}</option>
                                @endforeach
                            </select>
                            @error('additional_staff_id')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="conductor_id">{{__('Creator (Conductor)')}} <span class="text-danger">*</span></label>
                            <select required class="form-select @error('conductor_id') is-invalid @enderror" id="conductor_id" name="conductor_id">
                                <option value="" selected disabled>Selected Conductor</option>
                                <option value="{{ $matterRequest->conductor->id }}" selected>{{ $matterRequest->conductor->name }}</option>
                            </select>
                            @error('conductor_id')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label class="small mb-1" for="partner_id">{{__('Partner')}} </label>
                            <select class="form-select @error('partner_id') is-invalid @enderror" id="partner_id" name="partner_id">
                                <option value="" selected disabled>Select Partner</option>
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}" {{ (old('partner_id', $matterRequest->partner_id) == $partner->id) ? 'selected' : '' }}>{{ $partner->name }}</option>
                                @endforeach
                            </select>
                            @error('partner_id')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="small mb-1" for="secondary_partner_id">{{__('Secondary Partner')}} </label>
                            <select class="form-select @error('secondary_partner_id') is-invalid @enderror" id="secondary_partner_id" name="secondary_partner_id">
                                <option value="" selected disabled>Select Partner</option>
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}" {{ (old('secondary_partner_id', $matterRequest->secondary_partner_id) == $partner->id) ? 'selected' : '' }}>{{ $partner->name }}</option>
                                @endforeach
                            </select>
                            @error('secondary_partner_id')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="small mb-1" for="conflict_user_id">{{__('Conflicts')}} </label>
                            <select class="form-select @error('conflict_user_id') is-invalid @enderror" id="conflict_user_id" name="conflict_user_id">
                                <option value="" selected disabled>Select Matter Type</option>
                                @foreach($conflict as $member)
                                    <option value="{{ $member->id }}" {{ (old('conflict_user_id', $matterRequest->conflict_user_id) == $member->id) ? 'selected' : '' }}>{{ $member->name }}</option>
                                @endforeach
                            </select>
                            @error('conflict_user_id')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Save changes button-->
                    @if($matterRequest->status === \App\Models\MatterRequest::STATUS_TYPE_DRAFT && !$matterRequest->approval_flow_started)
                        <button class="btn btn-warning mr-2" type="submit" name="save_as_draft" value="true">{{__('Save As Draft')}}</button>
                        <button class="btn btn-primary mr-2" type="submit">{{__('Save As Submitted')}}</button>
                    @else
                        <button class="btn btn-primary mr-2" type="submit">{{__('Save Changes')}}</button>
                    @endif
                    <a class="btn btn-danger" href="{{ route('matter-requests.index') }}">{{__('Cancel')}}</a>
                </form>
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

    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const matterType = document.getElementById('matter_type_id');
            const matterSubType = document.getElementById('sub_type_id');

            function filterSubTypes() {
                const typeId = matterType.value;
                Array.from(matterSubType.options).forEach(option => {
                    if (option.value) {
                        option.style.display = option.getAttribute('data-type-id') === typeId ? '' : 'none';
                    }
                });
            }

            matterType.addEventListener('change', filterSubTypes);

            // Filter sub types on page load if a type is selected
            if (matterType.value) {
                filterSubTypes();
            }
        });
    </script>
@endsection

