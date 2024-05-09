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
                <form method="post" action="{{ route('matter-requests.store') }}" >
                    @csrf
                    <!-- Form Row  -->
                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label class="small mb-1" for="ppg_client_matter_no">{{__('PPG Client Matter Number')}}</label>
                            <input required class="form-control @error('ppg_client_matter_no') is-invalid @enderror" id="ppg_client_matter_no" type="text" placeholder="Enter PPG Client Matter Number" value="{{ old('ppg_client_matter_no') }}" name="ppg_client_matter_no"/>
                            @error('ppg_client_matter_no')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="ppg_ref">{{__('PPG Ref')}}</label>
                            <input required class="form-control @error('ppg_ref') is-invalid @enderror" id="ppg_ref" type="text" placeholder="Enter PPG REF" value="{{ old('ppg_ref') }}" name="ppg_ref"/>
                            @error('ppg_ref')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="client_ref">{{__('Client Ref')}}</label>
                            <input required class="form-control @error('client_ref') is-invalid @enderror" id="client_ref" type="text" placeholder="Enter Client Ref" value="{{ old('client_ref') }}" name="client_ref"/>
                            @error('client_ref')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label class="small mb-1" for="client_name">{{__('Client Name')}}</label>
                            <input required class="form-control @error('client_name') is-invalid @enderror" id="client_name" type="text" placeholder="Enter Client Name" value="{{ old('client_name') }}" name="client_name"/>
                            @error('client_name')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="small mb-1" for="client_name">{{__('Client Main Contact')}}</label>
                            <input required class="form-control @error('client_main_contact') is-invalid @enderror" id="client_main_contact" type="text" placeholder="Enter Client Main Contact" value="{{ old('client_main_contact') }}" name="client_main_contact"/>
                            @error('client_main_contact')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="client_name">{{__('Client Secondary Contact')}}</label>
                            <input required class="form-control @error('client_secondary_contacts') is-invalid @enderror" id="client_secondary_contacts" type="text" placeholder="Enter Client Secondary Contact" value="{{ old('client_secondary_contacts') }}" name="client_secondary_contacts"/>
                            @error('client_secondary_contacts')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1" for="title_of_invention">{{__('Title of Invention')}}</label>
                            <input required class="form-control @error('title_of_invention') is-invalid @enderror" id="title_of_invention" type="text" placeholder="Enter Title of Invention" value="{{ old('title_of_invention') }}" name="title_of_invention"/>
                            @error('title_of_invention')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="matter_type_id">{{__('Matter Type')}}</label>
                            <select required class="form-select @error('matter_type_id') is-invalid @enderror" id="matter_type_id" name="matter_type_id">
                                <option value="" selected disabled>Select Matter Type</option>
                                @foreach($matter_types as $matterType)
                                    <option value="{{ $matterType->id }}" {{ (old('matter_type_id') == $matterType->id) ? 'selected' : '' }}>{{ $matterType->name }}</option>
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
                            <select required class="form-select @error('sub_type_id') is-invalid @enderror" id="sub_type_id" name="sub_type_id">
                                <option value="" selected disabled>Select Matter Type</option>
                                @foreach($matter_sub_types as $matter_sub_type)
                                    <option value="{{ $matter_sub_type->id }}" {{ (old('sub_type_id') == $matter_sub_type->id) ? 'selected' : '' }}>{{ $matter_sub_type->name }}</option>
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
                            <label class="small mb-1" for="ppg_client_matter_no">{{__('Bar Date')}}</label>
                            <input required class="form-control @error('bar_date') is-invalid @enderror" id="bar_date" type="date" placeholder="Enter Bar Date" value="{{ old('bar_date') }}" name="bar_date"/>
                            @error('bar_date')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="ppg_ref">{{__('Goal Date')}}</label>
                            <input required class="form-control @error('goal_date') is-invalid @enderror" id="goal_date" type="date" placeholder="Enter Goal Date" value="{{ old('goal_date') }}" name="goal_date"/>
                            @error('goal_date')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="client_ref">{{__('Conversion Date')}}</label>
                            <input required class="form-control @error('conversion_date') is-invalid @enderror" id="conversion_date" type="date" placeholder="Enter Conversion Date" value="{{ old('conversion_date') }}" name="conversion_date"/>
                            @error('conversion_date')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label class="small mb-1" for="inventors">{{__('Investors')}}</label>
                            <input required class="form-control @error('inventors') is-invalid @enderror" id="inventors" type="text" placeholder="Enter Investors" value="{{ old('inventors') }}" name="inventors"/>
                            @error('inventors')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="licensees">{{__('Licensee(s)')}}</label>
                            <input required class="form-control @error('licensees') is-invalid @enderror" id="licensees" type="text" placeholder="Enter Licensee(s)" value="{{ old('licensees') }}" name="licensees"/>
                            @error('licensees')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="assignees">{{__('Assignee(s)')}}</label>
                            <input required class="form-control @error('assignees') is-invalid @enderror" id="assignees" type="text" placeholder="Enter Assignee(s)" value="{{ old('assignees') }}" name="assignees"/>
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
                            <input required class="form-control @error('co_owners') is-invalid @enderror" id="co_owners" type="text" placeholder="Enter Co-Owners" value="{{ old('co_owners') }}" name="co_owners"/>
                            @error('co_owners')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="adverse_parties">{{__('Adverse Party(ies)')}}</label>
                            <input required class="form-control @error('adverse_parties') is-invalid @enderror" id="adverse_parties" type="text" placeholder="Enter Adverse Party(ies)" value="{{ old('adverse_parties') }}" name="adverse_parties"/>
                            @error('adverse_parties')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="small mb-1" for="entity_size">{{__('Entity Size')}}</label>
                            <input required class="form-control @error('entity_size') is-invalid @enderror" id="entity_size" type="text" placeholder="Enter Entity Size" value="{{ old('entity_size') }}" name="entity_size"/>
                            @error('entity_size')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1">{{__('Renewal Fees Handled Elsewhere')}}</label>
                            <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('renewal_fees_handled_elsewhere') is-invalid @enderror" type="radio" name="renewal_fees_handled_elsewhere" id="renewal_fees_handled_elsewhere_yes" value="true">
                                <label class="form-check-label" for="renewal_fees_handled_elsewhere_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('renewal_fees_handled_elsewhere') is-invalid @enderror" type="radio" name="renewal_fees_handled_elsewhere" id="renewal_fees_handled_elsewhere_no" value="false">
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
                            <input  class="form-control @error('other_related_parties') is-invalid @enderror" id="other_related_parties" type="text" placeholder="Enter Other Related Party(ies)" value="{{ old('other_related_parties') }}" name="other_related_parties"/>
                            @error('other_related_parties')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="key_terms_for_conflict_search">{{__('Key Terms for Conflict Search')}}</label>
                            <input  class="form-control @error('key_terms_for_conflict_search') is-invalid @enderror" id="key_terms_for_conflict_search" type="text" placeholder="Enter Key Terms for Conflict Search" value="{{ old('key_terms_for_conflict_search') }}" name="key_terms_for_conflict_search"/>
                            @error('key_terms_for_conflict_search')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1" for="conflict_search_needed_explanation">{{__('If a conflict search is not needed, please explain why')}}</label>
                            <textarea class="form-control @error('conflict_search_needed_explanation') is-invalid @enderror" id="conflict_search_needed_explanation" name="conflict_search_needed_explanation" rows="5">{{ old('conflict_search_needed_explanation') }}</textarea>
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
                            <textarea class="form-control @error('related_cases') is-invalid @enderror" id="related_cases"  name="related_cases" rows="5">{{ old('related_cases') }}</textarea>
                            @error('related_cases')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="responsible_attorney_id">{{__('Responsible Attorney')}}</label>
                            <select required class="form-select @error('responsible_attorney_id') is-invalid @enderror" id="responsible_attorney_id" name="responsible_attorney_id">
                                <option value="" selected disabled>Select Responsible Attorney</option>
                                @foreach($responsible_attorneys as $responsible_attorney)
                                    <option value="{{ $responsible_attorney->id }}" {{ (old('responsible_attorney_id') == $responsible_attorney->id) ? 'selected' : '' }}>{{ $responsible_attorney->name }}</option>
                                @endforeach
                            </select>
                            @error('responsible_attorney_id')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="sub_type_id">{{__('Additional Staff')}}</label>
                            <select required class="form-select @error('additional_staff_id') is-invalid @enderror" id="additional_staff_id" name="additional_staff_id">
                                <option value="" selected disabled>Select Matter Type</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}" {{ (old('additional_staff_id') == $member->id) ? 'selected' : '' }}>{{ $member->name }}</option>
                                @endforeach
                            </select>
                            @error('additional_staff_id')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Save changes button-->
                    <button class="btn btn-primary" type="submit">{{__('Save changes')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
