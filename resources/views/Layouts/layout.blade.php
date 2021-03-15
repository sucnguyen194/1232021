<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="content-language" content="vn">
  <meta charset="utf-8">
  <title>@yield('title')</title>
  <meta name="keywords" content="@yield('keywords')"/>
  <meta name="description" content="@yield('description')"/>
  <meta property="og:url" content="@yield('url')" />
  <meta property="og:title" content="@yield('title')" />
  <meta property="og:locale" content="vi_VN" />
  <meta property="og:type" content="website" />
{{--  <meta property="fb:app_id" content="{{optional($setting)->facebook_app_id}}" />--}}
  <meta property="og:description" content="@yield('description')" />
  <meta property="og:image" content="@yield('image')" />
  <meta property="og:image:type" content="image/jpeg" />
  <meta property="og:image:width" content="400" />
  <meta property="og:image:height" content="300" />
  <meta property="og:image:alt" content="@yield('title')" />
  <meta property="og:site_name" content="@yield('title')" />
  <meta name="twitter:card" content="summary"/>
  <meta name="twitter:description" content="@yield('description')"/>
  <meta name="twitter:title" content="@yield('title')"/>
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="@yield('url')">
  <link rel="icon" href="{{asset(optional($setting)->favicon)}}">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
  <!--*************************---->
  <base href="{{route('home')}}">
    <link href="/admin/assets/libs/jquery-toast/jquery.toast.min.css" rel="stylesheet" type="text/css" />
  <!-- Latest compiled and minified CSS & JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.js" integrity="sha256-DrT5NfxfbHvMHux31Lkhxg42LY6of8TaYyK50jnxRnM=" crossorigin="anonymous"></script>

  <!--*********************************---->
    <!-- load stylesheets -->

  <!--*********************************---->
    {!! @$setting->remarketing_header !!}
</head>
  <body>
    <div id="app-body">
        @include('Layouts.header')
        @yield('content')
        @include('Layouts.footer')
    </div>
    {!! @$setting->remarketing_footer !!}
  </body>
  <!--************START*************---->
<!-- Vendor js -->
<script src="/admin/assets/js/vendor.min.js"></script>
<script src="/admin/assets/libs/jquery-toast/jquery.toast.min.js"></script>

  @include('Errors.note')
  <!--*************************---->
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
<script type="text/javascript">
    let alias = $('.vue-alias').val();
    var app = new Vue({
        el: '#app-body',
        data:{
            alias: alias,
            lang: {
                type: 0,
                id: 0,
                lang:0,
            },
           carts: {
                rowId:0,
               id:0,
               qty: 0,
               price: 0,
               options: {

               }
           }
        },
        methods: {
            changelang:function(lang){
                fetch("{{route('ajax.change.lang',[':alias',':lang'])}}".replace(":alias", this.alias).replace(":lang", lang)).then(function(response){
                    return response.json().then(function(data){
                        window.location.assign(data);
                    })
                })
            }
        },
        watch: {
            alias:function(val){
                this.alias = val;
            }
        },
        computed:{

        }
    })
</script>
<!--*************************---->
@if(@$setting->numbercall)
<!--****STARTACTION CALL*****---->
<div class="action-call">
  <div id="phonering-alo-phoneIcon" class="phonering-alo-phone phonering-alo-green phonering-alo-show">
    <div class="phonering-alo-ph-circle"></div>
    <div class="phonering-alo-ph-circle-fill"></div>
    <div class="phonering-alo-ph-img-circle">
      <a class="pps-btn-img " href="tel:{{@$setting->numbercall}}"> <img src="https://wonderads.vn/themes/default/images/v8TniL3.png" alt="Liên hệ" width="50" class="img-responsive"/> </a>
    </div>
  </div>
</div>
<!--*****END ACTION CALL*****---->
@endif
</html>
