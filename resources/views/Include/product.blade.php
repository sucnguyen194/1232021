<?php use App\model\FProductModel;
use App\model\FHomeModel;
$user = new FProductModel();
$var = new FHomeModel();
$categoryHome = $var->cateProductType('home',12);
//$getProduct = $user->getProductByCate($cate->id);
//$productHome = $var->getProductHome($cate->id);

?>
@foreach($categoryHome as $cate)
<ul class="umt">
	<li class="l">
		<h2><a href="{{$cate->alias}}" title="{{$cate->name}}">{{$cate->name}}</a></h2>
	</li>
</ul>
<div class="row large-columns-3 medium-columns- small-columns-2 row-small">
	<?php $productHome = $var->getProductHome($cate->id); ?>
	@foreach($productHome as $item)
	<div class="product-small col has-hover product type-product post-12140 status-publish first instock product_cat-mai-hien-di-dong has-post-thumbnail shipping-taxable product-type-simple">
		@include('include.item-product')
	</div> 
	@endforeach
</div>
@endforeach