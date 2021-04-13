@extends('Layouts.layout')
@section('title') {{$video->name}} @stop
@section('url') {{route('alias', $video->alias)}} @stop
@section('description') {{$video->description_seo}} @stop
@section('keywords') {{$video->keywords_seo}} @stop
@section('site_name') {{$video->name}}  @stop
@section('image') {{asset($video->image)}} @stop
@section('lang') {{redirect_lang($video->alias)}} @stop
@section('content')
<div class="link cb">
  <div class="container">
    <ul>
      <li><a href="{{url()}}">Trang chủ</a></li>
      <li><a href='video.html' title='Video'>Video</a></li>
      <li><a href='javascript:void(0)' title='{{$video->title}}'>{{$video->title}}</a></li>
    </ul>
  </div>
</div>
<div id="video-list" class="content">
  <div class="container">
    <div class="main cb">
      <div class="item-content-left">
        <div class="main-left">
          @include('frontend.include.left-product')
        </div>
      </div>
      <div class="item-content-right" id="video1-detail">
        <div class="main">
          <div class="item-video1-detail">
            <div class="title-pro cb"> <a class="name" href="video.html">Thư viện video</a> </div>
            <div class="main-gioithieu-detail">
              <h1 class="album-video"> {{$video->title}} </h1>
              <div class="thongke cb">
                <div class="thongke-ngay"> <span>Ngày đăng:
                  {{date('d/m/Y',$video->time)}}</span> </div>
                  <div class="right-res">
                    <div class="thongke-luotxem"> <span>Lượt xem:
                      {{$video->view}}</span> </div>
                      <div class="cochu"> <span>Cỡ chữ</span> <a class="tang-giam giam" title=""> <img src="assets/Css/images/tru.png" onclick="DecreaseTextSize()" /></a> <a class="tang-giam tang" title=""> <img src="assets/Css/images/cong.png" onclick="IncreaseTextSize()" /></a> </div>
                    </div>
                  </div>
                  <div class="cb"></div>
                  <div class="text-gioithieu TextSize">
                    {!!$video->content!!}
                    <div class="khungAnh"> <a class="khungAnhCrop" href="#" title="">
                      <iframe width='420' height='345' src='http://www.youtube.com/embed/{{$video->video}}?rel=0&autoplay=1&cc_load_policy=1&wmode=transparent&enablejsapi=1' frameborder='0' allowfullscreen></iframe>
                    </a> </div>
                  </div>

                  <div class="cb"></div>
                </div>
                <div class="cb"></div>
                <div class="gioithieu-khac cb">
                  <h2 class="title"> Các video khác </h2>
                  <div class="list-gt-khac item-images-home cb">
                    @foreach($aOthers as $item)
                    <div class='item-img'>
                      <div class='khungAnh'> <a class='khungAnhCrop' href='{{$item->alias}}' title='{{$item->title}}'> <img src="{{$item->thumb}}" class=""/> </a> <a class='icon' href='{{$item->alias}}'><img src='assets/Css/images/icon-video.png' alt='img'></a> </div>
                      <div class='text'> <a href='{{$item->alias}}'>
                        <h3>{{$item->title}}</h3>
                      </a> </div>
                    </div>
                    @endforeach
                    <div class="cb"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-------------------------->
    <!-----------SOURCSE----------->
    <!-------------------------->
    <input type="hidden" id="getDataLang" data-id="{{$video->id}}" data-type="video">
    @stop
