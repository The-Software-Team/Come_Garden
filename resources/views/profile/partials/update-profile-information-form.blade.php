<section>

    <h3>🧾 Profile Information</h3>
    <p class="text-muted">
        Update your name and email.
    </p>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="form-group">
            <label>Name</label>
            <input class="input" type="text" name="name"
                   value="{{ old('name', $user->name) }}">
            
            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>Email</label>
            <input class="input" type="email" name="email"
                   value="{{ old('email', $user->email) }}">

            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="alert warning">
                Your email is not verified.

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button class="link">Resend verification email</button>
                </form>
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="alert success">
                    Verification link sent!
                </div>
            @endif
        @endif

        <button class="btn">Save Changes</button>

        @if (session('status') === 'profile-updated')
            <p class="success-text">Saved successfully.</p>
        @endif

    </form>

</section>