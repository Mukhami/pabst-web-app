@extends('backend.admin.layouts.master')

@section('content')
    @include('backend.partials.page-header', ['title' => $title, 'sub_title' => $sub_title ?? ''])
    <!-- Notification form -->
    <div class="container-xl px-4 mt-n10">
        <div class="card mb-4">
            <div class="card-header">Send Email Notification</div>
            <div class="card-body">
                <p class="text-muted">
                    Fill in the details below to send a notification email.
                </p>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form method="post" action="{{ route('notifications.send') }}">
                    @csrf
                    <!-- Form Row -->
                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1" for="subject">{{ __('Subject') }}</label>
                            <input required class="form-control @error('subject') is-invalid @enderror" id="subject" type="text" placeholder="Enter email subject" value="{{ old('subject') }}" name="subject" />
                            @error('subject')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1" for="message">{{ __('Message') }}</label>
                            <textarea required class="form-control @error('message') is-invalid @enderror" id="message" placeholder="Enter your message" name="message">{{ old('message') }}</textarea>
                            @error('message')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label class="small mb-1" for="recipients">{{ __('Recipients (comma-separated emails)') }}</label>
                            <input required class="form-control @error('recipients') is-invalid @enderror" id="recipients" type="text" placeholder="e.g. user1@example.com, user2@example.com" value="{{ old('recipients') }}" name="recipients" />
                            <p class="text-muted">
                                Enter multiple email addresses separated by commas.
                            </p>
                            @error('recipients')
                            <div class="text-sm text-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Send button -->
                    <button class="btn btn-primary" type="submit">{{ __('Send Notification') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
