@php
    $navItems = [
    [
        'title' => 'Beranda',
        'url'   => route('home.index'),
        'active' => ['home.index'],
    ],
    [
        'title' => 'Tentang Kami',
        'url'   => route('about.index'),
    ],
    [
        'title' => 'Produk',
        'url'   => route('product.index'),
    ],
    [
        'title' => 'Galeri',
        'url'   => route('gallery.index')
    ],
    [
        'title' => 'Artikel',
        'url'   => route('blog.index')
    ],
    [
        'title' => 'Kontak Kami',
        'url'   => route('contact.index')   
    ],
];
@endphp

@if (request()->routeIs('home.index'))
    {{-- HEADER STYLE 2 (khusus Home) --}}
    <header class="site-header header-style-1 nav-wide mobile-sider-drawer-menu">
        <div class="sticky-header main-bar-wraper navbar-expand-lg">
            <div class="main-bar header-left-gray-block bg-white">
                <div class="container clearfix">
                    <div class="logo-header">
                        <div class="logo-header-inner logo-header-one">
                            <a href="{{ route('home.index') }}">
                                <img src="{{ asset('storage/' . ui_value('web-setting', 'logo')) }}" alt="Logo">
                            </a>
                        </div>
                    </div>

                    <button id="mobile-side-drawer" data-target=".header-nav" data-toggle="collapse" type="button" class="navbar-toggler collapsed">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar icon-bar-first"></span>
                        <span class="icon-bar icon-bar-two"></span>
                        <span class="icon-bar icon-bar-three"></span>
                    </button>

                    <div class="extra-nav">
                        <div class="extra-cell">
                            <div class="contact-slide-show">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', ui_value('contact-info','whatsapp1')) }}" 
                                   class="get-in-touch-btn from-top" 
                                   target="_blank" 
                                   rel="noopener noreferrer">
                                    Hubungi Kami
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="header-nav nav-dark navbar-collapse collapse justify-content-start collapse">
                        <ul class="nav navbar-nav">
                            @foreach ($navItems as $item)
                                <li class="{{ request()->is(ltrim(parse_url($item['url'], PHP_URL_PATH), '/')) ? 'active' : '' }}">
                                    <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                                    @if(isset($item['submenu']))
                                        <ul class="sub-menu">
                                            @foreach($item['submenu'] as $sub)
                                                <li><a href="{{ $sub['url'] }}">{{ $sub['title'] }}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
@else
    {{-- HEADER STYLE 1 (selain Home) --}}
    <header class="site-header nav-wide nav-transparent mobile-sider-drawer-menu">
        <div class="sticky-header main-bar-wraper navbar-expand-lg">
            <div class="main-bar">
                <div class="container clearfix">
                    <div class="logo-header">
                        <div class="logo-header-inner logo-header-one">
                            <a href="{{ route('home.index') }}">    
                                <img src="{{ asset('storage/' . ui_value('web-setting', 'logo_white')) }}" alt="Logo">
                            </a>
                        </div>
                    </div>

                    <button id="mobile-side-drawer" data-target=".header-nav" data-toggle="collapse" type="button" class="navbar-toggler collapsed">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar icon-bar-first"></span>
                        <span class="icon-bar icon-bar-two"></span>
                        <span class="icon-bar icon-bar-three"></span>
                    </button>

                    <div class="extra-nav">
                        <div class="extra-cell">
                            <div class="contact-slide-show">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', ui_value('contact-info','whatsapp1')) }}" 
                                   class="get-in-touch-btn from-top" 
                                   style="color: white" 
                                   target="_blank" 
                                   rel="noopener noreferrer">
                                    Hubungi Kami
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="header-nav navbar-collapse collapse justify-content-center collapse">
                        <ul class="nav navbar-nav">
                            @foreach ($navItems as $item)
                                <li class="{{ request()->is(ltrim(parse_url($item['url'], PHP_URL_PATH), '/')) ? 'active' : '' }}">
                                    <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                                    @if(isset($item['submenu']))
                                        <ul class="sub-menu">
                                            @foreach($item['submenu'] as $sub)
                                                <li><a href="{{ $sub['url'] }}">{{ $sub['title'] }}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endif
