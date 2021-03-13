@extends('Layouts.layout')
@section('title') {{lang('Tìm kiếm','Search')}} @stop
@section('content')
<?php use App\model\FHomeModel;
$user = new FHomeModel();
?>
 {{getDataLang(0,'search')}}
 <?php  $background = $user->getFirstRowWhere('image',['lang' => Session::get('lang'),'position'=>'Background'],'sort','asc');
?>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
  <div id="body">
    <div class="body-interior">
      <div class="pad append-clear">
        <div class="content-singular content-page category_page" id="content">

          @foreach($news as $item)
          <div class="pad append-clear wrap-inner-3">
            <div class="t"></div>
            <div class="m">
              <div class="hentry-large page type-page status-publish has-post-thumbnail hentry">
                <div class="title"> <a href="{{$item->alias}}">
                  <h2 class="custom_title">{{$item->title}}</h2>
                  </a> </div>
                <div class="meta append-clear">
                  <p><span class="author">by {{$item->user_name}}</span> | <span class="date">Th{{date('m',$item->time)}} {{date('d',$item->time)}}, {{date('Y',$item->time)}}</span></p>
                  <div class="clear"></div>
                </div>
                <div class="content" itemprop="mainContentOfPage" style="overflow: hidden!important;"> <a href="{{$item->alias}}"><img  data-src="{{$item->thumb}}" class="lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
                  <noscript>
                  <img src="{{$item->thumb}}">
                  </noscript>
                  </a>
                  <p>{!!Helper::getDescription($item->description,40)!!}&hellip;</p>
                  <div class="swp-content-locator"></div>
                </div>
              </div>
              <div class="clear"></div>
            </div>
            <div class="b"></div>
          </div>
          <div class="clear"></div>
          @endforeach
          <div class="clear"></div>
          <div class="navigation">
            <div class="alignleft"></div>
            <div class="alignright">{!!str_replace('/?','?',$news->render())!!}</div>
          </div>

        </div>
        @include('include.right')
        <div class="clear"></div>
      </div>
    </div>
  </div>

 <!-------------------------->
 <!-----------SOURCSE----------->
 <!-------------------------->

 @stop
