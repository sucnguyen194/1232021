@extends('Layouts.layout')
@section('title') {!!$cate_current->title_seo!!} @stop
@section('url') {{url($cate_current->alias)}} @stop
@section('description') {!!$cate_current->description_seo!!} @stop
@section('keywords') {!!$cate_current->keyword_seo!!} @stop
@section('content')
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<main class="main">
  @if($cate_current->image)
  <section class="bannerTop" style="background-image:url({{$cate_current->image}})">
    <div class="title-category"><h1>{{$cate_current->title}}</h1></div>
  </section>
  @else
  <section class="bannerTop" style="background-image:url('assets/images/custom.png')">
    <div class="title-category"><h1>{{$cate_current->title}}</h1></div>
  </section>
  @endif
<section class="hot-job-gap mt-30 mb-50">
  <div class="container">
    <div class="list-news-job">
      <div class="row">
        <div class="col-md-12 col-xs-12">
          <div class="form-search mb-30">
            @include('frontend.include.form')
          </div>
        </div>
        <div class="col-md-8 col-xs-12">
          <div class="col-left-job">
              @foreach($recruitment as $item)
              <div class="item-job">
                <div class="row">
                  <div class="col-md-2 col-xs-3">
                    <div class="item-job-img">
                      <a href="{{$item->alias}}"><img src="{{$item->partner_link}}" alt="{{$item->title}}"></a>
                    </div>
                  </div>
                  <div class="col-md-10 col-xs-9">
                    <div class="item-job-info">
                      <div class="item-job-title">
                        <a href="{{$item->alias}}">{{$item->title}}</a><span class="btn-save-job " data-id="27" data-toggle="tooltip" title="Vui lòng đăng nhập để lưu việc làm"> <i class="fa fa-heart-o" aria-hidden="true"></i> <span class="hidden-xs"> Lưu </span> </span>
                        <p><i class="fa fa-building"></i> {{$item->partner_name}}</p>
                      </div>
                      <div class="item-job-meta">
                        <div class="row">
                          <div class="col-md-6 col-xs-5">
                            <div class="time-location"> <span> <i class="fa fa-clock-o" aria-hidden="true"></i>{{date('Y-m-d',$item->time)}} </span> <span class="h--location"> <i class="fa fa-map-marker"></i>{{$item->location}} </span></div>
                          </div>
                          <div class="col-md-6 col-xs-7">
                            <span class="item-job-price"><strong>{{$item->salary}}</strong></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
            <div class="pagination mt-20">
              {!!str_replace('/?','?',$recruitment->render())!!}
            </div>
          </div>
        </div>
        <div class="col-md-4 col-xs-12">
          <div class="col-right-job">
            <?php $bannerTD = DB::table('image')->select('*')->where(['position'=>'Banner tuyển dụng'])->orderby('sort','asc')->get(); ?>
            @foreach($bannerTD as $item)
            <div class="item-banner-right">
              <a href="{{$item->url}}"><img src="{{$item->link}}" alt="{{$item->title}}"></a>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="banner-bottom mb-50">
  @include('frontend.include.banner')
</section>
</main>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<input type="hidden" id="getDataLang" data-id="{{$cate_current->id}}" data-type="recruitment_category">
<!--{!!str_replace('/?','?',$recruitment->render())!!}-->
@stop


