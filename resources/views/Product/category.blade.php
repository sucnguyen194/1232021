@extends('Layouts.layout')
@section('title') {!!$cate_current->title_seo!!} @stop
@section('site_name') {!!$cate_current->name!!} @stop
@section('url') {{url($cate_current->alias)}} @stop
@section('description') {!!$cate_current->description_seo!!} @stop
@section('keywords') {!!$cate_current->keyword_seo!!} @stop
@section('image') {!!url($cate_current->image)!!} @stop
@section('content')
<?php use App\model\FHomeModel;
$user = new FHomeModel();
?>
<?php  $background = $user->getFirstRowWhere('image',['lang' => Session::get('lang'),'position'=>'Background'],'sort','asc');
?>
{{getDataLang($cate_current->id,'catepro')}}
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<main id="main" class="">
  <div class="row category-page-row">
    <div class="col large-3 hide-for-medium ">
      @include('include.left')
    </div>
    <div class="col large-9">
      <div class="shop-container">
        <div class="term-description">
          {!!$cate_current->description!!}
        </div>
        <div class="woocommerce-notices-wrapper"></div>
        <div class="products row row-small large-columns-3 medium-columns-3 small-columns-2">
          @foreach($product as $item)
          <div class="product-small col has-hover product type-product post-11855 status-publish first instock product_cat-mai-hien-di-dong has-post-thumbnail shipping-taxable product-type-simple">
            @include('include.item-product')
          </div>
          @endforeach
        </div>
        <div class="container">
          <nav class="woocommerce-pagination">
            <ul class="page-numbers nav-pagination links text-center">
              {!!str_replace('/?','?',$product->render())!!}
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript">
  $('body').addClass('archive tax-product_cat term-mai-hien-di-dong term-1189 theme-flatsome woocommerce woocommerce-page woocommerce-no-js lightbox nav-dropdown-has-arrow');
</script>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->

<!--{!!str_replace('/?','?',$product->render())!!}-->
@stop
