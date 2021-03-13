<?php 
@$price = $item->price;
@$sale = $item->price_sale;
@$per = round((($price - $sale) / $price ) * 100);
@$ePrice = Helper::adddotstring($price);
@$eSale = Helper::adddotstring($sale);
?>
<div class="clearfix item transition"> <a href="{{$item->alias}}" class="clearfix img-hover-zoom transition">
  <figure class="img"> <img src="{{$item->image}}" alt="{{$item->name}}" title="{{$item->name}}" class="proimg img-responsive img-zoom"> </figure>
  <div class="des ">
    <h3 class="f-title transition">{{$item->name}}</h3>
    <p class="price-box"><strong class="title f-title">SP có sẵn:</strong><b class="price-sale">@if($item->qty > 0){{Helper::adddotstring($item->qty)}} sản phẩm @else Tạm hết @endif</b></p>
    <p class="price-box"><strong class="title f-title">Giá sỉ (CTV):</strong><b class="price-sale">@if($price > 0) {{Helper::adddotstring($item->price)}} vnđ @else Liên hệ @endif</b></p>
    <p class="price-box"><strong class="title f-title">Giá sỉ (thùng)</strong><b class="price-sale">@if($sale > 0) {{Helper::adddotstring($item->price_sale)}} vnđ @else Liên hệ @endif</b></p>
  </div>
</a> </div>1