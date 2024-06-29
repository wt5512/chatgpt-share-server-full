@extends('hyper.layouts.seo')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            {{-- 产品详细信息 --}}
            <h4 class="page-title">{{ __('hyper.buy_title') }}</h4>
        </div>
    </div>
</div>
<div class="buy-grid">
    <div class="buy-shop hyper-sm-last">
        <div class="card card-body sticky">
            <form id="buy-form" action="{{ url('create-order') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <h3>
                        {{-- 商品名称 --}}
                        {{ $gd_name }}
                    </h3>
                </div>
                <div class="form-group">
                    @if($type == \App\Models\Goods::AUTOMATIC_DELIVERY)
                        {{-- 自动发货 --}}
                        <span class="badge badge-outline-primary">{{ __('hyper.buy_automatic_delivery') }}</span>
                    @else
                        {{-- 人工发货 --}}
                        <span class="badge badge-outline-danger">{{ __('hyper.buy_charge') }}</span>
                    @endif
                    {{-- 库存 --}}
                    <span class="badge badge-outline-primary">{{ __('hyper.buy_in_stock') }}({{ $in_stock }})</span>
                    @if($buy_limit_num > 0)
                        <span class="badge badge-outline-dark"> {{__('hyper.buy_purchase_restrictions')}}({{ $buy_limit_num }})</span>
                    @endif
                </div>
                @if(!empty($wholesale_price_cnf) && is_array($wholesale_price_cnf))
                    <div class="form-group">
                        <div class="alert alert-dark bg-white text-dark mb-0" role="alert">
                            {{-- 批发优惠 --}}
                            @foreach($wholesale_price_cnf as $ws)
                                {{-- 购买 x 件起， x 元/件 --}}
                                <span>
                                    {{ __('hyper.buy_purchase') }} {{ $ws['number'] }} {{__('hyper.buy_the_above')}}，{{ $ws['price']  }} {{__('hyper.buy_each')}}。
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="form-group">
                    <!--<div class="buy-title">{{ __('hyper.buy_price') }}</div>-->
                    <h3>
                        {{-- 价格 --}}
                        <span class="buy-price">{{ __('hyper.global_currency') }} {{ $actual_price }}</span>
                        {{-- 原价 --}}
                        <small><del>¥ {{ $retail_price }}</del></small>
                    </h3>
                </div>
                <div class="form-group">
                    {{-- 电子邮箱 --}}
                    <div class="buy-title">UserId</div>
                    <input type="hidden" name="gid" value="{{ $id }}">
                    {{-- 接收卡密或通知 --}}
                    <input type="hidden" name="email" class="form-control" placeholder="{{ __('hyper.buy_input_account') }}">
					<input type="text" class="form-control" name="UserId">
                </div>
                <div class="form-group">
                    {{-- 购买数量 --}}
                    <div class="buy-title">{{ __('hyper.buy_purchase_quantity') }}</div>
                    <div class="input-group">
                        <input data-toggle="touchspin" type="text" name="by_amount" value="1" data-bts-max="999">
                    </div>
                </div>
                @if(dujiaoka_config_get('is_open_search_pwd') == \App\Models\Goods::STATUS_OPEN)
                <div class="form-group">
                    {{-- 查询密码 --}}
                    <div class="buy-title">{{ __('hyper.buy_search_password') }}</div>
                    {{-- 查询订单密码 --}}
                    <input type="text" name="search_pwd" value="" class="form-control" placeholder="{{ __('hyper.buy_input_search_password') }}">
                </div>
                @endif
                @if(isset($open_coupon))
                    <div class="form-group">
                        {{-- 优惠码 --}}
                        <div class="buy-title">{{ __('hyper.buy_promo_code') }}</div>
                        {{-- 您有优惠码吗？ --}}
                        <input type="text" name="coupon_code" class="form-control" placeholder="{{ __('hyper.buy_input_promo_code') }}">
                    </div>
                @endif
                @if($type == \App\Models\Goods::MANUAL_PROCESSING && is_array($other_ipu))
                    @foreach($other_ipu as $ipu)
                        <div class="form-group">
                            <div class="buy-title">{{ $ipu['desc'] }}</div>
                            <input type="text" name="{{ $ipu['field'] }}" @if($ipu['rule'] !== false) required @endif class="form-control" placeholder="{{ $ipu['placeholder'] }}">
                        </div>
                    @endforeach
                @endif
                @if(dujiaoka_config_get('is_open_geetest') == \App\Models\Goods::STATUS_OPEN )
                    <div class="form-group">
                        {{-- 极验证 --}}
                        <div class="buy-title">{{ __('hyper.buy_behavior_verification') }}</div>
                        <div id="geetest-captcha"></div>
                        <p id="wait-geetest-captcha" class="show">loading...</p>
                    </div>
                @endif
                @if(dujiaoka_config_get('is_open_img_code') == \App\Models\Goods::STATUS_OPEN)
                    {{-- 图形验证码 --}}
                    <div class="form-group">
                        <div class="buy-title">{{ __('hyper.buy_verify_code') }}</div>
                        <div class="input-group">
                            <input type="text" name="img_verify_code" value="" class="form-control" placeholder="{{ __('hyper.buy_verify_code') }}">
                            <div class="input-group-append">
                                <div class="buy-captcha">
                                    <img class="captcha-img"  src="{{ captcha_src('buy') . time() }}" onclick="refresh()" style="cursor: pointer;">
                                </div>
                            </div>
                        </div>
                        <script>
                            function refresh(){
                                $('img[class="captcha-img"]').attr('src','{{ captcha_src('buy') }}'+Math.random());
                            }
                        </script>
                    </div>
                @endif
                <div class="form-group">
                    {{-- 支付方式 --}}
                    <div class="buy-title">{{ __('hyper.buy_payment_method') }}</div>
                    <div class="input-group">
                        <input type="hidden" name="payway" value="{{ $payways[0]['id'] ?? 0 }}">
                        <div class="pay-grid">
                        @foreach($payways as $key => $way)
                            <div class="btn pay-type @if($key == 0) active @endif"
                                         data-type="{{ $way['pay_check'] }}" data-id="{{ $way['id'] }}" data-name="{{ $way['pay_name'] }}">
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    {{-- 提交订单 --}}
                    <button type="submit" class="btn btn-danger" id="submitBtn">
                        <i class="mdi mdi-truck-fast mr-1"></i>
                            {{ __('hyper.buy_order_now') }}
                    </button>
                </div>
				<div class="mt-2 text-center" style="color:red;">
				支付成功后自动发货
                </div>
            </form>
        </div> <!-- end card-->
    </div>
    <div class="card card-body buy-product">
        {{-- 商品详情 --}}
        <h5 class="card-title">{{ __('hyper.buy_product_desciption') }}</h5>
        {!! $description !!}
    </div>
