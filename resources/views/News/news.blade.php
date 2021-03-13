@extends('Layouts.layout')
@section('title') {!!$news->title_seo!!} @stop
@section('url') {{url($news->alias)}} @stop
@section('site_name') {!!$news->title!!} @stop
@section('description') {!!$news->description_seo!!} @stop
@section('keywords') {!!$news->keyword_seo!!} @stop
@section('image') {{asset($news->image)}} @stop
@section('content')
{{redirect_lang($news->alias)}}

<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<section class="w3-display-container w3-white w3-center" style="padding-top:80px;">
    <ul class="breadcrumb" style="margin:0px">
        <li><a href="{{route('home')}}" title="Trang Chủ">Trang Chủ</a></li>
        <li><a href="{{route('alias',$cate->alias)}}" title="{{$cate->title}}">{{$cate->title}}</a></li>
        <li>{{$news->title}}</li>
    </ul>
</section>
<section class="w3-container w3-light-grey">
    <div class="w3-row">
        <div class="w3-twothird w3-panel w3-justify cus-content">
            <h1 class="w3-text-red w3-xxlarge w3-center">{{$news->title}}</h1>
            @if(str_limit($news->description))
            <p class="w3-center"> <em>{!! $news->description !!}</em></p>
            @endif
            @if(file_exists($news->image))
            <p class="w3-center"><img class="w3-images" src="{{asset($news->image)}}" title="{{$news->title}}" alt="{{$news->title}}"/></p>
            @endif

            <div class="entry-content">
                {!! $news->content !!}
            </div>
        </div>
        <div class="w3-third w3-white">
            <div class="w3-border w3-round">
                <div class="w3-center w3-grey w3-text-white w3-padding"><strong>LIÊN QUAN</strong></div>
                @foreach($related as $item)
                <div class="w3-row w3-margin">
                    <div class="w3-col l4 m4 s4 w3-padding"> <a href="{{route('alias',$item->alias)}}" title="{{$item->title}}"> <img class="w3-images" src="{{asset($item->thumb)}}" alt="{{$item->title}}" style="width:100px"> </a> </div>
                    <div class="w3-col l8 m8 s8">
                        <h3><a href="{{route('alias',$item->alias)}}" title="{{$item->title}}">{{$item->title}}</a></h3>
                        <span><i class="fa fa-calendar"></i> {{$item->created_at->diffForHumans()}}</span> <br/>
                        <span><i class="fa fa-eye"></i> Xem: {{$item->view}} </span> </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
