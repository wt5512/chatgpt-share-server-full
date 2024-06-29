<div class="header-navbar">
    <div class="container header-flex">
        <!-- LOGO -->
        <a href="javascript:void(0);" onclick="window.location.href =URL_PROTOCOL+'://www.'+TOP_LEVEL_DOMAIN" class="topnav-logo" style="float: none;">
            <img src="{{ picture_ulr(dujiaoka_config_get('img_logo')) }}" height="36">
            <div class="logo-title">{{ dujiaoka_config_get('text_logo') }}</div>
        </a>
       <div class="menu-container">
		<a class="btn head-menu" href="javascript:void(0);" onclick="window.location.href =URL_PROTOCOL+'://www.'+TOP_LEVEL_DOMAIN">
            <i class="uil-home"></i>
            首页
        </a>
        <a class="btn head-menu" href="{{ url('order-search') }}">
            <i class="uil-file-search-alt"></i>
            查询订单
        </a>
      </div>
    </div>
</div>