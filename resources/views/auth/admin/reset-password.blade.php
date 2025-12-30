<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-admin.head />
<div class="d-flex align-items-center justify-content-center min-vh-100">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') == 'passwords.reset' ? 'Password berhasil direset' : session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.password.update') }}" class="max-w-464-px mx-auto w-100">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ request('email') }}">

        <div class="text-center mb-4">
            <h4>Reset Password</h4>
            <p class="text-secondary">Masukkan password baru Anda di bawah ini</p>
        </div>

        <div class="position-relative mb-20">
            <div class="icon-field">
                <span class="icon top-50 translate-middle-y">
                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                </span>
                <input type="password" 
                    name="password" 
                    class="form-control h-54-px bg-neutral-50 radius-12"
                    id="new-password" 
                    placeholder="Password Baru" 
                    required>
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                onclick="toggleNewPassword()"></span>
        </div>

        <div class="position-relative mb-20">
            <div class="icon-field">
                <span class="icon top-50 translate-middle-y">
                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                </span>
                <input type="password" 
                    name="password_confirmation" 
                    class="form-control h-54-px bg-neutral-50 radius-12"
                    id="confirm-password" 
                    placeholder="Konfirmasi Password" 
                    required>
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                onclick="toggleConfirmPassword()"></span>
        </div>

        <button type="submit" class="btn btn-primary text-sm btn-sm px-10 py-14 w-100 radius-12 mt-16">
            Reset Password
        </button>
    </form>
</div>
<script>
    function toggleNewPassword() {
        const passwordInput = document.getElementById('new-password');
        const toggleButton = document.querySelector('.toggle-password');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.classList.remove('ri-eye-line');
            toggleButton.classList.add('ri-eye-off-line');
        } else {
            passwordInput.type = 'password';
            toggleButton.classList.remove('ri-eye-off-line');
            toggleButton.classList.add('ri-eye-line');
        }
    }

    function toggleConfirmPassword() {
        const passwordInput = document.getElementById('confirm-password');
        const toggleButton = document.querySelector('.toggle-password');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.classList.remove('ri-eye-line');
            toggleButton.classList.add('ri-eye-off-line');
        } else {
            passwordInput.type = 'password';
            toggleButton.classList.remove('ri-eye-off-line');
            toggleButton.classList.add('ri-eye-line');
        }
    }
</script>

   <script>
        function togglePassword() {
            const passwordInput = document.getElementById('your-password');
            const toggleButton = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.classList.remove('ri-eye-line');
                toggleButton.classList.add('ri-eye-off-line');
            } else {
                passwordInput.type = 'password';
                toggleButton.classList.remove('ri-eye-off-line');
                toggleButton.classList.add('ri-eye-line');
            }
        }
    </script>

    <x-admin.script />
</body>
</html>