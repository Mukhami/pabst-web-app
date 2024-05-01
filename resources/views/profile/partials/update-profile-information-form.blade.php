<!-- Account details card-->
<div class="card mb-4">
    <div class="card-header">Account Details</div>
    <div class="card-body">
        <form method="post" action="{{ route('profile.update') }}" >
            @csrf
            @method('patch')
            <!-- Form Row        -->
            <div class="row gx-3 mb-3">
                <div class="col-md-6">
                    <label class="small mb-1" for="name">{{__('Full Name')}}</label>
                    <input required class="form-control @error('name') is-invalid @enderror" id="name" type="text" placeholder="Enter your Full Name" value="{{ old('name', $user->name) }}" name="name"/>
                    @error('name')
                    <div class="text-sm text-danger">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="small mb-1" for="email">{{__('Email Address')}}</label>
                    <input required class="form-control @error('email') is-invalid @enderror" id="email" type="email" placeholder="Enter Your email address" value="{{ old('email', $user->email) }}" name="email" />
                    @error('email')
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
