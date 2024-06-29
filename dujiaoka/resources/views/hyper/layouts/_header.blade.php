<head>
    <meta charset="utf-8" />
    <title>{{ isset($page_title) ? $page_title : '' }} | {{ dujiaoka_config_get('title') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Keywords" content="{{ dujiaoka_config_get('keywords') }}">
    <meta name="Description" content="{{ dujiaoka_config_get('description') }}">
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