</div>
<div class="modal fade" id="buy_prompt" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                {{-- 购买提示 --}}
                <h5 class="modal-title" id="myCenterModalLabel">{{ __('hyper.buy_purchase_tips') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                {!! $buy_prompt !!}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="img-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: none;">
        <img id="img-zoom" style="border-radius: 5px;">
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop
@section('js')
<script>
	function getCookie(name) {
		var nameEQ = name + "=";
		var cookies = document.cookie.split(';');
		for(var i = 0; i < cookies.length; i++) {
			var cookie = cookies[i];
			while (cookie.charAt(0) == ' ') {
				cookie = cookie.substring(1, cookie.length);
			}
			if (cookie.indexOf(nameEQ) == 0) {
				return decodeURIComponent(cookie.substring(nameEQ.length, cookie.length));
			}
		}
		return null;
	}
	let loading = false;
	function checkTaobaoOrderId(taobaoOrderId,callback) {
	  if (loading) return; // Prevent multiple calls when already loading

	  loading = true;

	  if (!taobaoOrderId) {
		loading = false;
		return; // Exit if 'userId' is not available
	  }

	  fetch(URL_PROTOCOL+'://chat.'+TOP_LEVEL_DOMAIN+'/GptService/api/GptOrderInfo/CheckTaobaoOrderId?taobaoOrderId='+taobaoOrderId, {
		method: 'GET'
	  })
	  .then(response => {
		if (!response.ok) {
		  throw new Error('Network response was not ok'); // Handle HTTP errors
		}
		return response.json();
	  })
	  .then(data => {
		if (data.code!==0) {
		  alert(data.msg)
		}else{
			if (callback && typeof callback === 'function') {
		      callback(data); // Pass data to callback, if provided
		    }
		}
	  })
	  .catch(error => {
		console.error('There has been a problem with your fetch operation:', error);
	  })
	  .finally(() => {
		loading = false; // Reset loading state in both cases, success or error
	  });
	}
	var userId=getCookie('userId')
    if(!userId){
        alert("请重新进入商品详情")
        location.href=URL_PROTOCOL+'://www.'+TOP_LEVEL_DOMAIN
    }
    $("input[name='email']").val(userId+"@domain.com");
	$("input[name='email']").prop("readonly", true);
	$("input[name='UserId']").val(userId);
	$("input[name='UserId']").prop("readonly", true);
    $('#submitBtn').click(function(){
		if($("input[name='email']").val() == ''){
			{{-- 邮箱不能为空 --}}
			$.NotificationApp.send("{{ __('hyper.buy_warning') }}","{{ __('hyper.buy_empty_mailbox') }}","top-center","rgba(0,0,0,0.2)","info");
			return false;
		}
		if($("input[name='by_amount']").val() == 0 ){
			{{-- 购买数量不能为0 --}}
			$.NotificationApp.send("{{ __('hyper.buy_warning') }}","{{ __('hyper.buy_zero_quantity') }}","top-center","rgba(0,0,0,0.2)","info");
			return false;
		}
		if($("input[name='by_amount']").val() > {{ $in_stock }}){
			{{-- 数量不允许大于库存 --}}
			$.NotificationApp.send("{{ __('hyper.buy_warning') }}","{{ __('hyper.buy_exceeds_stock') }}","top-center","rgba(0,0,0,0.2)","info");
			return false;
		}
		@if($buy_limit_num > 0)
		if($("input[name='by_amount']").val() > {{ $buy_limit_num }}){
			{{-- 已超过限购数量 --}}
			$.NotificationApp.send("{{ __('hyper.buy_warning') }}","{{ __('hyper.buy_exceeds_limit') }}","top-center","rgba(0,0,0,0.2)","info");
			return false;
		}
		@endif
		@if(dujiaoka_config_get('is_open_search_pwd') == \App\Models\Goods::STATUS_OPEN)
		if($("input[name='search_pwd']").val() == 0){
			{{-- 查询密码不能为空 --}}
			$.NotificationApp.send("{{ __('hyper.buy_warning') }}","{{ __('hyper.buy_empty_query_password') }}","top-center","rgba(0,0,0,0.2)","info");
			return false;
		}
		@endif
		@if(dujiaoka_config_get('is_open_img_code') == \App\Models\Goods::STATUS_OPEN)
		if($("input[name='img_verify_code']").val() == ''){
			{{-- 验证码不能为空 --}}
			$.NotificationApp.send("{{ __('hyper.buy_warning') }}","{{ __('hyper.buy_empty_captcha') }}","top-center","rgba(0,0,0,0.2)","info");
			return false;
		}
		@endif
        if($("input[name='tb_order_id']").length>0){
			if($("input[name='tb_order_id']").val() == ''||$("input[name='tb_order_id']").val().trim().length!=19){
				alert('淘宝单号错误，请重新输入！')
				return false;
			}
			let taobaoOrderId = $("input[name='tb_order_id']").val().trim();
            checkTaobaoOrderId(taobaoOrderId,()=>{
				var form = document.getElementById('buy-form');
				form.submit();
			})
			return false;
        }
    });
</script>
<style>
/* 轮播图的基本样式 */
.carousel {
    position: relative;
}

.carousel img {
    width: 100%;
    height: auto;
    display: none; /* 初始时隐藏图片 */
}

/* 导航点的样式 */
.carousel-dots {
    position: absolute;
    left: 50%;
    bottom: 10px;
    transform: translateX(-50%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.dot {
    padding: 5px;
    margin-right: 5px;
    cursor: pointer;
    border-radius: 50%;
    background: gray; /* 非活跃导航点的颜色 */
    transition: background 0.3s;
}

.dot:last-child {
    margin-right: 0;
}

.dot.active {
    background: white; /* 活跃导航点的颜色 */
}
</style>
<script>
// ad
function createCarousel(images, selector) {
    const container = document.querySelector(selector);
    let currentIndex = 0;
    let intervalId;

    // 创建轮播图结构
    const carousel = document.createElement('div');
    carousel.classList.add('carousel');

    // 初始化所有图片，并隐藏
    const imageElements = images.map((img) => {
        const imageElem = document.createElement('img');
        imageElem.src = img.src;
        imageElem.style.display = 'none'; // 初始时隐藏所有图片
        carousel.appendChild(imageElem);
        return imageElem;
    });

    // 创建导航点容器
    const dotsContainer = document.createElement('div');
    dotsContainer.classList.add('carousel-dots');

    // 创建导航点
    const dots = images.map((_, index) => {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        dot.addEventListener('click', () => {
            moveToSlide(index); // 切换到被点击的图片
        });
        dotsContainer.appendChild(dot);
        return dot;
    });

    // 导航点高亮函数
    function highlightDot(index) {
        dots.forEach((dot, idx) => {
            if (idx === index) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }

    // 切换到指定图片并重置定时器
    function moveToSlide(index) {
        clearTimer(); // 停止当前的定时器
        currentIndex = index;
        imageElements.forEach((img, idx) => {
            img.style.display = idx === currentIndex ? 'block' : 'none';
        });
        highlightDot(currentIndex); // 高亮对应的点
        startCarousel(images[currentIndex].time); // 重新开始轮播
    }

    // 添加导航点到轮播图
    carousel.appendChild(dotsContainer);

    // 切换到下一张图片
    function nextSlide() {
        moveToSlide((currentIndex + 1) % images.length);
    }

	// 启动轮播定时器
	function startCarousel(time=3000) {
		clearTimer(); // 清除现有的定时器
		intervalId = setInterval(nextSlide, time); // 设定新的定时器
	}


    // 开始轮播到第一张图片
    moveToSlide(0);

    // 添加到容器中
    container.appendChild(carousel);

    // 清除定时器函数
    function clearTimer() {
		if (intervalId) {
			clearInterval(intervalId); // 清除现有的定时器
		}
    }

    // 监听鼠标移入移出事件，停止和继续轮播
    carousel.addEventListener('mouseover', clearTimer);
    carousel.addEventListener('mouseleave', () => startCarousel(images[currentIndex].time));
}

// 使用示例
const imageUrls = [
    { src: '/ad1.png', time: 5000 },
    { src: '/7be04004-4aed-4158-8394-707bc5e57a06.webp', time: 5000 } 
];

createCarousel(imageUrls, '.buy-product');

</script>
<script>
    @if(!empty($buy_prompt))
        $('#buy_prompt').modal();
    @endif
        $(function() {
        //点击图片放大
        $("#img-zoom").click(function(){
            $('#img-modal').modal("hide");
        });
        $("#img-dialog").click(function(){
            $('#img-modal').modal("hide");
        });
        $(".buy-product img").each(function(i){
            var src = $(this).attr("src");
            $(this).click(function () {
                $("#img-zoom").attr("src", src);
                var oImg = $(this);
                var img = new Image();
                img.src = $(oImg).attr("src");
                var realWidth = img.width;
                var realHeight = img.height;
                var ww = $(window).width();
                var hh = $(window).height();
                $("#img-content").css({"top":0,"left":0,"height":"auto"});
                $("#img-zoom").css({"height":"auto"});
                $("#img-zoom").css({"margin-left":"auto"});
                $("#img-zoom").css({"margin-right":"auto"});
                if((realWidth+20)>ww){
                    $("#img-content").css({"width":"100%"});
                    $("#img-zoom").css({"width":"100%"});
                }else{
                    $("#img-content").css({"width":realWidth+20, "height":realHeight+20});
                    $("#img-zoom").css({"width":realWidth, "height":realHeight});
                }
                if((hh-realHeight-40)>0){
                    $("#img-content").css({"top":(hh-realHeight-40)/2});
                }
                if((ww-realWidth-20)>0){
                    $("#img-content").css({"left":(ww-realWidth-20)/2});
                }
                $('#img-modal').modal();
            });
        });
    });
</script>
@if(dujiaoka_config_get('is_open_geetest') == \App\Models\Goods::STATUS_OPEN )
<script src="https://static.geetest.com/static/tools/gt.js"></script>
<script>
    var geetest = function(url) {
        var handlerEmbed = function(captchaObj) {
            $("#geetest-captcha").closest('form').submit(function(e) {
                var validate = captchaObj.getValidate();
                if (!validate) {
                    $.NotificationApp.send("{{ __('hyper.buy_warning') }}","{{ __('hyper.buy_correct_verification') }}","top-center","rgba(0,0,0,0.2)","info");
                    e.preventDefault();
                }
            });
            captchaObj.appendTo("#geetest-captcha");
            captchaObj.onReady(function() {
                $("#wait-geetest-captcha")[0].className = "d-none";
            });
            captchaObj.onSuccess(function () {$('#geetest-captcha').attr("placeholder",'{{ __('dujiaoka.success_behavior_verification') }}')})

            captchaObj.appendTo("#geetest-captcha");
        };
        $.ajax({
            url: url + "?t=" + (new Date()).getTime(),
            type: "get",
            dataType: "json",
            success: function(data) {
                initGeetest({
                    width: '100%',
                    gt: data.gt,
                    challenge: data.challenge,
                    product: "popup",
                    offline: !data.success,
                    new_captcha: data.new_captcha,
                    lang: '{{ dujiaoka_config_get('language') ?? 'zh_CN' }}',
                    http: '{{ (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://" }}' + '://'
                }, handlerEmbed);
            }
        });
    };
    (function() {
        geetest('{{ '/check-geetest' }}');
    })();
</script>
@endif
@stop
