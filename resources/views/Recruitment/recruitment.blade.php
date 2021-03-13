@extends('Layouts.layout')
@section('title') {!!$recruitment->title_seo!!} @stop
@section('url') {{url($recruitment->alias)}} @stop
@section('description') {!!$recruitment->description_seo!!} @stop
@section('keywords') {!!$recruitment->keyword_seo!!} @stop
@section('site_name') {!!$recruitment->title!!} @stop
@section('image') {!!url($recruitment->image)!!} @stop
@section('content')
{{getDataLang($recruitment->id,'recruitment')}}
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<main class="main" id="">
  @if($cate_current->image)
  <section class="bannerTop" style="background-image:url({{$cate_current->image}})">
    <div class="info-top">
      <div class="container">
        <div class="row">
          <div class="col-md-2 col-xs-12">
            <img src="{{$recruitment->partner_link}}" alt="{{$recruitment->partner_name}}">
          </div>
          <div class="col-md-8 col-xs-12">
            <div class="info-jobs">
              <h1 class="detail-title-jobs">{{$rectuitment->title}}</h1>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  @else
  <section class="bannerTop" style="background-image:url('assets/images/custom.png')">

  </section>
  @endif
  <section class="news-home mt-30 mb-20">
    <div class="info-top container position-relative mb-30">
      <div class="">
        <div class="row">
          <div class="col-md-2 col-xs-12">
            <div class="img-partner"><img src="{{$recruitment->partner_link}}" alt="{{$recruitment->partner_name}}"></div>
          </div>
          <div class="col-md-8 col-xs-12">
            <div class="info-jobs">
              <h1 class="detail-title-jobs">{{$recruitment->title}}</h1>
              <div class="name-partner mt-5">{{$recruitment->partner_name}}</div>
              <div class="salary mt-5"> {{$recruitment->salary}}</div>
              <div class="date-job mt-5"><span class="">Ngày đăng: {{date('d/m/Y',$recruitment->time)}}</span> - <span class="">Hết hạn:  {{$recruitment->time_out}}</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="list-news-home">
        <div class="row">
          <div class="col-md-12 col-xs-12">
            <div role="tabpanel">
             <!-- Nav tabs -->
             <ul class="nav nav-tabs mb-30" role="tablist">
              <li role="presentation" class="active">
                <a href="#info" aria-controls="home" role="tab" data-toggle="tab">Thông tin</a>
              </li>
              <li role="presentation">
                <a href="#company" aria-controls="tab" role="tab" data-toggle="tab">Công ty</a>
              </li>
              <li role="presentation">
                <a href="#relationship" aria-controls="tab" role="tab" data-toggle="tab">Việc làm khác từ công ty</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-8 col-xs-12">
          <div role="tabpanel">
            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="info">
                <div class="content-news">
                  {!!$recruitment->requirement!!}
                </div>
              </div>
              <div role="tabpanel" class="tab-pane" id="company">
                <div class="description-company"><h4 class="title-ctn">Thông tin công ty</h4><p>{!!$recruitment->partner_description!!}</p></div>
              </div>
              <div role="tabpanel" class="tab-pane" id="relationship">
                <div class="list-item-recruitment">
                  @foreach($aOthers as $item)
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
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="box-summary link-list col-md-4 col-xs-12">
          <div class="summary-item">
            <div class="summary-icon"> <i class="fa fa-calendar-minus-o" aria-hidden="true"></i></div>
            <div class="summary-content"> <span class="label-content"> Ngày đăng tuyển </span><span class="content"> {{date('d/m/Y',$recruitment->time)}}</span></div>
          </div>
          <div class="summary-item">
            <div class="summary-icon"> <i class="fa fa-calendar-minus-o" aria-hidden="true"></i></div>
            <div class="summary-content"> <span class="label-content">Hạn nộp hồ sơ</span> <span class="content"> {{$recruitment->time_out}} </span></div>
          </div>
          <div class="summary-item">
            <div class="summary-icon"> <i class="fa fa-suitcase" aria-hidden="true"></i></div>
            <div class="summary-content"> <span class="label-content">Ngành nghề</span> <span class="content"> {{$recruitment->jobs_name}} </span></div>
          </div>
          <div class="summary-item">
            <div class="summary-icon"> <i class="fa fa-child" aria-hidden="true"></i></div>
            <div class="summary-content"> <span class="label-content">Số lượng</span> <span class="content"> {{$recruitment->quantity}} </span></div>
          </div>
          <div class="summary-item">
            <div class="summary-icon"> <i class="fa fa-clock-o" aria-hidden="true"></i></div>
            <div class="summary-content"> <span class="label-content">Thời gian làm việc</span> <span class="content"> {{$recruitment->time_work}} </span></div>
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

@stop
