<!--==================================================-->
<!-- Start Breadcumb Area -->
<!--==================================================-->
<div class="sx-bnr-inr overlay-wraper bg-parallax bg-top-center" data-stellar-background-ratio="0.5" style="background-image:url({{ asset('storage/'.ui_value('breadcrumb','image')) }});">
	<div class="overlay-main bg-black opacity-07"></div>
	<div class="container">
		<div class="sx-bnr-inr-entry">
			<div class="banner-title-outer">
				<div class="banner-title-name">
					<h2 class="m-tb0">{{ $title ?? 'Untitled' }}</h2>
				</div>
			</div>
			<!-- BREADCRUMB ROW -->
			<div>
				<ul class="sx-breadcrumb breadcrumb-style-2">
					<li>
						<a href="{{ route('home.index') }}">
							{{-- <img src="{{ asset('storage/'.ui_value('web-setting','icon')) }}" alt="" style="height: 15px"> --}}
							Beranda
						</a>
					</li>

					@foreach($items ?? [] as $item)
						<li>
							@if(!empty($item['url']))
								<a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
							@else
								{{ $item['label'] }}
							@endif
						</li>
					@endforeach
				</ul>
			</div>
			<!-- BREADCRUMB ROW END -->
		</div>
	</div>
</div>
<!--==================================================-->
<!-- End Breadcumb Area -->
<!--==================================================-->
