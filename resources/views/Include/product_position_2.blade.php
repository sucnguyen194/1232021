<?php use App\model\FProductModel;
use App\model\FHomeModel;
$user = new FProductModel();
$var = new FHomeModel();
$categoryHome = $var->cateProductType('home');
//$productHome = $user->getProductByCate($cate->id);
?>
@foreach($categoryHome as $cate)
@if($cate->position=='2')
<div class="main-product">
  <div class="container">
    <div class="name-category">
      <div class="main-title">
        <h2 class="product-title"><span>{{$cate->name}}</span></h2>
      </div>
      <div class="des-title">
        <h3>{!!$cate->description!!}</h3>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="list-product text-center">
      <div class="row">
        <?php $productHome = $user->getProductByCate($cate->id); ?>
        @foreach($productHome as $item)
        <div class="col-sm-3 col-xs-12">
          @include('frontend.include.item-product')         
        </div>
        @endforeach
      </div>
    </div>
  </div>
<!-- 
      <div class="row" style="justify-content: center;">
        <a href="" class="buy" style="padding: 0 22.5px;">Mua hàng</a>
      </div> -->
    </div>
  </div>
  @endif
  @endforeach