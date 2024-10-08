<!DOCTYPE html>
<html lang="{{ str_replace('_','-',strtolower(app()->getLocale())) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="applicable-device" content="pc,mobile">
    <title>{{ isset($page_title) ? $page_title : '' }} | {{ dujiaoka_config_get('title') }}</title>
    <meta name="keywords" content="{{ $gd_keywords }}">
    <meta name="description" content="{{ $gd_description }}">
    <meta property="og:type" content="article">
    <meta property="og:image" content="{{ $picture }}">
    <meta property="og:title" content="{{ isset($page_title) ? $page_title : '' }}">
    <meta property="og:description" content="{{ $gd_description }}">    
    <meta property="og:release_date" content="{{ $updated_at }}">
    @if(\request()->getScheme() == "https")
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif
    <link rel="shortcut icon" href="/favicon.ico">
    <link href="/assets/hyper/css/vendor/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css">
    <link href="/assets/hyper/css/icons.min.css" rel="stylesheet" type="text/css">
    <link href="/assets/hyper/css/app-creative.min.css" rel="stylesheet" type="text/css" id="light-style">
    <link href="/assets/hyper/css/hyper.css?v=045256" rel="stylesheet" type="text/css">
    <script>
        function getTopLevelDomain() {
	        const url = window.location.href;
	        const matches = url.match(/^(?:https?:\/\/)?(?:[^@\n]+@)?(?:www\.)?([^:\/\n?]+)/img);
	        if (matches && matches.length > 0) {
		        const domain = matches[0].replace(/^https?:\/\//, '').replace(/^www\./, '');
		        const parts = domain.split('.');
		        const ipPattern = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/; // 匹配IP地址的正则表达式
		        if (ipPattern.test(domain)) {
			        return domain; // 如果是IP地址，直接返回
		        } else if (parts.length > 2) {
			        return parts.slice(-2).join('.');
		        } else {
			        return domain;
		        }
	        }
	        return null;
        }
        function getUrlProtocol() {
	        const url = window.location.href;
	        const matches = url.match(/^(https?):\/\//);
	        return matches ? matches[1] : null;
        }
        const TOP_LEVEL_DOMAIN = getTopLevelDomain()
        const URL_PROTOCOL = getUrlProtocol()
    </script>
</head>
<body data-layout="topnav">
    <div class="wrapper">
        <div class="content-page">
            <div class="content">
                @include('hyper.layouts._nav')
                <div class="container">
                    @yield('content')
                </div>
            </div><!-- content -->
            @include('hyper.layouts._footer')
        </div><!-- content-page -->
    </div><!-- wrapper -->
    @include('hyper.layouts._script')
    @section('js')
    @show
</body>
</html>