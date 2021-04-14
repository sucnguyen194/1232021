<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta http-equiv="content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="content-language" content="{{ str_replace('_', '-', app()->getLocale()) }}">
  <meta charset="utf-8">
  <title>@hasSection('title') @yield('title') - @endif {{setting()->name}}</title>
  <meta name="keywords" content="@hasSection('keywords') @yield('keywords') @else {{setting()->keyword_seo}} @endif"/>
  <meta name="description" content="@hasSection('description') @yield('description') @else {{setting()->description_seo}} @endif"/>
  <meta property="og:url" content="@hasSection('url') @yield('url') @else {{url('/')}} @endif" />
  <meta property="og:title" content="@hasSection('title') @yield('title') - @endif {{setting()->name}}" />
  <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
  <meta property="og:type" content="website" />
  <meta property="fb:app_id" content="{{setting()->facebook_app_id}}" />
  <meta property="og:description" content="@hasSection('description') @yield('description') @else {{setting()->description_seo}} @endif" />
  <meta property="og:image" content="@hasSection('image') @yield('image') @else {{setting()->og_image ?? setting()->logo}} @endif" />
  <meta property="og:image:type" content="image/jpeg" />
  <meta property="og:image:width" content="400" />
  <meta property="og:image:height" content="300" />
  <meta property="og:image:alt" content="@hasSection('title') @yield('title') - @endif {{setting()->name}}" />
  <meta property="og:site_name" content="@hasSection('title') @yield('title') - @endif {{setting()->name}}" />
  <meta name="twitter:card" content="summary"/>
  <meta name="twitter:description" content="@hasSection('description') @yield('description') @else {{setting()->description_seo}} @endif"/>
  <meta name="twitter:title" content="@hasSection('title') @yield('title') - @endif {{setting()->name}}"/>
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="@hasSection('url') @yield('url') @else {{url('/')}} @endif">
  <link rel="icon" href="{{asset(setting()->favicon)}}">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
  <!--*************************---->
  <base href="{{route('home')}}">
    <link href="/admin/assets/libs/jquery-toast/jquery.toast.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
  <!-- Latest compiled and minified CSS & JS -->
{{--    <script src="https://code.jquery.com/jquery-3.5.1.slim.js" integrity="sha256-DrT5NfxfbHvMHux31Lkhxg42LY6of8TaYyK50jnxRnM=" crossorigin="anonymous"></script>--}}

  <!--*********************************---->
    <!-- load stylesheets -->

  <!--*********************************---->
    {!! setting()->remarketing_header !!}
</head>
  <body>
    <div id="app-body">
        @include('Layouts.header')
        @yield('content')
        @include('Layouts.footer')
    </div>
    {!! setting()->remarketing_footer !!}
    @yield('lang')
  </body>
  <!--************START*************---->
<!-- Vendor js -->
<script src="/admin/assets/js/vendor.min.js"></script>
<script src="/admin/assets/libs/jquery-toast/jquery.toast.min.js"></script>
<style>
    .qtv {
        display: inline;
        vertical-align: middle;
        font-style: normal;
        background-color: #eebc49;
        color: #222;
        font-size: 10px;
        padding: 5px 5px 3px 5px;
        border-radius: 2px;
        width: auto;
        height: auto;
        line-height: 1;
        margin-left: 5px;
    }
</style>
<script src="/admin/js/cpanel.js"></script>
  @include('Errors.note')
  <!--*************************---->
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
@if(setting()->numbercall)
<!--****STARTACTION CALL*****---->
<div class="action-call">
  <div id="phonering-alo-phoneIcon" class="phonering-alo-phone phonering-alo-green phonering-alo-show">
    <div class="phonering-alo-ph-circle"></div>
    <div class="phonering-alo-ph-circle-fill"></div>
    <div class="phonering-alo-ph-img-circle">
      <a class="pps-btn-img " href="tel:{{setting()->numbercall}}"> <img src="https://wonderads.vn/themes/default/images/v8TniL3.png" alt="Liên hệ" width="50" class="img-responsive"/> </a>
    </div>
  </div>
</div>
<!--*****END ACTION CALL*****---->
@endif
</html>
