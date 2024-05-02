@extends('backend.admin.layouts.master')

@section('content')
    @include('backend.partials.page-header', ['title' => $title, 'sub_title' => $sub_title ?? ''])
    <!-- Account details card-->
    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Create New User</div>
            <div class="card-body">
                <p class="text-muted">
                    Kindly fill in the form below to create a new user.
                    Their log in credentials will be sent to the provided email address.
                </p>
                <form method="post" action="{{ route('users.store') }}" >
                    @csrf
                    <!-- Form Row  -->
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="name">{{__('Full Name')}}</label>
                            <input required class="form-control @error('name') is-invalid @enderror" id="name" type="text" placeholder="Enter Full Name" value="{{ old('name') }}" name="name"/>
                            @error('name')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="small mb-1" for="email">{{__('Email Address')}}</label>
                            <input required class="form-control @error('email') is-invalid @enderror" id="email" type="email" placeholder="Enter email address" value="{{ old('email') }}" name="email" />
                            @error('email')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="role">{{__('Roles')}}</label>
                            <br>
                            @foreach($roles as $role)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="{{$role->id}}" id="userRole{{$role->id}}" name="roles[]" {{  (in_array($role->id, old('roles', [])) ? 'checked' : '' )}}>
                                    <label class="form-check-label" for="userRole{{$role->id}}">
                                        {{ ucwords(str_replace('_', ' ', $role->name)) }}
                                    </label>
                                </div>
                            @endforeach
                            <p class="text-muted">
                                {{__('Select the roles to be assigned to the user.')}}
                            </p>
                            @error('roles')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input  @error('status') is-invalid @enderror" type="checkbox" value="active" id="userStatus" name="status" {{ (old('status')) ? 'checked' : '' }}>
                                <label class="form-check-label small" for="userStatus">
                                    {{__('User Status')}}
                                </label>
                                <p class="text-muted">
                                    {{__('User Status determines if the user is active or inactive.')}}
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
