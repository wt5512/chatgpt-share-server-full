<div>正在加载...</div>
<script>
	function getTopLevelDomain() {
		const url = window.location.href
		const matches = url.match(/^(?:https?:\/\/)?(?:[^@\n]+@)?(?:www\.)?([^:\/\n?]+)/img)
		if (matches && matches.length > 0) {
			const domain = matches[0].replace(/^https?:\/\//, '').replace(/^www\./, '')
			const parts = domain.split('.')
			if (parts.length > 2) {
				return parts.slice(-2).join('.')
			} else {
				return domain
			}
		}
		return null
	}
	const TOP_LEVEL_DOMAIN = getTopLevelDomain()
	if(location.hash){
		location.replace(location.hash.replace('#',''))
	}else{
		location.href="https://www."+TOP_LEVEL_DOMAIN
	}
</script>