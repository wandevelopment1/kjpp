<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-admin.head />

<body>
    @php
        $branches = [
            [
                'label' => 'KANTOR PUSAT',
                'address' => ui_value('contact-info', 'address1') ?? 'Jalan Perintis Kemerdekaan No. 18a, Kebon Kelapa, Bogor 16125',
                'phone' => ui_value('contact-info', 'phone1') ?? '+62 251 8393066, 8396646',
                'email' => ui_value('contact-info', 'email1') ?? 'pusat@kjppgunawan.com',
                'website' => 'www.kjppgunawan.com',
            ],
            [
                'label' => 'Cabang Bandung',
                'address' => ui_value('contact-info', 'address2') ?? 'Jalan SMP 1 Cileunyi, Komplek Haruman Asri Blok D No. 06, Cimekar, Bandung',
                'phone' => ui_value('contact-info', 'phone2') ?? '+62 22 87884068',
                'email' => ui_value('contact-info', 'email2') ?? 'gunawanbandungtimur@gmail.com',
            ],
            [
                'label' => 'Cabang Semarang',
                'address' => ui_value('contact-info', 'address3') ?? 'Graha Kedondong Asri, Jl. Kedondong Dalam IV No. 8a, Lamper Tengah, Semarang 50248',
                'phone' => ui_value('contact-info', 'phone3') ?? '+62 24 76411535',
                'email' => ui_value('contact-info', 'email3') ?? 'kjppgunawansmg18@gmail.com',
            ],
            [
                'label' => 'Cabang Cirebon',
                'address' => ui_value('contact-info', 'address4') ?? 'Jl. Anyelir No. 23, Kedawung Jaya, Cirebon 45153',
                'phone' => ui_value('contact-info', 'phone4') ?? '+62 231 8803062',
                'email' => ui_value('contact-info', 'email4') ?? 'kjppgncirebon@gmail.com',
            ],
            [
                'label' => 'Cabang Purwakarta',
                'address' => ui_value('contact-info', 'address5') ?? 'Jl. Veteran SMP 2 No. 9, Ciseureuh, Purwakarta 41118',
                'phone' => ui_value('contact-info', 'phone5') ?? '+62 264 8307505',
                'email' => ui_value('contact-info', 'email5') ?? 'kjppgpwk@gmail.com',
            ],
        ];

        $companyAbout = ui_value('web-setting', 'about');
        $companyVision = ui_value('web-setting', 'visi');
        $companyMission = ui_value('web-setting', 'misi');
        $companyLogo = ui_value('web-setting', 'logo');

    @endphp

    <style>
        .auth-left-panel {
            background: linear-gradient(135deg, #040b1a, #0f172a 35%, #1d4ed8);
            color: #f8fafc;
            padding: 48px;
            display: flex;
            flex-direction: column;
            gap: 32px;
            overflow-y: auto;
            max-height: 100vh;
        }
        .auth-left-panel .section-label {
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(248, 250, 252, 0.65);
            margin-bottom: 4px;
        }
        .auth-left-panel .info-card {
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(15, 23, 42, 0.35);
            backdrop-filter: blur(10px);
            padding: 28px;
        }
        .auth-left-panel .info-card--light {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.18);
        }
        .auth-left-panel .info-card h3,
        .auth-left-panel .info-card h5 {
            margin: 0 0 12px;
            color: #e2e8f0;
        }
        .auth-left-panel .info-card p,
        .auth-left-panel .info-card div,
        .auth-left-panel .info-card li {
            margin: 0;
            font-size: 14px;
            line-height: 1.6;
        }
        .auth-left-panel .branch-card {
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 20px;
            background: rgba(4, 11, 26, 0.55);
            color: #f8fafc;
            box-shadow: 0 15px 35px rgba(4, 11, 26, 0.25);
        }
        .auth-left-panel .branch-card__title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 12px;
        }
        .auth-left-panel .branch-card__title span {
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
        }
        .auth-left-panel .branch-card__title .badge {
            background: rgba(255, 255, 255, 0.15);
            color: #f8fafc;
            border-radius: 999px;
            font-size: 12px;
            padding: 4px 12px;
            letter-spacing: 0.05em;
        }
        .auth-left-panel .branch-card__meta {
            font-size: 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .auth-left-panel .branch-card__meta-label {
            font-weight: 600;
            color: #93c5fd;
            min-width: 60px;
            display: inline-block;
        }
        .auth-left-panel .network-card {
            border-radius: 24px;
            background: rgba(15, 23, 42, 0.45);
            color: #f8fafc;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(4, 11, 26, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        .auth-left-panel .network-card h5 {
            color: #f8fafc;
            margin-bottom: 4px;
        }
        .auth-footer {
            text-align: center;
            color: rgba(248, 250, 252, 0.7);
            font-size: 13px;
            letter-spacing: 0.05em;
            padding: 24px 16px 32px;
        }
        .auth-footer span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .auth-footer::before {
            content: "";
            display: block;
            width: 100%;
            max-width: 420px;
            height: 1px;
            margin: 0 auto 16px;
            background: rgba(248, 250, 252, 0.25);
        }
        @media (max-width: 1024px) {
            .auth-left-panel {
                padding: 32px;
            }
        }
        @media (max-width: 575px) {
            .auth-left-panel {
                padding: 24px;
            }
        }
    </style>

    <section class="auth bg-base d-flex flex-wrap">
        <div class="auth-left auth-left-panel">
            <div class="info-card">
                @if($companyLogo)
                <div class="mb-3 text-center">
                    <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ ui_value('web-setting', 'title') }}" style="width: 100%; max-width: 220px; height: 48px; object-fit: contain;">
                </div>
                @endif
                <p class="section-label">Our Company</p>
                <h3 class="fw-semibold" style="font-size: 28px;">{{ ui_value('web-setting', 'title') }}</h3>
                @if($companyAbout)
                    <div>{!! $companyAbout !!}</div>
                @else
                    <p>Kami menghadirkan layanan appraisal profesional dan terpercaya.</p>
                @endif
            </div>

            <div class="d-flex flex-column gap-3">
                @if($companyVision)
                <div class="info-card info-card--light">
                    <p class="section-label">Our Vision</p>
                    <p class="fw-semibold mb-0">{{ $companyVision }}</p>
                </div>
                @endif

                @if($companyMission)
                <div class="info-card info-card--light">
                    <p class="section-label">Our Mission</p>
                    <div>{!! $companyMission !!}</div>
                </div>
                @endif
            </div>

            <div class="network-card">
                <div class="d-flex align-items-center justify-content-between" style="margin-bottom: 24px;">
                    <div>
                        <p class="section-label" style="color: #94a3b8;">Our Addresses</p>
                        <h5 class="fw-semibold mb-0" style="font-size: 18px;">Jaringan Kantor</h5>
                    </div>
                    <span class="badge bg-primary-100 text-primary-600" style="font-size: 12px;">{{ count($branches) }} lokasi</span>
                </div>

                <div class="d-flex flex-column" style="gap: 18px;">
                    @foreach ($branches as $branch)
                    <div class="branch-card">
                        <div class="branch-card__title">
                            <span>{{ $branch['label'] }}</span>
                            <span class="badge">{{ sprintf('#%02d', $loop->iteration) }}</span>
                        </div>
                        <p style="font-size: 13px; line-height: 1.6; margin-bottom: 12px;">{{ $branch['address'] }}</p>
                        <div class="branch-card__meta">
                            @if(!empty($branch['phone']))
                            <div class="d-flex align-items-center gap-2">
                                <iconify-icon icon="mdi:phone" class="text-primary-200"></iconify-icon>
                                <span>{{ $branch['phone'] }}</span>
                            </div>
                            @endif
                            @if(!empty($branch['email']))
                            <div class="d-flex align-items-center gap-2">
                                <iconify-icon icon="mdi:email-outline" class="text-primary-200"></iconify-icon>
                                <span>{{ $branch['email'] }}</span>
                            </div>
                            @endif
                            @if(!empty($branch['website'] ?? null))
                            <div class="d-flex align-items-center gap-2">
                                <iconify-icon icon="mdi:web" class="text-primary-200"></iconify-icon>
                                <span>{{ $branch['website'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="auth-footer" style="background: transparent; padding-top: 16px;">
                <span>&copy; {{ date('Y') }} {{ ui_value('web-setting', 'copyright') }}</span>
            </div>
        </div>

        <div class="auth-right px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <h4 class="mb-12 mt-12 text-center">{{ ui_value('web-setting', 'title') }}</h4>
                    <p class="text-secondary text-center">Please login to continue</p>
                </div>
                
    

                <form action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    <div class="icon-field mb-16">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>
                        <input type="text" 
                            name="login" 
                            class="form-control h-54-px bg-neutral-50 radius-12"
                            placeholder="Email atau Username" 
                            value="{{ old('login') }}" 
                            required 
                            autofocus>
                        @error('login')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="position-relative mb-20">
                        <div class="icon-field">
                            <span class="icon top-50 translate-middle-y">
                                <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            </span>
                            <input type="password" 
                                name="password" 
                                class="form-control h-54-px bg-neutral-50 radius-12"
                                id="your-password" 
                                placeholder="Password" 
                                required>
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                            onclick="togglePassword()"></span>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between gap-2">
                            <a href="{{route('admin.password.request')}}" class="text-primary-600 fw-semibold">Forgot Password?</a>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary text-sm btn-sm px-10 py-14 w-100 radius-12 mt-16">
                        Login
                    </button>

                </form>
            </div>
        </div>
    </section>

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