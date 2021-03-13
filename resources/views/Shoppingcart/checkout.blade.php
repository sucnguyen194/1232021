@extends('Layouts.layout')

@section('title') {{lang('Thanh toán','Checkout')}} @stop

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">

<div class="container">

  <div class="clearfix mt-15" id="bg-main">

    <div class="clearfix breadgroup">

      <ol class="breadcrumb" xmlns:v="http://rdf.data-vocabulary.org/#">

        <li typeof="v:Breadcrumb"> <a href="{{url()}}" rel="v:url" property="v:title">Trang Chủ</a> </li>

        <li typeof="v:Breadcrumb"> <a href="javascript:void(0)" title="Giỏ hàng" rel="v:url" property="v:title"> Xác nhận đơn hàng </a> </li>

      </ol>

    </div>

  </div>

  <div class="clearfix" id="">

    <div class="row">

      <div class="col-xs-12 col-sm-12 col-md-12">

        <h1 class="clearfix title-page f-title" title="Giỏ hàng" itemprop="name"> <a href="shopping-cart">Xác nhận đơn hàng</a> </h1>

        <div class="xacnhandondang">

          @if(count($cart) > 0)

          <div class="row">

            <div class="col-md-4 col-xs-12 col-md-offset-4">

              <div class="tenkho text-center mb-10"><strong><h3 class="mt-5">{{$site_option->name}}</h3></strong></div>

              <div class="dienthoai mb-5"><strong>Điện thoại:</strong> {{$site_option->hotline}}</div>

              <div class="taikhoan mb-5"><strong>Tài khoản:</strong> {{$site_option->address}}</div>

              <div class="line-dotted text-center">--------------------------------------------------------</div>

              <div class="noidunghoadon">

                <div class="tieudehoadon text-center"><h3 class="mt-5">HÓA ĐƠN XUẤT BÁN</h3></div>

                <div class="chitiethoadon">

                  <?php $time = time(); ?>

                  <div class="ngaygio mb-5">Ngày giờ: {{date('d/m/Y h:i', $time)}}</div>

                  <div class="thongtinkhachhang">

                   <form method="post" action="">

                    <div class="row">

                      <div class="col-md-12">

                        <div class="m-2">

                          <div class="row bg-light bor pt-2 pb-2">



                            <div class="col-md-12">

                              <div class="form-group">

                               <label>Khách hàng<span class="required">*</span>:</label>

                               <input type="text" required="required" id="name-customer" value="{{@Session::get('user')->name}}" name="name" class="form-control">

                             </div>

                           </div>

                           <div class="col-md-12">

                            <div class="form-group">

                              <label>Số điện thoại<span class="required">*</span>:</label>

                              <input type="text" required="required" id="phone-customer" value="{{@Session::get('user')->phone}}" name="phone" class="form-control">

                            </div>

                          </div>

                          <div class="col-md-12">

                            <div class="form-group">

                              <label>Địa chỉ nhận hàng<span class="required">*</span>:</label>

                              <textarea class="form-control" id="address-customer" required="required" name="address" rows="3">{!!@Session::get('user')->address!!}</textarea>

                            </div>

                          </div>

                        </div>

                      </div>

                    </div>

                  </div>



                  <div class="thontindonhang" id="mydiv">

                    <table border="0" width="100%" class="tblSkin table-responsive" style="border:unset">

                      <tbody>

                        <tr class="tblSkinHeader" style="border-bottom: 1px solid">



                          <td style="border:none"><strong>Tên sản phẩm</strong></td>

                          <td align="center" style="border:none"><strong>SL</strong></td>

                          <td align="center" style="border:none"><strong>Đ.giá</strong></td>

                          <td align="center" style="border:none"><strong>T.tiền</strong></td>

                        </tr>

                        @foreach($cart as $k=>$item)

                        <tr class="tblSkinRow" id="671529g0" style="border-bottom: 1px solid">

                          <td style="border:none">

                            <div class="right_Option_card">

                              <strong class="orderName">{{$item->name}}</strong>

                              <div class="item-options">

                                <div class="type-item">

                                  Phân loại: <strong>{{$item->options->type}}</strong>

                                </div>

                              </div>

                            </div>

                          </td>

                          <td style="border:none" align="center"> {{$item->qty}}</td>

                          <td align="center" style="width: 65px;border:none">{{Helper::adddotstring($item->price)}}</td>

                          <td align="center" style="width: 65px;border:none"><span id="total_t_671529">{{Helper::adddotstring($item->price*$item->qty)}}</span></td>

                        </tr>

                        @endforeach

                        <tr class="tblSkinRow">

                          <td align="left" colspan="2" style="border:none"><span class="totalmoney">Tổng tiền</span></td>

                          <td align="right" colspan="2" style="border:none"><span id="lblTotalPriceShopCart"><span id="lblTotalPrice" >{{Helper::adddotstring(Cart::total())}} </span></span></td>

                        </tr>

                      </tbody>

                    </table>

                  </div>

                  <div class="col-md-12">

                    <div class="clearfix text-center">

                      <p class="cart-button">
                        <a href="{{url('shopping-cart')}}" class="blueButton" id="back_step02"><span><span>&lt;&lt; Quay lại</span></span></a>
		@if(Session::get('user'))
						{{Helper::form('checkout')}}
						<input type="hidden" name="user_id" value="{{@Session::get('user')->id}}">
                        <button type="submit" class="blueButton" id="send-cart"><span><span>Gửi đơn hàng &gt;&gt;</span></span></button>
						  @else
						  <p><strong class="text-center" style="color:#8e0d23">*Chú ý: Quý khách vui lòng ĐĂNG NHẬP THÀNH VIÊN để chốt đơn. Trong trường hợp quý khách chưa có tài khoản, quý khách có thể chụp ảnh màn hình đơn hàng rồi gửi vào zalo {{$site_option->hotline}} để được hỗ trợ. </strong></p>

		@endif
                      </p>

                    </div>

                  </div>

                </div>

              </form>

            </div>

          </div>

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

<style type="text/css">

  .loading{

    position: fixed;

    left: 0;

    top: 0;

    bottom: 0;

    right: 0;

    background: rgba(255,255,255,0.7);

  }

  .loading img {

    position: fixed;

    left: 0;

    top:0;

    bottom: 0;

    right: 0;

    margin:auto;

  }

</style>

<div class="loading"><img src="public/images/loading.gif"></div>

<script type="text/javascript">

  $('.loading').hide();

  $('#send-cart').click(function(){

    var name = $('#name-customer').val();

    var phone = $('#phone-customer').val();

    var address = $('#address-customer').val();

    if(name == "" || phone == "" || address == ""){

     $('.loading').hide();

   }else{

    $('.loading').show();

  }

})

</script>

<style type="text/css">

  .required {

    color: #f00;

  }

  .searchProductHome {

    display: none;

  }

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

    .talbe-list{

      overflow-x: scroll;

    }

  }

</style>





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

    $('input[name="qty"]').change(function(){

      rowId = $(this).attr('data-rowid');

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

          $('.update-success_'+rowId+'').text('Update Success');

          $('.update-success_'+rowId+'').fadeIn(1000);

          $('.update-success_'+rowId+'').fadeOut(6000);

            //$(select).text(obj.items_price);

            //$('#items_price').text(obj.items_price);

            //$('#total_price').text(obj.total_price);

            //window.location.href = "{{url('shopping-cart')}}";

          }

        });

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
