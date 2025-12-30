<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="id" data-theme="light">

<x-admin.head />

<body>
    <section class="auth forgot-password-page bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
           <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="{{ asset('storage/' . ui_value('auth-setting', 'image')) }}"
                    alt="" 
                    class="w-100 h-100 object-fit-cover"
                    style="@media (max-width: 768px) { width: 100% !important; }">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <h4 class="mb-12">Lupa Password</h4>
                    <p class="mb-32 text-secondary-light text-lg">Masukkan alamat email yang terkait dengan akun Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
                </div>
                <form method="POST" action="{{ route('admin.password.email') }}">
                    @csrf
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>
                        <input type="email" name="email" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Masukkan Email" required>
                    </div>
                    @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                     @if (session('status'))
                        <div class="alert alert-success mt-2">
                            {{ session('status') }}
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Lanjutkan</button>

                    <div class="text-center">
                        <a href="{{ route('admin.login') }}" class="text-primary-600 fw-bold mt-24">Kembali ke Halaman Login</a>
                    </div>

                    <div class="mt-120 text-center text-sm">
                        <p class="mb-0">Sudah punya akun? <a href="{{ route('admin.login') }}" class="text-primary-600 fw-semibold">Masuk</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <x-admin.script/>
</body>
</html>
