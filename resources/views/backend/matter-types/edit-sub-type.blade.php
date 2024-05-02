@extends('backend.admin.layouts.master')

@section('content')
    @include('backend.partials.page-header', ['title' => $title, 'sub_title' => $sub_title ?? ''])
    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">{{__('Update Matter Type')}}</div>
            <div class="card-body">
                <p class="text-muted">
                    {{__('Kindly fill in the form below to update Matter Type Details.')}}
                </p>
                <form method="post" action="{{ route('matter-sub-types.update', $matterSubType) }}" >
                    @csrf
                    @method('put')
                    <!-- Form Row  -->
                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1" for="name">{{__('Parent Name')}}</label>
                            <input readonly class="form-control " style="background: #E9EAEC; cursor: not-allowed" type="text" value="{{ $matterSubType->matter_type->name }}">
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1" for="name">{{__('Sub Type Name')}}</label>
                            <input required class="form-control @error('name') is-invalid @enderror" id="name" type="text" placeholder="Enter Sub Type Name" value="{{ old('name', $matterSubType->name) }}" name="name"/>
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
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"  placeholder="Enter a short description" name="description" rows="10">{{ old('description', $matterSubType->description) }}</textarea>
                            @error('description')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input  @error('status') is-invalid @enderror" type="checkbox" value="active" id="matterTypeStatus" name="status" {{ (old('status', $matterSubType->status)) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="matterTypeStatus">
                                    {{__('Status')}}
                                </label>
                                <p class="text-muted">
                                    {{__('Status determines if the Sub Type is active or inactive.')}}
                                </p>
                            </div>
                            @error('status')
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
