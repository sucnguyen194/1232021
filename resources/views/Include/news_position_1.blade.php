<?php use App\model\FNewsModel;
use App\model\FHomeModel;
$user = new FNewsModel();
$var = new FHomeModel();
$categoryHome  = $var->cateNewsType('home');
//$getNews = $user->getNewsByCategory($cate->id);
//$newsHome = $var->getNewsHome($cate->id);
?>
<?php $count=0; ?>
@foreach($categoryHome as $cate)
<?php $count++; ?>
@if($count==1)
<div class="row custom">
  <div class="category"> 
    <!--<a href=""><h2 class="cat-name"></h2></a>--> 
    <a href="kien-thuc">
      <h2 class="cat-name"><span>{{$cate->title}}</span></h2>
    </a> </div>
    <?php $newsHome = $var->getNewsHome($cate->id); ?>
    <?php $i=0; ?>
    @foreach($newsHome as $item)
    <?php $i++; ?>
    @if($i<3)
    <div class="col-xs-6 z-10 mb-1">
      <div class="first-posts">
        <div class="img-content"> <a href="{{$item->alias}}"><img   data-src="{{$item->thumb}}" class="img-thumbnail show-img lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
          <noscript>
            <img class="img-thumbnail show-img" src="{{$item->thumb}}">
          </noscript>
        </a> </div>
        <div class="title"> <a href="{{$item->alias}}">
          <h2 class="my_title">{{$item->title}}</h2>
        </a> </div>
        <div class="my_meta">
          <p><span class="author">by {{$item->user_name}}</span> | <span class="date">Th{{date('m',$item->time)}} {{date('d',$item->time)}}, {{date('Y',$item->time)}}</span></p>
          <div class="clear"></div>
        </div>
      </div>
    </div>
    @elseif($i > 2 && $i < 7)
    <div class="col-md-6 mb-1">
      <div class="row">
        <div class="col-xs-5"> <a href="{{$item->alias}}"><img   data-src="{{$item->thumb}}" class="img-thumbnail show-img-2 lazyload" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==">
          <noscript>
            <img class="img-thumbnail show-img-2" src="{{$item->thumb}}">
          </noscript>
        </a> </div>
        <div class="col-xs-7 my_title_side">
          <div class="title"> <a href="{{$item->alias}}">
            <h2 class="my_title2">{{$item->title}}</h2>
          </a> </div>
          <div class="my_meta2">
            <p><span class="author">by {{$item->user_name}}</span> | <span class="date">Th{{date('m',$item->time)}} {{date('d',$item->time)}}, {{date('Y',$item->time)}}</span></p>
            <div class="clear"></div>
          </div>
        </div>
      </div>
    </div>
    @endif
    @endforeach
  </div>
  @endif
  @endforeach
