@extends("template.main")

@section("title", "Registration Form")

@section('body')
<div class="row justify-content-center align-items-center" style="min-height: 500px">
    <form method="POST" action="{{ route('register') }}" style="max-width: 400px">
        @csrf
        <div class="mb-4">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" id="full_name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required autofocus />
            @error('full_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="form-label">Email address</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required />
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password" />
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirm" class="form-label">Confirm Password</label>
            <input type="password" id="password_confirm" name="password_confirm" class="form-control" required autocomplete="new-password" />
        </div>
        <button type="submit" class="btn btn-primary btn-block mb-4">Register</button>
    </form>
</div>
@endsection
