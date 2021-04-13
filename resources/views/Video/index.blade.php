@extends('Layouts.layout')
@section('title') Video - {{setting()->name}} @stop
@section('url') {{route('video')}} @stop
@section('description') Video - {{setting()->name}} @stop
@section('keywords') Video - {{setting()->name}} @stop
@section('lang') {{redirect_lang(\App\Enums\AliasType::HOMEVIDEO)}} @stop
@section('content')
<div class="link cb">
  <div class="container">
    <ul>
      <li><a href="{{url()}}">Trang chủ</a></li>
      <li><a href='javascript:void(0)' title='Video'>
        <h1>Video</h1>
      </a></li>
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
      <div class="item-content-right">
        <div class="main">
          <div class="title-pro cb"> <a class="name" href="">Thư viện video</a> </div>
          <div class="item-video-list item-images-home cb">
            @foreach($list_video as $item)
            <div class='item-img'>
              <div class='khungAnh'> <a class='khungAnhCrop' href='{{$item->alias}}' title='{{$item->title}}'> <img src="{{$item->thumb}}" class=""/> </a> <a class='icon' href='{{$item->alias}}'><img src='assets/Css/images/icon-video.png' alt='img'></a> </div>
              <div class='text'> <a href='{{$item->alias}}'>
                <h2>{{$item->title}}</h2>
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
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
