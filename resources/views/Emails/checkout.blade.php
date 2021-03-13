<div class="container">
  <div class="clearfix" id="">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="xacnhandondang" style="width:350px!important" id="detailPrint">
          <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600&amp;display=swap" rel="stylesheet">
          <style type="text/css">
            .xacnhandondang {
              line-height: 24px!important;
              font-family: 'Oswald', sans-serif!important;
            }

            .mt-5 {
              margin-top:5px!important;
            }
            .mb-5 {
              margin-bottom: 5px!important;
            }
            .required {
              color: #f00
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
            .text-center {
              text-align: center!important
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
          <div class="row">
            <div class="col-md-12 col-xs-12">
              <div class="tenkho text-center mb-10"><strong>
                <h3 class="mt-5">{{$site_option->name}}</h3>
                </strong></div>
              <div class="dienthoai mb-5"><strong>Điện thoại:</strong> {{$site_option->hotline}}</div>
              <div class="taikhoan mb-5"><strong>Tài khoản:</strong> {{$site_option->address}}</div>
              <div class="line-dotted text-center">--------------------------------------------------------</div>
              <div class="noidunghoadon">
                <div class="tieudehoadon text-center">
                  <h3 class="mt-5">HÓA ĐƠN XUẤT BÁN</h3>
                </div>
                <div class="chitiethoadon">
                  <div class="ngaygio mb-5">Ngày giờ: <span id="time-checkout">{{date('d/m/Y H:i',$time)}}</span> - Mã hóa đơn: BTMART-<span id="id_btmart">{{$id}}</span></div>
                  <div class="thongtinkhachhang">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="m-2">
                          <div class="row bg-light bor pt-2 pb-2">
                            <div class="col-md-12">
                              <div class="mb-5">
                                <div class="tenkhachhang"><strong>Khách hàng:</strong> <span id="name-customer">{{$name}}</span></div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="mb-5">
                                <div class="sodienthoai"><strong>Số điện thoại:</strong> <span id="phone-customer">{{$phone}}</span></div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="mb-5">
                                <div class="diachinhanhang"><strong>Địa chỉ nhận hàng:</strong> <span id="address-customer">{{$address}}</span></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="thontindonhang" id="detail-cart">
                      {!!$content!!}
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
