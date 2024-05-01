<!-- Change password card-->
<div class="card mb-4">
    <div class="card-header">{{__('Change Password')}}</div>
    <div class="card-body">
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')
            <!-- Form Group (current password)-->
            <div class="mb-3">
                <label class="small mb-1" for="currentPassword">{{__('Current Password')}}</label>
                <input required class="form-control  @error('current_password') is-invalid @enderror" id="currentPassword" type="password" placeholder="Enter current password" name="current_password"/>
                @error('current_password')
                <div class="text-sm text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Form Group (new password)-->
            <div class="mb-3">
                <label class="small mb-1" for="newPassword">{{__('New Password')}}</label>
                <input required class="form-control  @error('password') is-invalid @enderror" id="newPassword" type="password" placeholder="Enter new password" name="password"/>
                @error('password')
                <div class="text-sm text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Form Group (confirm password)-->
            <div class="mb-3">
                <label class="small mb-1" for="confirmPassword">{{__('Confirm Password')}}</label>
                <input required class="form-control @error('password_confirmation') is-invalid @enderror" id="confirmPassword" type="password" placeholder="Confirm new password" name="password_confirmation" />
                @error('password_confirmation')
                <div class="text-sm text-danger">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <button class="btn btn-primary" type="submit">{{__('Save')}}</button>
        </form>
    </div>
</div>
