@extends('Layouts.layout')
@section('title') {{$category->title_seo}} @stop
@section('site_name') {{$category->title_seo}} @stop
@section('url') {{route('alias',$category->alias)}} @stop
@section('description') {{$category->description_seo}} @stop
@section('keywords') {{$category->keyword_seo}} @stop
@section('image') {{asset($category->image)}} @stop
@section('lang') {{redirect_lang($category->alias)}} @stop
@section('content')
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
