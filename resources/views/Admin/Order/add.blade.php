@extends('Admin.Layout.layout')
@section('title')
    Thêm mới
@stop
@section('content')

    <div class="container-fluid" id="add-cart">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.orders.index')}}">Danh sách xuất hàng</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Thêm mới</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <form method="post" action="{{route('admin.orders.store')}}" novalidate enctype="multipart/form-data">
            <div class="row">
                @csrf
                <div class="col-lg-12">
                    <div class="card-box">
                        <div class="form-group">
                            <label class="font-weight-bold">Khách hàng <span class="required">*</span></label>
                            <select class="form-control customer" v-model="customer" name="user" v-on:change="choiseCustomer()" id="customer">
                                <option value="0">--Chọn khách hàng--</option>
                                <option v-for="(item,key) in users" v-bind:value="item.id" v-bind:key="key">@{{ item.name ?? item.account }}</option>
                            </select>
                            <p v-if="customer == 0" class="text-danger font-weight-bold mt-2">Vui lòng chọn khách hàng</p>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tên sản phẩm <span class="required">*</span></label>
                            <select class="form-control choise-product" id="products"  :disabled="customer == 0" v-model="product_id">
                                <option value="0">--Chọn sản phẩm--</option>
                                <option v-for="item in products" v-bind:value="item.id">
                                    @{{ item.name }} (@{{  item.amount }})
                                </option>
                            </select>
                            <p v-if="product_id == 0" class="text-danger font-weight-bold mt-1">Vui lòng chọn sản phẩm</p>
                        </div>

                        <div class="form-group" v-if="product != 0">
                            <label class="font-weight-bold">Thông tin sản phẩm</label>
                            <div class="alert alert-icon alert-danger text-danger alert-dismissible fade show" role="alert" v-if="amount > product.max && product_id > 0 && customer > 0">
                                <button type="button" class="close" data-dismiss="alert"
                                        aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <i class="mdi mdi-block-helper mr-2"></i>
                                <strong>Thông báo!</strong> Số lượng tối đa có thể thêm là: <span class="text-primary"> (@{{ product.max }})</span> Vui lòng <span class="text-purple">[ Cập nhật ]</span> thêm số lượng sản phẩm!
                            </div>
                            <div class="product-info" id="product_info">
                                <table class="table table-bordered table-striped" id="table-append">
                                    <thead>
                                    <tr>
                                        <th>Tên sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá bán</th>
                                        <th>Thành tiền</th>
                                        <th>Tiền lãi</th>
                                        <th>Hành động</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="product_id > 0 && customer > 0">
                                         <td><div class="font-weight-bold mb-1"> @{{ product.name }} <a href="javascript:void(0)" data-toggle="modal" data-target="#update-product" v-if="amount > product.max" v-on:click="getProduct(product.id)" class="font-weight-bold text-purple"> [ Cập nhật ]</a></div>
                                         <div class="text-primary font-weight-bold">[Giá nhập: <strong class="text-danger">@{{number_format(product.price_in)}}</strong>]</div>
                                         <div class="text-primary font-weight-bold">[Giá bán gần nhất: <strong class="text-danger">@{{number_format(product.price_buy)}}</strong>]</div>
                                         </td>
                                         <td>
                                             <div class="input-group">
                                                 <div class="input-group-prepend">
                                                     <span class="input-group-text" id="basic-addon1">SL</span>
                                                 </div>
                                                 <input type="number" min="1" v-bind:max="product.max" value="1" oninput="validity.valid||(value = 1);" class="form-control text-primary font-weight-bold" v-model="amount" v-on:keyup="sumRevenue(product.id, $event.target.value, price_out)">
                                             </div>
                                         </td>

                                         <td>
                                             <div class="input-group">
                                                 <div class="input-group-prepend">
                                                     <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                 </div>
                                                 <input type="number" min="0" class="form-control text-primary font-weight-bold" oninput="validity.valid||(value = 0);" v-model="price_out" v-on:keyup="sumRevenue(product.id, amount, $event.target.value)">
                                             </div>
                                         </td>
                                         <td>
                                             <div class="input-group">
                                                 <div class="input-group-prepend">
                                                     <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                 </div>
                                                 <div class="form-control font-weight-bold">@{{ provisional.toLocaleString() }}</div>
                                             </div>
                                         </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                </div>
                                                <div class="form-control font-weight-bold">@{{ cart.revenue.toLocaleString() }}</div>
                                            </div>
                                        </td>
                                         <td><input type="hidden" value="" class="product-id">
                                             <button class="btn btn-primary" v-on:click="addCart()" :disabled="!amount || !price_out || amount > product.max" type="button"><span class="icon-button"><i class="fe-plus"></i></span> Thêm</button>
                                         </td>
                                     </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Danh sách sản phẩm</label>
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá nhập</th>
                                    <th>Giá bán</th>
                                    <th>Thành tiền</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                                <tbody class="show-card">
                                    <tr v-for="item in carts" :key='item.id'>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">SP</span>
                                                </div>
                                                <div class="form-control font-weight-bold">@{{ item.name }}</div>
                                            </div>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">SL</span>
                                                </div>
                                                <div class="form-control font-weight-bold">@{{ item.qty }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                </div>
                                                <div class="form-control font-weight-bold">@{{ number_format(item.options.price_in) }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                </div>
                                                <div class="form-control font-weight-bold">@{{ number_format(item.price) }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                </div>
                                                <div class="form-control font-weight-bold">@{{ (item.qty*item.price).toLocaleString() }}</div>
                                            </div>
                                        </td>
                                        <td><a href="javascript:void(0)" class="btn btn-purple waves-effect waves-light" v-on:click="getItem(item.rowId)" data-toggle="modal" data-target="#item-cart"><span class="icon-button"><i class="fe-edit-2"></i></span> </a>
                                            <button type="button" class="btn btn-warning waves-effect waves-light" v-on:click="destroyItem(item.rowId)"><span class="icon-button"><i class="fe-x"></i></span> </button>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>

                        <div class="form-group" v-if="total > 0">
                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label class="font-weight-bold">Tổng tiền</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <div class="font-weight-bold form-control">@{{ number_format(total) }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="font-weight-bold">Tổng lãi</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <div class="font-weight-bold form-control">@{{ number_format(revenue_carts) }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="font-weight-bold">Công nợ</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <div class="font-weight-bold form-control">@{{ number_format(detb) }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label class="font-weight-bold">Phải trả</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <div class="font-weight-bold form-control">@{{ number_format(totalall) }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Phí vận chuyển</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <input type="number" class="form-control text-primary font-weight-bold" v-model="transport" name="transport" min="0" oninput="validity.valid||(value = 0);">
                                    </div>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Giảm giá</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <input type="number" class="form-control text-primary font-weight-bold" v-model="discount" name="discount" min="0" oninput="validity.valid||(value = 0);">
                                    </div>
                                </div>

                                <div class="col-lg-4 form-group">
                                    <label class="font-weight-bold">Thanh toán</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <input type="number" class="form-control text-primary font-weight-bold" v-model="checkout" name="checkout" min="0" oninput="validity.valid||(value = 0);" value="0">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Ghi chú</label>
                            <textarea class="form-control" onkeyup="textareaBr('#note')" id="note" v-model="note" rows="4" name="note" v-html="note"> {!! old('note') !!}</textarea>
                        </div>
                        <div class="justify-content-end" v-if="total > 0 && customer > 0"  style="display: -webkit-box">
                            <a href="#print-cart" v-on:click="printCart(customer)" class="btn btn-purple waves-effect cancel waves-light align-right" data-toggle="modal" data-target="#print-cart"><span class="icon-button"><i class="pe-7s-print"></i></span> In đơn hàng</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 text-center">
                    <div class="card-box">
                        <button type="submit" class="btn btn-purple waves-effect cancel waves-light" onclick="return confirm('Hủy toàn bộ đơn hàng?')" :disabled="total == 0" name="send" value="cancel"><span class="icon-button"><i class="pe-7s-close-circle"></i></span> Hủy đơn hàng</button>
                        <button type="submit" class="btn btn-primary waves-effect save width-md waves-light" onclick="return confirm('Xác nhận đơn hàng?')" :disabled="customer == 0 || total == 0" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </form>
        <div id="update-product" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Tên sản phẩm</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">SP</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ name_update }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Nhà cung cấp</label>
                            <select class="form-control agency-update" id="customer_update" v-model="agency_update">
                                <option value="0">--Chọn nhà cung cấp--</option>
                                <option v-for="(agency,key) in agencys" v-bind:value="key" :selected="key == agency_update">@{{ agency }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Số lượng</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">SL</span>
                                </div>
                                <input type="number" min="1" oninput="validity.valid||(value = 1);"  class="form-control text-primary font-weight-bold" v-model="amount_update">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Giá nhập</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <input type="number" min="0" oninput="validity.valid||(value = 0);" class="form-control text-primary font-weight-bold" v-model="price_update">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Thành tiền</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{provisional_update.toLocaleString()}}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Công nợ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{detb_update.toLocaleString()}}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Thanh toán</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <input type="number" oninput="validity.valid||(value = 0);"  min="0" class="form-control text-primary font-weight-bold" v-model="checkout_update">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"> Đóng</button>
                        <button type="button" class="btn btn-primary waves-effect waves-light" :disabled="agency_update == 0 || price_update == 0 || amount_update == 0 " v-on:click="updateProduct()"> <span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="item-cart" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="alert alert-icon alert-danger text-danger alert-dismissible fade show" role="alert" v-if="cart.amount > cart.max">
                            <button type="button" class="close" data-dismiss="alert"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <i class="mdi mdi-block-helper mr-2"></i>
                            <strong>Thông báo!</strong> Số lượng tối đa có thể thêm là: <span class="text-primary"> (@{{ cart.max }})</span> Vui lòng <a href="javascript:void(0)" data-toggle="modal" data-target="#update-product" v-on:click="getProduct(cart.id)" class="font-weight-bold text-purple"> [ Cập nhật ]</a> thêm số lượng sản phẩm!
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tên sản phẩm</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">SP</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ cart.name }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Số lượng</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">SL</span>
                                </div>
                                <input type="number" class="form-control text-primary font-weight-bold" oninput="validity.valid||(value = 1);" v-model="cart.amount" v-on:keyup="sumRevenueItem(cart.id,$event.target.value, cart.price)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Giá bán</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <input type="number" class="form-control text-primary font-weight-bold" oninput="validity.valid||(value = 0);" v-model="cart.price" v-on:keyup="sumRevenueItem(cart.id, cart.amount,$event.target.value)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Thành tiền</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ (cart.amount*cart.price).toLocaleString() }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tiền lãi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ number_format(cart.revenue_update) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"> Đóng</button>
                        <button type="button" :disabled="cart.amount > 0 && cart.price > 0 && cart.amount > cart.max" class="btn btn-primary waves-effect waves-light" v-on:click="updateItemCart(cart.rowId,cart.amount,cart.price,cart.revenue_update)"> <span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="print-cart" class="modal fade" v-if="total > 0" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <style type="text/css">
                            @media all {.page-break	{ display: none; page-break-before: avoid;  }}
                            @media print {
                                .page-break	{ display: none; page-break-before: avoid; }
                            }
                            @page {margin:0mm;padding:0px;font-size: 14px}
                            @page :first {margin-top: 0cm /* Top margin on first page 10cm */}

                        </style>
                        <div class="xacnhandondang" id="detailPrint">
                            <div class="CssBillPaperSize" style="background-color:white; padding-left:4px;padding-right:4px; margin-left:0px; font-family:tahoma;line-height: 18px;">
                                <div class="CssPrintRow" style="text-align:center;font-weight:bold;font-size:16px; margin-bottom: 15px">{{$setting->name}}</div>
                                <div class="CssPrintRow" style="font-size: 13px;">{!! $setting->contact !!}</div>
                                <div style="text-align:center">-----------------------------------</div>
                                <div style="font-weight:bold;font-size:16px;text-align:center;text-transform: uppercase">Hóa đơn xuất bán</div>
                                <div class="CssPrintRow" style="padding: 2px 0;font-size: 13px;">Ngày giờ: @{{ print.time }}</div>
                                <div class="CssPrintRow" style="padding: 2px 0;font-size: 13px;">Thu Ngân: Quản trị {{$setting->name}}</div>
                                {{--                                <div class="CssPrintRow">Số phiếu: #XBA.2021.1084</div>--}}
                                <div class="CssPrintRow" style="padding: 2px 0 4px 0;font-size: 13px;">Khách hàng: @{{ print.customer }}</div>
                                <div class="CssBillDetail">
                                    <table class="table table-bordered table-striped" style="width: 100%;font-size:12px;line-height: 18px;">
                                        <tbody>
                                        <tr>
                                            <th nowrap="" style="padding-right:4px;border-bottom:dotted 1px black">Tên</th>
                                            <th nowrap="" style="padding-right:4px;border-bottom:dotted 1px black">SL</th>
                                            <th nowrap="" style="padding-right:4px;border-bottom:dotted 1px black">Đ.giá</th>
                                            <th nowrap="" style="padding-right:4px;border-bottom:dotted 1px black">T.tiền</th>
                                        </tr>
                                        <tr v-for="item in carts">
                                            <th nowrap="" style="padding-right:4px;border-bottom:dotted 1px black; width:250px">@{{ item.name }}</th>
                                            <th nowrap="" style="padding-right:4px;border-bottom:dotted 1px black">@{{ item.qty }}</th>
                                            <th nowrap="" style="padding-right:4px;border-bottom:dotted 1px black">@{{ number_format(item.price) }}</th>
                                            <th nowrap="" style="padding-right:4px;border-bottom:dotted 1px black">@{{ (item.price*item.qty).toLocaleString() }}</th>
                                        </tr>
                                        <tr>
                                            <td nowrap="" colspan="3" class="CssNoLine" style="font-weight: bold">Tổng cộng </td>
                                            <td nowrap="" class="CssNoLine" style="text-align:right;font-weight: bold">@{{number_format(total)}}</td>
                                        </tr>
                                        <tr>
                                            <td class="CssNoLine" colspan="3" style="font-weight: bold">Giảm giá:</td>
                                            <td class="CssNoLine" style="text-align:right;font-weight: bold" colspan="2">@{{number_format(discount)}}</td>
                                        </tr>
                                        <tr>
                                            <td class="CssNoLine" colspan="3" style="font-weight: bold">Phí vận chuyển:</td>
                                            <td class="CssNoLine" style="text-align:right;font-weight: bold" colspan="2">@{{number_format(transport)}}</td>
                                        </tr>
                                        <tr>
                                            <td class="CssNoLine" colspan="3" style="font-weight: bold">Phải trả:</td>
                                            <td class="CssNoLine" style="text-align:right;font-weight: bold">@{{number_format(totalall)}}</td>
                                        </tr>
                                        <tr>
                                            <td class="CssNoLine" colspan="4"><span style="font-style: italic; font-weight: bold">Bằng chữ: @{{ DocTienBangChu(totalall) }}</span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="CssBillDetail" style="font-size: 12px">
                                    <strong>* Ghi chú: <div v-html="print_note" style="padding-left: 15px"></div></strong>
                                </div>
                                <div style="font-style:italic; margin-top:10px;text-align:center; font-size: 13px">Khách hàng vui lòng kiểm tra kĩ, hàng đã thanh toán, ra khỏi kho, kho không chịu trách nhiệm!</div>
                                <div style="margin-top:10px;text-align:center; font-size: 13px">Xin cảm ơn Quý khách!</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"> Đóng</button>
                        <button type="button" class="btn btn-purple waves-effect waves-light" onclick="PrintElem('#detailPrint')"><span class="icon-button"><i class="pe-7s-print"></i></span> In đơn hàng</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </div>

    <style>
        #custom-modal {
            color: #2d353c;
        }
    </style>
    <script type="text/javascript">
    var app = new Vue({
        el: '#add-cart',
        data: {
            product_id: {{Session::get('export_product') ?? 0}},
            amount : 1,
            price: 0,
            checkout: 0,
            customer: {{Session::get('customer') ?? 0}},
            price_out: 0,
            transport: 0,
            discount: 0,

            note: "",
            product:{
                id: {{@$product->id ?? 0}},
                name: '{{@$product->name}}',
                price_in: '{{@$price_in ?? 0}}',
                price_buy: '{{@$price ?? 0}}',
                max: {{@$product->amount ?? 0}},
            },
            cart:{
                id:0,
                name: 0,
                amount : 0,
                price: 0,
                rowId: 0,
                max: 0,
                revenue:0,
                revenue_update: 0,
            },

            print:{
              time: 0,
              customer: 0,
            },

            name_update: 0,
            amount_update: 1,
            price_update: 0,
            agency_update:0,
            checkout_update:0,

            selected_product: 0,

            carts: @json(Cart::instance('export')->content()),
            total: {{str_replace(',','',Cart::instance('export')->subtotal(0))}},
            users : @json($users),
            products : @json($products),
            agencys : @json($agencys),
        },
        methods: {
            sumRevenue:function(id,amount, price){
                if(amount > 0 && price > 0){
                    fetch('{{route('admin.ajax.get.revenue',[':id',':amount',':price'])}}'.replace(':id',id).replace(':amount',amount).replace(':price',price)).then(function(response){
                        return response.json().then(function(data){
                            app.cart.revenue = data;
                            //console.log(data);
                        })
                    })
                }
            },
            sumRevenueItem:function(id,amount, price){
                if(amount > 0 && price > 0){
                    fetch('{{route('admin.ajax.get.revenue',[':id',':amount',':price'])}}'.replace(':id',id).replace(':amount',amount).replace(':price',price)).then(function(response){
                        return response.json().then(function(data){
                            app.cart.revenue_update = data;
                            //console.log(data);
                        })
                    })
                }
            },
            printCart:function(customer){
              fetch('{{route('admin.ajax.get.data.print',':customer')}}'.replace(':customer',customer)).then(function(response){
                  return response.json().then(function(data){
                        app.print.time = data.time;
                        app.print.customer = data.customer.name ?? data.customer.account;
                  })
              })
            },
            getProduct:function (id){
                fetch('{{route('admin.ajax.get.product.export',':id')}}'.replace(':id',id)).then(function (response){
                    return response.json().then(function(data){
                        $('#customer_update').select2('destroy');
                        setTimeout(function(){
                            $('#customer_update').select2();
                        },0);
                        $('#item-cart').modal('hide');
                        app.name_update = data.product.name;
                        if(data.session){
                            app.agency_update = data.session.agency_id ?? 0;
                            app.price_update = data.session.price_in;
                        }
                    })
                })
            },

            updateProduct:function(){
                if(confirm('Xác nhận thông tin?')){
                    fetch("{{route('admin.ajax.update.product',[':id',':amount',':price',':checkout',':agency',':customer'])}}".replace(":id",this.product_id).replace(":amount",this.amount_update).replace(":price",this.price_update).replace(":checkout",this.checkout_update).replace(":agency",this.agency_update).replace(":customer",this.customer))
                        .then(function (response){
                            return response.json().then(function(data){
                                $('#products').select2('destroy');
                                app.products = data.lists;
                                app.selected_product = data.product.id;
                                app.product.max = data.product.amount;
                                app.product.price_in = data.price_in;
                                app.product.price_buy = data.price;
                                setTimeout(function(){
                                    $('#products').select2();
                                },0)
                                $('#update-product').modal('hide');
                                flash('success','Cập nhật số lượng thành công!');
                            })
                        })
                }
            },
            getItem: function(rowId){
                fetch('{{route('admin.ajax.get.item.export',':rowId')}}'.replace(':rowId',rowId)).then(function (reponse){
                    return reponse.json().then(function(data){
                        app.cart.id = data.item.id;
                        app.cart.name = data.item.name;
                        app.cart.amount = data.item.qty;
                        app.cart.revenue_update = data.item.options.revenue;
                        app.cart.price = data.item.price;
                        app.cart.rowId = data.item.rowId;
                        app.cart.max = data.product.amount
                    });
                })
            },
            updateItemCart:function (rowId,amount,price,revenue){
                if(confirm('Xác nhận thông tin?')) {
                    fetch('{{route('admin.ajax.update.item.export',[':rowId',':amount',':price',':revenue'])}}'.replace(':rowId', rowId).replace(':amount', amount).replace(':price', price).replace(':revenue', revenue)).then(function (response) {
                        return response.json().then(function (data) {
                            if(data == 'error'){
                                flash('error', 'Số lượng trong kho không đủ. Vui lòng cập nhật thêm!');
                            }else{
                                app.carts = data.cart;
                                app.total = data.total.replaceAll(',','');
                                $('#item-cart').modal('hide');
                                flash('success', 'Cập nhật thành công!');
                            }
                        })
                    })
                }
            },
            destroyItem:function(rowId){
                if(confirm('Xóa sản phẩm')) {
                    fetch('{{route('admin.ajax.destroy.item.export',':rowId')}}'.replace(":rowId", rowId)).then(function (reponse) {
                        return reponse.json().then(function (data) {
                            app.carts = data.cart;
                            app.total = data.total;
                            flash('success', 'Xóa sản phẩm thành công!');
                        });
                    })
                }
            },
            addCart: function(){
                console.log(this.cart.revenue);
                fetch('{{route('admin.ajax.export.product',[':id',':amount',':price',':revenue'])}}'.replace(":id",this.product_id).replace(":amount",this.amount).replace(":price",this.price_out).replace(":revenue",this.cart.revenue)).then(function(reponse){
                    return reponse.json().then(function(data){
                        console.log(data);
                        if(data == 'max'){
                            flash('error','Tồn kho không đủ! Vui lòng cập nhật thêm!');
                        }else{
                            app.carts = data.cart;
                            app.total = data.total;
                            flash('success','Thêm sản phẩm thành công!');
                        }
                    });
                })
            },
            choiseProduct:function(){
                fetch("{{route('admin.ajax.choise.export.product',[':id',':user'])}}".replace(":id",this.product_id).replace(":user",this.customer))
                    .then(function (response){
                    return response.json().then(function(data){
                       app.product.id = data.product.id;
                       app.product.name = data.product.name;
                       app.product.price_in = data.price_in;
                       app.product.price_buy = data.price;
                       app.product.max = data.product.amount;
                       app.price_update = data.price_in;
                       app.amount = 1;
                        app.price_out = 0;
                       $('#customer_update').select2();
                    });
                })
            },
            choiseCustomer:function(){
                fetch("{{route('admin.ajax.choise.user',[':id',':product'])}}".replace(":id",this.customer).replace(":product",this.product_id))
                    .then(function (response){
                        return response.json().then(function(data){
                            app.customer = data.customer;
                            app.product.id = data.product.id;
                            app.product.name = data.product.name;
                            app.product.price_in = data.price_in;
                            app.product.price_buy = data.price;
                            app.product.max = data.product.amount;
                            app.price_update = data.price_in;
                            app.amount = 1;
                            app.price_out = 0;
                            $('#customer_update').select2();
                        });
                    })
            }

        },
        watch: {
            product_id: function(val){
                this.product_id = val;
                if(val > 0 && this.customer > 0){
                    this.choiseProduct();
                }
            },
            customer: function(val){
                this.customer = val;
                if(val > 0 && this.product_id > 0){
                    this.choiseProduct();
                }
            },
            amount: function(val){
                this.amount = val;
            },
            price: function(val){
                this.price = val;
            },
            checkout:function(val){
                this.checkout = val;
            },
            price_out:function (val){
                this.price_out = val;
            },
            agency_update:function(val){
              this.agency_update = val;
            },
            price_update:function(val){
                this.price_update = val;
            },
            amount_update:function(val){
                this.amount_update = val;
            },
            checkout_update:function(val){
                this.checkout_update = val;
            },
            transport:function(val){
                this.transport = val;
            }
        },
        computed:{
            totalall:function(){
                return Number(this.total) + Number(this.transport) - Number(this.discount);
            },
            detb: function(){
                return Number(Number(this.total) + Number(this.transport)) - Number(this.checkout) - Number(this.discount);
            },
            provisional:function(){
                return this.amount * this.price_out;
            },
            provisional_update:function(){
                return this.amount_update * this.price_update;
            },
            detb_update:function(){
                return this.amount_update * this.price_update - this.checkout_update;
            },
            print_note:function(){
                return nl2br(this.note);
            },
            revenue:function(){
              return Number(this.amount * this.price_out) - Number(this.product.price_in * this.amount);
            },
            revenue_carts:function(){
                let revenue = 0;
                $.each(this.carts,function(key,value){
                    revenue += Number(value.options.revenue);
                })
                return revenue;
            }
        },
    })

    function touchspin(){
        let input = $('input[type=number]');
        $.each(input,function(){
          return $(this).TouchSpin({
              buttondown_class:"btn btn-primary",
              buttonup_class:"btn btn-primary",
              min: $(this).attr('min') || 1,
              max: $(this).attr('max') || null,
          });
        })
    }
    function js_select2(){
        return $("#customer, #products, #customer_update").select2()
            .on("select2:select", e => {
                const event = new Event("change", { bubbles: true, cancelable: true });
                e.params.data.element.parentElement.dispatchEvent(event);
            })
            .on("select2:unselect", e => {
                const event = new Event("change", { bubbles: true, cancelable: true });
                e.params.data.element.parentElement.dispatchEvent(event);
            });
    }
    function show_box_update(){
        $('#item-cart').modal('hide');
        $('.box-update-product').slideToggle();
    }
    $(document).ready(function() {
        //touchspin();
        js_select2();
        $('.box-update-product').hide();

    });
</script>

@stop

@section('javascript')

    <script src="{{asset('admin/assets/libs/switchery/switchery.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/libs/select2/select2.min.js"></script>
    {{--    <script src="{{asset('admin/assets/libs/jquery-mockjax/jquery.mockjax.min.js')}}"></script>--}}
    <script src="{{asset('admin/assets/libs/autocomplete/jquery.autocomplete.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-filestyle2/bootstrap-filestyle.min.js')}}"></script>

    <script src="{{asset('admin/assets/libs/custombox/custombox.min.js')}}"></script>
    <!-- Init js-->
    <script src="{{asset('admin/assets/js/pages/form-advanced.init.js')}}"></script>
    <!-- Summernote js -->
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/libs/summernote/summernote-bs4.min.js"></script>

    <!-- Init js -->
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/js/pages/form-summernote.init.js"></script>

    <!-- scrollbar init-->
    <script src="{{asset('admin/assets/js/pages/scrollbar.init.js')}}"></script>
@stop

@section('css')
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/custombox/custombox.min.css')}}" rel="stylesheet" type="text/css" >
    <!-- Summernote css -->
    <link href="https://coderthemes.com/adminox/layouts/vertical/assets/libs/summernote/summernote-bs4.css" rel="stylesheet" type="text/css" />

@stop
