@extends('Layouts.layout')
@section('title') {{lang('Giỏ hàng','Cart')}} @stop
@section('content')
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<div class="container">
  <div class="clearfix mt-15" id="bg-main">
    <div class="clearfix breadgroup">
      <ol class="breadcrumb" xmlns:v="http://rdf.data-vocabulary.org/#">
        <li typeof="v:Breadcrumb"> <a href="{{url()}}" rel="v:url" property="v:title">Trang Chủ</a> </li>
        <li typeof="v:Breadcrumb"> <a href="javascript:void(0)" title="Giỏ hàng" rel="v:url" property="v:title"> Giỏ hàng </a> </li>
      </ol>
    </div>
  </div>
  <div class="clearfix" id="product-detail-container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">
        <h1 class="clearfix title-page f-title" title="Giỏ hàng" itemprop="name"> <a href="shopping-cart">Giỏ hàng</a> </h1>
        <div class="table-list" style="white-space: nowrap;">
          @if(count($cart) > 0)
          <table border="1" class="tblSkin table-responsive">
            <tbody>
              <tr class="tblSkinHeader">
                <td>STT</td>
                <td>Tên sản phẩm</td>
                <td align="right">Đơn giá</td>
                <td align="right">Thành tiền</td>
                <td align="center">#</td>
              </tr>
              <?php $i=0; ?>
              @foreach($cart as $k=>$item)
              <?php $i++; ?>
              <tr class="tblSkinRow" id="671529g0">
                <td align="center" style="width: 1%;">{{$i}}</td>
                <td>
                  <div class="fl hidden-xs">
                    <a href="javascript:void(0)" class="image-product">
                      <img style="border-width: 0px;" src="{{$item->options->image}}" class="img-order">
                    </a>
                  </div>
                  <div class="right_Option_card">
                    <a href="javascript:void(0)">
                      <span class="orderName">{{$item->name}}</span>
                    </a>
                    <div class="item-options">
                      <div class="type-item">
                        Phân loại: {{$item->options->type}}
                      </div>
                      <div class="qty-item position-relative">
                        <input type="number" value="{{$item->qty}}" name="qty" data-rowid="{{$item->rowid}}" id=""  maxlength="3" min="0" class="input-qty" size="5"><span class="update-success_{{$item->rowid}}" style="color:#2f5999 ;font-size: 11px;margin-left: 5px; display:none;width: 100%; position: absolute;
                        top: 6px;
                        left: 100%; background: #fff; border:1px solid #ccc; border-radius: 3px;-webkit-border-radius: 3px;-moz-border-radius: 3px;-ms-border-radius: 3px; text-align: center"></span>
                      </div>
                    </div>
                  </div>
                </td>
                <td align="center" style="width: 65px;">{{Helper::adddotstring($item->price)}}</td>
                <td align="center" style="width: 65px;"><span id="items_price_{{$item->rowid}}">{{Helper::adddotstring($item->price*$item->qty)}}</span></td>
                <td align="center" style="width: 10px;"><a href="{{url()}}/cart-remove/{{$item->rowid}}" onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này')" class="shopcart-icon-16-delete" title="Xóa Sản Phẩm" id="likDelete">Xóa</a></td>
              </tr>
              @endforeach
              <tr class="tblSkinRow">
                <td align="right" colspan="2"><span class="totalmoney">Tổng tiền</span></td>
                <td align="center" colspan="3"><span id="lblTotalPriceShopCart" style="color: Red;"><span id="total_price" style="color:Red;">{{Helper::adddotstring(Cart::total())}} </span></span></td>
              </tr>
              <tr id="htmlcoupon" class="tblSkinRow"></tr>
            </tbody>
          </table>
          <p class="cart-button">
            <a id="btnContinue" class="blueButton" href="{{url()}}">
              <span><span>Tiếp tục xem hàng</span></span>
            </a>
            <a href="{{url('checkout')}}" class="blueButton" id="next_step02">
              <span><span>Xác nhận đơn hàng &gt;&gt;</span></span>
            </a>
          </p>
          @else
          <div class="empty-cart">
            <p>Chưa có sản phẩm nào trong giỏ hàng!</p>
          </div>
          <p class="cart-button">
            <a id="btnContinue" class="blueButton" href="{{url()}}">
              <span><span>Bắt đầu mua hàng</span></span>
            </a>
          </p>
          @endif
        </div>
      </div>
      <!-- -->
    </div>
  </div>
  <!-- -->
  <!-- -->
