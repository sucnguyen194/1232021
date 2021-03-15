@extends('Layouts.layout')
@section('title') {!!$product->title_seo!!} @stop
@section('url') {{url($product->alias)}} @stop
@section('description') {!!$product->description_seo!!} @stop
@section('keywords') {!!$product->keyword_seo!!} @stop
@section('site_name') {!!$product->name!!} @stop
@section('image') {!!url($product->image)!!} @stop
@section('content')
<?php
use App\model\FHomeModel;
$user = new FHomeModel();
@$price = $product->price;
@$sale = $product->price_sale;
@$per = round((($price - $sale) / $price  ) *100);
@$pPrice = Helper::adddotstring($price - $sale);
@$ePrice = Helper::adddotstring($price);
@$eSale = Helper::adddotstring($sale);
$background = $user->getFirstRowWhere('image',['lang' => Session::get('lang'),'position'=>'Background'],'sort','asc');
?>
{{getDataLang($product->id,'product')}}
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<div class="container">
  <div class="clearfix mt-15" id="bg-main">
    <div class="clearfix breadgroup">
      <ol class="breadcrumb" xmlns:v="http://rdf.data-vocabulary.org/#">
        <li typeof="v:Breadcrumb"> <a href="{{url()}}" rel="v:url" property="v:title">Trang Chủ</a> </li>
        <li typeof="v:Breadcrumb"> <a href="javascript:void(0)" title="{{$product->name}}" rel="v:url" property="v:title"> {{$product->name}} </a> </li>
      </ol>
    </div>
  </div>
  <div class="clearfix" id="product-detail-container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">
        <h1 class="clearfix title-page f-title" title="{{$product->name}}" itemprop="name"> <a href="{{$product->alias}}">{{$product->name}}</a> </h1>
        <div class="clearfix dproduct-info">
          <div class="row">
            <div class="col-md-5 col-xs-12">
              <div id="detail-img">
                <div class="slider slider-for">
                  <div> <span class="box-img"> <img src="{{$product->image}}" alt="{{$product->name}}" title="{{$product->name}}" class="proimg img-responsive"/> <img src="assets/front/img/product-trans.png" class="trans"> </span> </div>
                </div>
              </div>
            </div>
            <div class="col-md-7 col-xs-12">
              <p style="color: #8e0d23; font-size: 16px; font-weight: 800">*Chú ý: Giá tiền được tính trên từng đơn vị sản phẩm (cái, gói, thùng)</p>
              <div id="detail-info">
                <div class="clearfix detail-info-group">
                  <div class="clearfix price-number position-relative" itemprop="qty">
                    <span class="col-md-4 col-xs-4">SP sẵn có</span>
                    <div class="col-md-8 col-xs-8">
                      <p class="price-box"><b class="price-sale">@if($product->qty == 0) Tạm hết @else  {{$product->qty}} sản phẩm @endif</b></p>
                    </div>
                  </div>
                  <div class="">
                    <div class="col-md-12 col-xs-6 price-number position-relative" itemprop="price">
                      <div class="row">
                        <span class="col-md-4 col-xs-4">Giá sỉ (CTV)</span>
                        <div class="col-md-8 col-xs-8">
                          <p class="price-box"><b class="price-sale">{{@$ePrice}} vnđ</b></p>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 col-xs-6 price-number position-relative" itemprop="price">
                      <div class="row">
                        <span class="col-md-4 col-xs-4">Giá sỉ (Thùng)</span>
                        <div class="col-md-8 col-xs-8">
                          <p class="price-box"><b class="price-sale">{{@$eSale}} vnđ</b></p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <style type="text/css">
                    .checkbox-buy-price {
                      height: 50px;
                      width: 50px;
                    }
                    .has-error {
                      color:#8e0d23;
                    }
                  </style>
                  <form action="#" method="post" enctype="application/x-www-form-urlencoded" class="product-add-cart" >
                    <div class="clearfix price-number position-relative" itemprop="price" style="display: inline-block;">
                      <span class="col-md-4 col-xs-4">Phân loại</span>
                      <div class="col-md-8 col-xs-8">
                        <select class="form-control" id="type-product">
						@if($price > 0)
                          <option value="{{@$price}}" data-type="1">Giá sỉ (CTV): {{@$ePrice}} vnđ - SP sẵn có: {{$product->qty}} sản phẩm</option>
						@endif
						@if($sale > 0)
                          <option value="{{@$sale}}" data-type="2">Giá sỉ (Thùng): {{@$eSale}} vnđ</option>
						@endif
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-xs-12 col-sm-4">Số lượng</label>
                      <!-- -->
                      <div class="col-xs-12 col-md-8">
                        <div class="row clearfix"> <span class="col-xs-6 col-sm-5 qty">
                          <input type="number" class="form-control" id="Quantity"  name="qty" value="1" min="1" />
                        </span>
                        <input type="hidden" value="{{$product->id}}" id="product_id">
                        <span class="col-xs-6 col-sm-7 fbutton">
                          <button type="submit" class="transition f-title btnAddToCart" id="add-cart"> <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng </button>
                        </span>
                        <div class="has-error"></div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <!-- -->
            </div>
          </div>
        </div>
        <!-- -->
      </div>
    </div>
    <!-- -->
    <!-- -->
  </div>
