<head>
   

    @include('components.page.meta')
    
    <!-- BOOTSTRAP STYLE SHEET -->
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}"> 
     <!-- BOOTSTRAP SLECT BOX STYLE SHEET  -->
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-select.min.css') }}">        
     <!-- FONTAWESOME STYLE SHEET -->
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome/css/font-awesome.min.css') }}" />
     <!-- OWL CAROUSEL STYLE SHEET -->
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/owl.carousel.min.css') }}">
     <!-- MAGNIFIC POPUP STYLE SHEET -->
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/magnific-popup.min.css') }}">   
     <!-- LOADER STYLE SHEET -->
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/loader.min.css') }}">
     <!-- FLATICON STYLE SHEET -->
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flaticon.min.css') }}">    
     <!-- MAIN STYLE SHEET -->
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
     <!-- Price Range Slider -->
     <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-slider.min.css') }}" />    
     <!-- Color Theme Change Css -->
     <link rel="stylesheet" class="skin" type="text/css" href="{{ asset('assets/css/skin/skin-1.css') }}">  
      

        
    <!-- REVOLUTION SLIDER CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/revolution/revolution/css/settings.css') }}">	
    <!-- REVOLUTION NAVIGATION STYLE -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/revolution/revolution/css/navigation.css') }}">
    
    <!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">

    @stack('styles')
</head>