</div>
</div>
</div>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<style type="text/css">
  .shopcart-icon-16-delete {
    color: #8e0d23
  }
  .input-qty {
    position: relative;
    height: 38px;
    line-height: 38px;
    overflow: hidden;
    text-transform: capitalize;
    font-size: 14px;
    font-weight: bold;
    padding-left: 5px;
  }
  .right_Option_card {
    float: left;
  }
  .right_Option_card > a {
    color: #8e0d23;
    display: inline-block;
    width: 100%;
    margin-bottom: 5px;
    font-weight: 900
  }
  .type-item {
    margin-bottom: 5px;
  }
  .image-product {
    float: left;
    margin-right:15px;
  }
  .image-product img{
    width: 80px
  }
  table {
    border-collapse: collapse;
  }
  td {
    border:1px solid #e2e2e2;
    padding:8px;
  }
  .total input {
    width: 50px;
    text-align: center;
    padding:5px;
  }
  .heading {
    font-weight: 600;
    margin:15px 0;
  }
  .panel-lower {
    margin-top: 15px;
    border: 1px solid #e2e2e2;
    background: #be171e;
    display: inline-block;
    padding:15px!important;
  }
  .pay {
    color:#fff;
    font-weight: 600;
    text-transform: uppercase;
  }
  .shop_table {
    margin-bottom: 15px;
  }
  .blueButton {
    border: none;
    background: #8e0d23;
    display: inline-block;
    padding: 0px 25px;
    text-align: center;
    text-transform: uppercase;
    font-size: 13px;
    height: 38px;
    line-height: 40px;
    color: #FFF;
    border-radius: 3px;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    margin-top:10px;
  }
  .blueButton:hover {
    color: #fff;
  }
  @media(max-width:768px){
    .over-table {
      overflow-x: auto;
    }
    .uk-table{
      width: 100%!important
    }
    .table-list{
      overflow-x: scroll;
    }
  }
</style>
<script type="text/javascript">
  $('.btnCart').click(function(){
    window.location.href = '{{url("checkout")}}';
  })
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $('a[target="#payment"]').click(function(){
        var posClick = $(this).attr('target');//lấy giá trị trong thuộc tính href,gắn vào posClick. posClick sẽ có dạng #xxxxx
        var pos = $(posClick).position().top;//lấy khoảng cách từ id #xxxxx tới đầu trang gắn vào pos
        $("[href='"+posClick+"']").addClass("current");//thêm class current vào thẻ có href bằng giá trị trong posClick
        $('html, body').animate({
          scrollTop:pos+20//lăn tới vị trí cách đầu trang 1 khoảng pos + 20 so với đầu trang
        },1000);
      })
    $('html').on('change keyup','input[name="qty"]',function(){
     var num = $(this).val();
     if(num > 0){
       var num = $(this).val();
       var rowId = $(this).attr('data-rowid');
       $('.update-success_'+rowId+'').hide();
       _token = $('input[name="_token"]').val();
       num = $(this).val();
       url = "{{url('ajax/update-cart')}}/"+rowId+'/'+num;
       select = '#price'+rowId;
       $.ajax({
        url:url,
        cache:false,
        type:'get',
        data:{'rowId':rowId,'num':num},
        success:function(result){
          obj = JSON.parse(result);
          $('#items_price_'+rowId+'').html(obj[1]);
          $('#total_price').html(obj[2]);
          $('#total_').html(obj[2]);
          $('.update-success_'+rowId+'').text('Cật nhật số lượng thành công!');
          $('.update-success_'+rowId+'').fadeIn();
          setTimeout(function(){
            $('.update-success_'+rowId+'').fadeOut();
          },4000)
            //$(select).text(obj.items_price);
            //$('#items_price').text(obj.items_price);
            //$('#total_price').text(obj.total_price);
            //window.location.href = "{{url('shopping-cart')}}";
          }
        });
     }else{
       var rowId = $(this).attr('data-rowid');
       $('.update-success_'+rowId+'').text('Số lượng phải lớn hơn 0!');
       $('.update-success_'+rowId+'').fadeIn();
       setTimeout(function(){
        $('.update-success_'+rowId+'').fadeOut();
      },4000)
     }
   })
  })
  $(document).ready(function(){
    $('.remove_cart2').click(function(){
      rowId = $(this).attr('data-rowid');
      _token = $('input[name=_token]').val();
      url = "{{url('ajax/remove-cart')}}/"+rowId;
      $.ajax({
        url:url,
        cache:false,
        type:'get',
        data:{'rowId':rowId,'_token':_token},
        success:function(result){
          $('#table-cart').html(result);
        }
      });
    })
  })
</script>
@stop
