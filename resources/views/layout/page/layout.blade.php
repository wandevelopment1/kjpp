<!DOCTYPE html>
<html lang="id">



@include('components.page.head')
<!-- page wrapper -->

<body>
  <div class="page-wraper">
    @include('components.page.header')
    @yield('content')
    @include('components.page.footer')
  </div>
  @include('components.page.back-to-top')
  @include('components.page.preload')
</body><!-- End of .page_wrapper -->


@include('components.page.script')

</html>