@props(['sliders'])


<!-- Banner -->
<div class="owl-carousel owl-theme banner-carousel">
    @foreach ($sliders as $slide)
    <section class="banner3-con position-relative"
        style="background-image: url('{{ $slide->image_url }}');">
        <div class="container position-relative">
            <div class="banner-slide">
                <div class="row">
                    <div class="col-lg-10 col-12 mx-auto">
                        <div class="banner_content text-center">
                            <h3 class="text-white">
                                {{$slide->label}}
                            </h3>
                            <h1 class="text-white">
                                {{$slide->title}}
                            </h1>
                            <p class="text-white text-size-18">
                                {{$slide->description}}
                            </p>
                            <a href="https://wa.me/{{ui_value('contact','whatsapp1')}}?text=Halo%20saya%20mau%20booking" class="text-decoration-none all_button" target="_blank">
                                Booking Sekarang <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endforeach
</div>

@push('scripts')
<script>
    $(document).ready(function(){
        $(".banner-carousel").owlCarousel({
            items: 1,
            loop: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            nav: true,
            dots: true,
            smartSpeed: 600
        });
    });
</script>
@endpush


@push('styles')
<style>
    .banner3-con::before {
        content: none !important;
        /* matikan background ::before lama */
    }

    .banner3-con {
        min-height: 100vh;
        max-height: 100vh;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: center;
    }

    .banner-carousel .owl-nav {
        display: block !important;
        margin-top: 0 !important;
        position: absolute;
        width: 100%;
        top: 50%;
        transform: translateY(-50%);
    }

    .banner-carousel .owl-prev,
    .banner-carousel .owl-next {
        font-size: 18px !important;
        width: 57px;
        height: 57px;
        line-height: 57px !important;
        border-radius: 100% !important;
        position: absolute;
        text-align: center;
        color: var(--e-global-color-white) !important;
        background-color: transparent !important;
        border: 1px solid var(--e-global-color-white) !important;
        transition: all 0.3s ease-in-out;
        opacity: 1;
    }

    .banner-carousel .owl-prev {
        left: 50px;
    }

    .banner-carousel .owl-next {
        right: 50px;
    }

    .banner-carousel .owl-prev:hover,
    .banner-carousel .owl-next:hover {
        color: var(--e-global-color-white) !important;
        background-color: var(--e-global-color-accent) !important;
        border: 1px solid var(--e-global-color-accent) !important;
    }

    /* Pagination dots styling */
    .banner-carousel .owl-dots {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
    }

    .banner-carousel .owl-dot {
        display: inline-block;
        margin: 0 5px;
    }

    .banner-carousel .owl-dot span {
        width: 12px;
        height: 12px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        display: block;
        transition: all 0.3s ease;
    }

    .banner-carousel .owl-dot.active span {
        background: var(--e-global-color-white);
        width: 30px;
        border-radius: 10px;
    }

    .banner-carousel .owl-dot:hover span {
        background: var(--e-global-color-white);
    }
</style>

@endpush