<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8" />

<title>@yield('title', ui_value('web-setting','landing_title'))</title>
<meta name="description" content="@yield('description', ui_value('web-setting','description'))">
<meta name="keywords" content="@yield('keywords', ui_value('web-setting','keywords'))">
<meta name="author" content="@yield('author', ui_value('web-setting','title').' Team')">
<meta name="robots" content="@yield('robots', 'index, follow')">


<meta property="og:title" content="@yield('og_title', ui_value('web-setting','title'))">
<meta property="og:description" content="@yield('og_description', ui_value('web-setting','meta_description'))">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:url" content="@yield('og_url', url('/'))">
<meta property="og:image" content="@yield('og_image', asset('storage/'.ui_value('web-setting','icon')))">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('twitter_title', ui_value('web-setting','meta_title'))">
<meta name="twitter:description" content="@yield('twitter_description', ui_value('web-setting','meta_description'))">
<meta name="twitter:image" content="@yield('twitter_image', asset('storage/'.ui_value('web-setting','icon')))">


<link rel="icon" href="@yield('favicon', asset('storage/'.ui_value('web-setting','icon')))" type="image/x-icon">
<link rel="apple-touch-icon" href="@yield('apple_icon', asset('storage/'.ui_value('web-setting','icon')))">
<link rel="shortcut icon" href="@yield('shortcut_icon', asset('storage/'.ui_value('web-setting','icon')))">