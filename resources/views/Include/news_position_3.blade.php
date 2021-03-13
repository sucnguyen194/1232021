<?php use App\model\FNewsModel;
use App\model\FHomeModel;
$user = new FNewsModel();
$var = new FHomeModel();
$categoryHome  = $var->cateNewsType('home');
//$getNews = $user->getNewsByCategory($cate->id);
//$newsHome = $var->getNewsHome($cate->id);
?>
 <div class="category skin1">
  <div class="category-nav">
    <ul role="tablist">
      <?php $i=0; ?>
      @foreach($categoryHome as $cate)
      @if($cate->position=='3')
      <?php $i++ ?>
      @if($i==1)
      <li class="active"> <a href="{{$cate->alias}}"
       title="{{$cate->title}}">{{$cate->title}}</a> </li>
       @else
       <li> <a href="{{$cate->alias}}"
         title="{{$cate->title}}">{{$cate->title}}</a> </li>
         @endif
         @endif
         @endforeach
       </ul>
     </div>

     <?php $i=0; ?>
     @foreach($categoryHome as $cate)
     @if($cate->position=='3')
     <?php $i++ ?>
     @if($i==1)
     <div class="tab-content">
      <div role="tabpanel" class="tab-pane active"
      id="news_{{$cate->id}}">
      <div class="row featured">
        <div class="col-sm-4">
          <?php $newsHome = $var->getNewsHome($cate->id); ?>
          <?php $p=0; ?>
          @foreach($newsHome as $item)
      <?php $p++;?>
          @if($p==1)
          <div class="cover"> <a href="{{$item->alias}}" class="img"
           title="{{$item->title}}"> <img class="img-bg" src="{{$item->thumb}}" alt="{{$item->title}}"> </a>
           <p class="title"> <a href="{{$item->alias}}"
             title="{{$item->title}}">{{$item->title}}</a> </p>
             <p class="summary">{!!Helper::getDescription($item->description,60)!!}</p>
           </div>
           @endif
           @endforeach
         </div>
         <div class="col-sm-8">
          <div class="row">
          <?php $p=0; ?>
          @foreach($newsHome as $item)
      <?php $p++;?>
          @if($p > 1 && $p < 8)
            <div class="col-sm-6 media-content"> <a href="{{$item->alias}}" title="{{$item->title}}" class="media-img"> <img class="img-bg" src="{{$item->thumb}}" alt="{{$item->title}}" width="77"> </a>
              <div class="media-txt">
                <p class="title"> <a href="{{$item->alias}}" title="{{$item->title}}">{{$item->title}}</a> </p>
                <p class="summary">{!!Helper::getDescription($item->description,40)!!}</p>
              </div>
            </div>
       @if($p%2==1)
       <div class="clearfix"></div>
       @endif
           @endif
           @endforeach
          </div>
        </div>
      </div>
      <br>
      <a href="{{$cate->alias}}">Xem th&ecirc;m</a> </div>
    </div>
    @endif
    @endif
    @endforeach
  </div>