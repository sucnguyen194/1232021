<?php use App\model\FNewsModel;
use App\model\FHomeModel;
$user = new FNewsModel();
$var = new FHomeModel();
$categoryHome  = $var->cateNewsType('home');
//$getNews = $user->getNewsByCategory($cate->id);
//$newsHome = $var->getNewsHome($cate->id);
?>
@foreach($categoryHome as $cate)
<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      <div class="bg-title">
        <h4>{{$cate->title}}</h4>
        <img alt="{{$cate->title}}" src="assets/images/content/anh-hon-hop/title-bg.png" title="{{$cate->title}}" width="50%">
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <?php $newsHome = $var->getNewsHome($cate->id) ?>
    @foreach($newsHome as $item)
    <div class="col-md-3 mb-3">
      <a href="{{$item->alias}}" title="{{$item->title}}">
        <div class="img-hover-zoom img-hover-zoom--colorize">
          <img src="{{$item->thumb}}" alt="{{$item->title}}">
        </div>
      </a>
      <h6 class="mt-3"><a href="" title="{{$item->title}}" class="den-vang">{{$item->title}}</a></h6>
      <small>Ngày đăng: {{date('d/m/Y',$item->time)}} - Lượt xem: {{$item->view}}</small>
      <p class="text-desc">{!!Helper::getDescription($item->description,30)!!}</p>
      <a class="contine" href="{{$item->alias}}">Xem thêm</a>
    </div>
    @endforeach
  </div>
</div>
@endforeach