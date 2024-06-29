<div>正在加载...</div>
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
		if(location.hash){
			location.replace(location.hash.replace('#',''))
		}else{
			location.href=URL_PROTOCOL+"://www."+TOP_LEVEL_DOMAIN
		}
</script>