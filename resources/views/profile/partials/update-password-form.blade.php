<section>

    <h3>🔐 Update Password</h3>
    <p class="text-muted">
        Use a strong password to keep your account secure.
    </p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="form-group">
            <label>Current Password</label>
            <input class="input" type="password" name="current_password">

            @error('current_password', 'updatePassword')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input class="input" type="password" name="password">

            @error('password', 'updatePassword')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input class="input" type="password" name="password_confirmation">

            @error('password_confirmation', 'updatePassword')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button class="btn">Update Password</button>

        @if (session('status') === 'password-updated')
            <p class="success-text">Password updated.</p>
        @endif

    </form>

</section>