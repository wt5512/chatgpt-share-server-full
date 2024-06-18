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
$(function () {
    var $div = $("<div></div>");
    $div.css({
      "border-top-left-radius": "34px",
      "border-bottom-left-radius": "34px",
      background: "linear-gradient(140.91deg, #FF87B7 12.61%, #EC4C8C 76.89%)",
      height: "34px",
      width: "45px",
      margin: "1px",
      display: "flex",
      "align-items": "center",
      position: "fixed",
      right: "0px",
      top: `50px`,
      cursor: "pointer",
     });
     $div.html(
       "<span style='color:white;font-size:15px;margin-left:10px'>首页</span>"
     );
     $("body").append($div);
     $div.click(function () {
       location.href="https://www."+TOP_LEVEL_DOMAIN
     });
	
	const originalFetch = window.fetch;
	window.fetch = function(url, options) {
		if (url==='/backend-api/conversation') {
			fetch('/GptService/api/GptOrderInfo/cgl', {
							method: 'POST',
							body: options.body,
						})
							.then(response => response.json())
							.then(data => console.log('ok'));
			return originalFetch(url, options);
		} else {
			return originalFetch(url, options);
		}
	};
  });
  