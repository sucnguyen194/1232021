@extends('Layouts.layout')
@section('title') Thư viện hình ảnh - {{setting()->name}} @stop
@section('url') {{url('gallery')}}.html @stop
@section('description') Thư viện - {{setting()->name}} @stop
@section('keywords') Thư viện - {{setting()->name}} @stop
@section('image') {{asset(setting()->og_image ?? setting()->logo)}}  @stop
@section('lang') {{redirect_lang(\App\Enums\AliasType::HOMEGALLERY)}} @stop
@section('content')
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<div class="ct-b-slider-subpage">
  <div class="ct-slider">
    <div class="item"> <img src="assets/storage/app/uploads/public/5d6/189/0d0/5d61890d0b192728076040.jpg" /> </div>
  </div>
  <figure> <img class="logo-subpage" src="assets/themes/cantho/assets/images/logo-subpage.png" alt=""> </figure>
</div>
<section class="section ct-s-section ct-s-gallery"
    style="background-image: url(assets/themes/cantho/assets/images/bg-gallery.jpg)">
  <div class="ct-b-container">
    <div class="ct-b-head ct-title">
      <h2>Thư viện</h2>
      <img src="assets/themes/cantho/assets/images/title-icon.png" alt="title"> </div>
    <div class="ct-b-body">
      <div class="slider-nav-gallery">
        <div class="ct-slide">
          <h4>Hình ảnh</h4>
        </div>
        <div class="ct-slide">
          <h4>Video</h4>
        </div>
      </div>
      <div class="slider-for-gallery">
        <div class="ct-content-gallery">
          <div class="ct-slide-gallery">
            @foreach($banner as $item)
            <div class="gallery-item js-img-popup"> <a href="{{$item->link}}"> <img src="{{$item->link}}" alt="gallery"> </a> </div>
            @endforeach
          </div>
        </div>
        <div class="ct-content-gallery">
          <div class="ct-slide-gallery-video">
            <div class="slider-for-gallery-video">
              @foreach($video as $item)
              <div class="videoWrapper">
                <iframe
                                    class="ct-frame-video"
                                    src="http://www.youtube.com/embed/{{$item->video}}?enablejsapi=1&version=3&playerapiid=ytplayer" frameborder="0"
                                    allowfullscreen></iframe>
              </div>
            @endforeach
            </div>
            <div class="slider-nav-gallery-video">
              @foreach($video as $item)
              <img src="{{$item->thumb}}" class="js-frame" alt="gallery">
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script type="text/javascript">
  $('body').addClass('gallerypage');
</script>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->

@stop
