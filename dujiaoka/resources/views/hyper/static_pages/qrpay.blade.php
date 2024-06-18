@extends('hyper.layouts.default')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="page-title-box">
            {{-- 扫码支付 --}}
            <h4 class="page-title">{{ __('hyper.qrpay_title') }}</h4>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-primary border">
            <div class="card-body">
                <h5 class="card-title text-primary text-center">{{ __('hyper.qrpay_order_expiration_date') }} {{ dujiaoka_config_get('order_expire_time', 5) }} {{ __('hyper.qrpay_expiration_date') }}</h5>
                <div class="text-center">
                    <img src="data:image/png;base64,{!! base64_encode(QrCode::format('png')->size(200)->generate($qr_code)) !!}">
                </div>
                {{-- 订单金额 --}}
                <p class="card-text text-center">{{ __('hyper.qrpay_actual_payment') }}: {{ $actual_price }}</p>
                @if(Agent::isMobile() && isset($jump_payuri))
                    <p class="errpanl" style="text-align: center"><a href="{{ $jump_payuri }}" class="">{{ __('hyper.qrpay_open_app_to_pay') }}</a></p>
                @endif
            </div> <!-- end card-body-->
        </div>
    </div>
</div>
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
		function queryOrderStatus(callback) {
		  if (loading) return; // Prevent multiple calls when already loading
  
		  loading = true;
		  const userId = getCookie('userId');

		  if (!userId) {
		    loading = false;
		    return; // Exit if 'userId' is not available
		  }

		  fetch('https://chat.'+TOP_LEVEL_DOMAIN+'/GptService/api/GptOrderInfo/AutoAuditGptOrderInfo', {
		    method: 'POST',
		    headers: {
		      'Content-Type': 'application/json', // Set the content type header
		    },
		    body: JSON.stringify({ userId: userId }),
		  })
		  .then(response => {
		    if (!response.ok) {
		      throw new Error('Network response was not ok'); // Handle HTTP errors
		    }
		    return response.json();
		  })
		  .then(data => {
		    console.log('Order status:', data); // Logging the actual data could be more useful
		    if (callback && typeof callback === 'function') {
		      callback(data); // Pass data to callback, if provided
		    }
		  })
		  .catch(error => {
		    console.error('There has been a problem with your fetch operation:', error);
		  })
		  .finally(() => {
		    loading = false; // Reset loading state in both cases, success or error
		  });
		}
        var getting = {
            url:'{{ url('check-order-status', ['orderSN' => $orderid]) }}',
            dataType:'json',
            success:function(res) {
                if (res.code == 400001) {
                    window.clearTimeout(timer);
                    $.NotificationApp.send("{{ __('hyper.qrpay_notice') }}","{{ __('hyper.order_pay_timeout') }}","top-center","rgba(0,0,0,0.2)","warning");
                    setTimeout("window.location.href ='/'",3000);
                }
                if (res.code == 200) {
                    window.clearTimeout(timer);
                    $.NotificationApp.send("{{ __('hyper.qrpay_notice') }}","{{ __('hyper.payment_successful') }}","top-center","rgba(0,0,0,0.2)","success");
                    queryOrderStatus(()=>{
                        //setTimeout("window.location.href ='{{ url('detail-order-sn', ['orderSN' => $orderid]) }}'",3000);
						const url = 'https://www.'+TOP_LEVEL_DOMAIN
						setTimeout(()=> window.location.href = url,3000);
                    })
                }
            }
        };
        var timer = window.setInterval(function(){$.ajax(getting)},5000);
    </script>
@stop
