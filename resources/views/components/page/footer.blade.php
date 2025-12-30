<!-- FOOTER START -->
<footer class="site-footer footer-large  footer-dark	footer-wide">

	<!-- FOOTER BLOCKES START -->
	<div class="footer-top overlay-wraper">
		<div class="overlay-main"></div>
		<div class="container">
			<div class="row">
				<!-- ABOUT COMPANY -->
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="widget widget_about">
						<!--<h4 class="widget-title">About Company</h4>-->
						<div class="logo-footer clearfix p-b15">
							<a href="{{route('home.index')}}"><img src="{{ asset('storage/' . ui_value('web-setting', 'logo_white')) }}" alt=""></a>
						</div>
						<p>{{ ui_value('web-setting', 'description') }}</p>

						<ul class="social-icons  sx-social-links">
							@if(ui_value('social-link', 'facebook'))
							<li><a href="{{ ui_value('social-link', 'facebook') }}"><i class="fa fa-facebook"></i></a>
							</li>
							@endif
							@if(ui_value('social-link', 'twitter'))
							<li><a href="{{ ui_value('social-link', 'twitter') }}"><i class="fa fa-twitter"></i></a>
							</li>
							@endif
							@if(ui_value('social-link', 'instagram'))
							<li><a href="{{ ui_value('social-link', 'instagram') }}"><i class="fa fa-instagram"></i></a>
							</li>
							@endif
							@if(ui_value('social-link', 'linkedin'))
							<li><a href="{{ ui_value('social-link', 'linkedin') }}"><i class="fa fa-linkedin"></i></a>
							</li>
							@endif
							@if(ui_value('social-link', 'youtube'))
							<li><a href="{{ ui_value('social-link', 'youtube') }}"><i class="fa fa-youtube"></i></a>
							</li>
							@endif
							@if(ui_value('social-link', 'tiktok'))
							<li>
								<a href="{{ ui_value('social-link', 'tiktok') }}">
									<svg fill="currentColor" width="16" height="16" viewBox="0 0 32 32"
										xmlns="http://www.w3.org/2000/svg">
										<title>tiktok</title>
										<path
											d="M16.656 1.029c1.637-0.025 3.262-0.012 4.886-0.025 0.054 2.031 0.878 3.859 2.189 5.213l-0.002-0.002c1.411 1.271 3.247 2.095
										 5.271 2.235l0.028 0.002v5.036c-1.912-0.048-3.71-0.489-5.331-1.247l0.082 0.034c-0.784-0.377-1.447-0.764-2.077-1.196l0.052 0.034c-0.012
										  3.649 0.012 7.298-0.025 10.934-0.103 1.853-0.719 3.543-1.707 4.954l0.020-0.031c-1.652 2.366-4.328 3.919-7.371 4.011l-0.014 0c-0.123
										   0.006-0.268 0.009-0.414 0.009-1.73 0-3.347-0.482-4.725-1.319l0.040 0.023c-2.508-1.509-4.238-4.091-4.558-7.094l-0.004-0.041c-0.025-0.625-0.037-1.25-0.012-1.862
										    0.49-4.779 4.494-8.476 9.361-8.476 0.547 0 1.083 0.047 1.604 0.136l-0.056-0.008c0.025 1.849-0.050 3.699-0.050 5.548-0.423-0.153-0.911-0.242-1.42-0.242-1.868 0-3.457
											 1.194-4.045 2.861l-0.009 0.030c-0.133 0.427-0.21 0.918-0.21 1.426 0 0.206 0.013 0.41 0.037 0.61l-0.002-0.024c0.332 2.046 2.086 3.59 4.201 3.59 0.061 0 0.121-0.001
											  0.181-0.004l-0.009 0c1.463-0.044
											  2.733-0.831 3.451-1.994l0.010-0.018c0.267-0.372 0.45-0.822 0.511-1.311l0.001-0.014c0.125-2.237 0.075-4.461 0.087-6.698 0.012-5.036-0.012-10.060 0.025-15.083z">
										</path>
									</svg>
								</a>
							</li>
							@endif
							@if(ui_value('social-link', 'pinterest'))
							<li><a href="{{ ui_value('social-link', 'pinterest') }}"><i class="fa fa-pinterest"></i></a>
							</li>
							@endif
							@if(ui_value('social-link', 'telegram'))
							<li><a href="{{ ui_value('social-link', 'telegram') }}"><i
										class="fa fa-paper-plane"></i></a></li>
							@endif
						</ul>
					</div>
				</div>

				<!-- RESENT POST -->
				<div class="col-lg-3 col-md-6 col-sm-6">
					@php
						$fPost = \App\Models\Post::query()
							->latest()
							->with(['user'])
							->published()
							->limit(3)
							->get();
					@endphp
					<div class="widget recent-posts-entry-date">
						<h5 class="widget-title">Artikel Terbaru</h5>
						<div class="widget-post-bx">

							@foreach ($fPost as $item)
								
							<div class="widget-post clearfix">
								<div class="sx-post-date text-center text-uppercase text-white">
									<strong class="p-date">{{ $item->created_at->format('d') }}</strong>
									<span class="p-month">{{ $item->created_at->format('M') }}</span>
									<span class="p-year">{{ $item->created_at->format('Y') }}</span>
								</div>
								<div class="sx-post-info">
									<div class="sx-post-header">
										<h6 class="post-title"><a href="{{ route('blog.show', $item->slug) }}">{{ Str::limit($item->title, 50) }}</a>
										</h6>
									</div>
									<div class="sx-post-meta">
										<ul>
											<li class="post-author"><i class="fa fa-user"></i>By {{ $item->user->name }}</li>
										</ul>
									</div>
								</div>
							</div>
							
							@endforeach
						</div>
					</div>
				</div>

				<!-- USEFUL LINKS -->
				<div class="col-lg-3 col-md-6 col-sm-6 footer-col-3">
					<div class="widget widget_services inline-links">
						<h5 class="widget-title">Link Cepat</h5>
						<ul>
							@php
							$navItems = [
							['title' => 'Tentang Kami', 'url' => route('about.index')],
							['title' => 'Produk', 'url' => route('product.index')],
							['title' => 'Galeri', 'url' => route('gallery.index')],
							['title' => 'Produk', 'url' => route('product.index')],
							['title' => 'Artikel', 'url' => route('blog.index')],
							['title' => 'Kontak Kami', 'url' => route('contact.index')],
							];
							@endphp
							@foreach ($navItems as $item)
							<li><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
							@endforeach
						</ul>
					</div>
				</div>

				<!-- CONTACT US -->
				<div class="col-lg-3 col-md-6 col-sm-6">
					<div class="widget widget_address_outer">
						<h5 class="widget-title">Kontak Kami</h5>
						<ul class="widget_address">
							<li>{{ ui_value('contact-info', 'address') }}</li>
							<li>{{ ui_value('contact-info', 'email1') }}</li>
							<li>{{ ui_value('contact-info', 'phone1') }}</li>
							<li>{{ ui_value('contact-info', 'whatsapp1') }}</li>
						</ul>

					</div>
				</div>



			</div>
		</div>
	</div>
	<!-- FOOTER COPYRIGHT -->
	<div class="footer-bottom overlay-wraper">
		<div class="overlay-main"></div>
		<div class="container">
			<div class="row">
				<div class="sx-footer-bot-left">
					<span class="copyrights-text">© {{ date('Y') }} {{ ui_value('web-setting', 'copyright') }}</span>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- FOOTER END -->