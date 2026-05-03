<section>

    <h3>⚠️ Delete Account</h3>
    <p class="text-muted">
        This action is permanent. Your data will be lost.
    </p>

    <form method="POST" action="{{ route('profile.destroy') }}"
          onsubmit="return confirm('Are you sure you want to delete your account?');">
        
        @csrf
        @method('delete')

        <div class="form-group">
            <label>Enter Password</label>
            <input class="input" type="password" name="password">
            
            @error('password', 'userDeletion')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button class="btn-danger">Delete Account</button>

    </form>

</section>