</div>
<script type="text/javascript">
  $('.has-error').hide();
  var qty = $('#Quantity');
  $('#type-product').change(function(){
    var select = $('#type-product option:selected');
    var qty = $('#Quantity');
    if(select.attr('data-type') == 1){
      qty.attr('max','{{$product->qty}}');
      if(qty.val() < 1 || qty.val() > {{$product->qty}}){
        $('#add-cart').prop('disabled',true);
        $('#add-cart').css('cursor','wait');
        $('.has-error').show().text('Số lượng sản phẩm sỉ cho CTV phải lớn hơn 1 và tối đa là {{$product->qty}} sản phẩm! Khách hàng lấy thêm vui lòng inbox zalo {{$site_option->hotline}}');
      }else{
        $('#add-cart').prop('disabled',false);
        $('.has-error').hide().text();
        $('#add-cart').css('cursor','auto');
      }
    }else{
      if(qty.val() < 1){
        $('.has-error').show().text('Số lượng sỉ theo "THÙNG" phải lớn hơn 0!');
        $('#add-cart').prop('disabled',true);
        $('#add-cart').css('cursor','wait');
      }else{
        $('.has-error').hide().text();
        $('#add-cart').prop('disabled',false);
        $('#add-cart').css('cursor','auto');
      }
    }
  })

  qty.on('keyup change',function(){
    var select = $('#type-product option:selected');
    if(select.attr('data-type') == 1){
      if(qty.val() < 1 || qty.val() > {{$product->qty}}){
        $('#add-cart').prop('disabled',true);
        $('#add-cart').css('cursor','wait');
        $('.has-error').show().text('Số lượng sản phẩm sỉ cho CTV phải lớn hơn 1 và tối đa là {{$product->qty}} sản phẩm! Khách hàng lấy thêm vui lòng inbox zalo {{$site_option->hotline}}');
      }else{
        $('#add-cart').prop('disabled',false);
        $('.has-error').hide().text();
        $('#add-cart').css('cursor','auto');
      }
    }else{
      if(qty.val() < 1){
        $('.has-error').show().text('Số lượng sỉ theo "THÙNG" phải lớn hơn 0!');
        $('#add-cart').prop('disabled',true);
        $('#add-cart').css('cursor','wait');
      }else{
        $('.has-error').hide().text();
        $('#add-cart').prop('disabled',false);
        $('#add-cart').css('cursor','auto');
      }
    }
    // if(qty.val() < 1 && select == 1){
    //   $('#add-cart').prop('disabled',true);
    //   $('#add-cart').css('cursor','wait');
    //   $('.has-error').show();
    // }else{
    //   $('#add-cart').prop('disabled',false);
    //   $('.has-error').hide();
    //   $('#add-cart').css('cursor','auto');
    // }
  })
</script>
<div class="clearfix">
  <div class="clearfix" id="prodetail-other">
    <div class="title-page f-title text-sm clearfix marbot-30"> <span class="title f-title">Sản phẩm khác</span> </div>
    <div class="clearfix col_4 row" id="products-container">
      @foreach($aOthers as $item)
      <div class="col-xs-6 col-sm-4 col-md-3 box">
        @include('include.item-product')
      </div>
      @endforeach
    </div>
  </div>
</div>
</div>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
