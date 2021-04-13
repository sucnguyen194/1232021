@extends('Layouts.layout')
@section('title') {{$cate->title_seo}} @stop
@section('site_name') {{$cate->title_seo}} @stop
@section('url') {{url($cate->alias)}} @stop
@section('description') {{$cate->description_seo}} @stop
@section('keywords') {{$cate->keyword_seo}} @stop
@section('image') {{asset($cate->image)}} @stop
@section('lang') {{redirect_lang($cate->alias)}} @stop
@section('content')


<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<section class="w3-display-container w3-white w3-center" style="padding-top:80px;">
    <ul class="breadcrumb" style="margin:0px">
        <li><a href="{{route('home')}}" title="Trang Chủ">Trang Chủ</a></li>
        <li>{{$cate->title}}</li>
    </ul>
</section>
<section class="w3-display-container w3-light-grey w3-padding-32">
    <h3 class="w3-center w3-xxlarge">{{$cate->title}}</h3>
    <p class="w3-center w3-large">{!! $cate->description !!}</p>

    <div class="w3-row-padding w3-margin-top">
        @foreach($posts as $item)
        <div class="w3-col l3 m6 s6 w3-margin-bottom">
            <div class="w3-card"> <a href="{{route('alias',$item->alias)}}" title="{{$item->title}}"> <img class="w3-image" src="{{asset($item->thumb)}}" alt="{{$item->title}}"> </a>
                <div class="w3-container w3-center w3-green" style="height:170px">
                    <h3> <a class="cus-title-2 w3-text-black w3-hover-text-white w3-large" title="{{$item->title}}" href="{{route('alias',$item->alias)}}">{{$item->title}}</a></h3>
                    <p class="cus-title-6 w3-justify">{{str_limit($item->description,40)}}</p>
                </div>
            </div>
        </div>
        @endforeach
        <div class="pagination">
            {{ $posts->appends(request()->except(['page']))->links() }}
        </div>
    </div>
</section>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
