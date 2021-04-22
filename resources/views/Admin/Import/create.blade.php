@extends('Admin.Layout.layout')
@section('title')
    Thêm mới
@stop
@section('content')

    <div class="container-fluid" id="import-product">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Thêm mới</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <form method="post" action="{{route('admin.imports.store')}}" novalidate enctype="multipart/form-data">
            <div class="row">
                @csrf
                <div class="col-lg-12">
                    <div class="card-box">
                        <div class="form-group">
                            <label>Nhà cung cấp</label>

                            <select class="form-control agencys" v-model="agency_id" v-on:change="choiseAgency()" name="agency" id="agencys">
                                <option value="0">--Chọn nhà cung cấp--</option>
                                <option v-for="item in agencys" v-bind:value="item.id" >@{{ item.name }}</option>
                            </select>
                            <p v-if="agency_id == 0" class="text-danger mt-1">Vui lòng chọn nhà cung cấp</p>
                        </div>
                        <div class="form-group">
                            <label>Tên sản phẩm <span class="required">*</span></label>
                            <select class="form-control choise-product" v-model="product_id" :disabled="agency_id == 0" id="products" v-on:change="choiseProduct()">
                                <option value="0">--Chọn sản phẩm--</option>
                                <option v-for="product in products" v-bind:value="product.id" >@{{ product.name }} (@{{ product.amount }})</option>
                            </select>
                            <p v-if="product_id == 0" class="text-danger mt-1">Vui lòng chọn sản phẩm</p>
                        </div>

                        <div class="form-group">
                            <label>Thông tin sản phẩm <span class="required">*</span></label>
                            <div class="product-info" id="product_info">
                                <table class="table table-bordered table-hover bs-table" id="table-append">
                                    <thead>
                                    <tr>
                                        <th>Tên sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá nhập</th>
                                        <th>Thành tiền</th>
                                        <th>Hành động</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="product_id > 0  && agency_id > 0">
                                        <td>
                                            <div class="font-weight-bold mb-1"> @{{ product.name }}</div>
                                            <div class="text-primary">[Giá nhập lần trước: <span class="text-danger">@{{product.price_in.toLocaleString()}}</span>]</div>
                                            <div class="text-primary">[Giá nhập gần nhất: <span class="text-danger">@{{price_in.toLocaleString()}}</span>]</div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">SL</span>
                                                </div>
                                                <input type="number" min="1" value="1" oninput="validity.valid||(value = 1);" class="form-control text-primary font-weight-bold" v-model="amount">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                </div>
                                                <input type="number" min="0" class="form-control text-primary font-weight-bold price" oninput="validity.valid||(value = 0);" v-model="price_in">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                </div>
                                                <div class="font-weight-bold form-control">@{{ provisional.toLocaleString() }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary" v-on:click="addCart()" :disabled="!amount || !price_in" type="button"><span class="icon-button"><i class="fe-plus"></i></span> Thêm</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Danh sách sản phẩm</label>
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Tên sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá nhập</th>
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
                                    </td>
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
                                            <div class="form-control font-weight-bold">@{{ item.price.toLocaleString() }}</div>
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

                                    <td><a href="javascript:void(0)" class="btn btn-primary waves-effect waves-light" v-on:click="getItem(item.rowId)" data-toggle="modal" data-target="#item-cart"><span class="icon-button"><i class="fe-edit-2"></i></span> </a>
                                        <button type="button" class="btn btn-warning waves-effect waves-light" v-on:click="destroyItem(item.rowId)"><span class="icon-button"><i class="fe-x"></i></span> </button>
                                    </td>
                                </tr>

                                </tbody>
                            </table>

                            <div id="item-cart" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Tên sản phẩm</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">SP</span>
                                                    </div>
                                                    <div class="form-control font-weight-bold">@{{ cart.name }}</div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Số lượng</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">SL</span>
                                                    </div>
                                                    <input type="number" class="form-control text-primary font-weight-bold" oninput="validity.valid||(value = 1);" v-model="cart.amount">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Giá nhập</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                    </div>
                                                    <input type="number" class="form-control text-primary font-weight-bold" oninput="validity.valid||(value = 0);" v-model="cart.price">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Thành tiền</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                    </div>
                                                    <div class="form-control font-weight-bold">@{{ (cart.amount*cart.price).toLocaleString() }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"> Đóng</button>
                                            <button type="button" class="btn btn-primary waves-effect waves-light" v-on:click="updateItemCart(cart.rowId,cart.amount,cart.price)"> <span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <label>Tổng tiền</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <div class="form-control font-weight-bold">@{{ totalall.toLocaleString() }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label>Thanh toán</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <input type="number" class="form-control text-primary font-weight-bold" name="checkout" v-model="checkout" min="0" oninput="validity.valid||(value = 0);">
                                    </div>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <label>Công nợ</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <div class="form-control font-weight-bold">@{{ detb.toLocaleString() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="note-group">
                            <label>Ghi chú</label>
                            <textarea class="form-control" rows="4" name="note"> {!! old('note') !!}</textarea>
                        </div>
                    </div>

                </div>
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-default waves-effect cancel waves-light" onclick="return confirm('Hủy toàn bộ đơn hàng?')" :disabled="carts.length == 0" name="send" value="cancel"><span class="icon-button"><i class="pe-7s-close-circle"></i></span> Hủy đơn hàng</button>
                    <button type="submit" class="btn btn-primary waves-effect save width-md waves-light float-right" onclick="return confirm('Xác nhận đơn hàng?')" :disabled="carts.length == 0" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                </div>
            </div>
            <!-- end row -->
        </form>
    </div>
<script>

   var app = new Vue({
        el:"#import-product",
        data:{
            agencys: @json($agencys),
            products: @json($products),
            carts: @json(Cart::instance('import')->content()->sort()),
            total: {{str_replace(',','',Cart::subtotal(0))}},

            product_id: 0,
            agency_id: {{$agency}},
            product:{
                id: 0,
                name: 0,
                price_in: 0,
                price_buy:0,
                max: 0,
            },
            cart:{
                name: 0,
                amount : 0,
                price: 0,
                rowId: 0,
            },
            amount : 1,
            price: 0,
            checkout: 0,
            price_in: 0,
        },
        methods:{
            destroyItem:function(rowId){
                if(confirm('Xóa sản phẩm')) {
                    fetch('{{route('admin.ajax.destroy.item.import',':rowId')}}'.replace(":rowId", rowId)).then(function (reponse) {
                        return reponse.json().then(function (data) {
                            app.carts = data.cart;
                            app.total = data.total.replaceAll(',','');
                            flash({'message': 'Xóa sản phẩm thành công!','type': 'success'});
                        });
                    })
                }
            },
            addCart: function(){
                fetch('{{route('admin.ajax.import.product',[':id',':amount',':price'])}}'.replace(":id",this.product_id).replace(":amount",this.amount).replace(":price",this.price_in)).then(function(reponse){
                    return reponse.json().then(function(data){
                        app.carts = data.cart;
                        app.total = data.total.replaceAll(',','');
                        flash({'message': 'Thêm sản phẩm thành công!','type': 'success'});
                    });
                })
            },
            getItem: function(rowId){
                fetch('{{route('admin.ajax.get.item.import',':rowId')}}'.replace(':rowId',rowId)).then(function (reponse){
                    return reponse.json().then(function(data){
                        app.cart.name = data.name;
                        app.cart.amount = data.qty;
                        app.cart.price = data.price;
                        app.cart.rowId = data.rowId;
                    });
                })
            },
            updateItemCart:function (rowId,amount,price){
                if(confirm('Xác nhận thông tin?')) {
                    fetch('{{route('admin.ajax.update.item.import',[':rowId',':amount',':price'])}}'.replace(':rowId', rowId).replace(':amount', amount).replace(':price', price)).then(function (response) {
                        return response.json().then(function (data) {
                            app.carts = data.cart;
                            app.total = data.total.replaceAll(',','');
                            $('#item-cart').modal('hide');
                            flash({'message': 'Cập nhật thành công!','type': 'success'});
                        })
                    })
                }
            },

            choiseProduct:function(){
                fetch("{{route('admin.ajax.choise.product',[':id',':agency'])}}".replace(":id",this.product_id).replace(":agency",this.agency_id))
                    .then(function (response){
                        return response.json().then(function(data){
                            app.product.name = data.product.name;
                            app.product.price_in = data.price;
                            app.price_in = data.price_in;
                            app.amount = 1;
                        });
                    })
            },
            choiseAgency:function(){
                fetch("{{route('admin.ajax.choise.agency',[':id',':product'])}}".replace(":id",this.agency_id).replace(":product",this.product_id))
                    .then(function (response){
                        return response.json().then(function(data){
                            app.agency_id = data.agency;
                            app.product.name = data.product.name;
                            app.product.price_in = data.price;
                            app.price_in = data.price_in;
                            app.amount = 1;
                        });
                    })
            }
        },
        wacth:{
            product_id: function(val){
                this.product_id = val;
                if(val > 0 && this.customer > 0){
                    this.choiseProduct();
                }
            },
            agency_id: function(val){
                this.agency_id = val;
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
            price_in:function (val){
                this.price_in = val;
            },
        },
        computed:{
            totalall:function(){
                return number_format(this.total);
            },
            detb: function(){
                return Number(this.total) - Number(this.checkout);
            },
            provisional:function(){
                return this.amount * this.price_in;
            },
        }
    })
    jQuery(document).ready(function($) {
        js_select2();
    })
   function js_select2(){
       return $("#agencys, #products").select2()
           .on("select2:select", e => {
               const event = new Event("change", { bubbles: true, cancelable: true });
               e.params.data.element.parentElement.dispatchEvent(event);
           })
           .on("select2:unselect", e => {
               const event = new Event("change", { bubbles: true, cancelable: true });
               e.params.data.element.parentElement.dispatchEvent(event);
           });
   }
</script>
@stop

@section('javascript')

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{asset('admin/js/dynamicrows/dynamicrows.js')}}"></script>

    <script src="{{asset('admin/assets/libs/switchery/switchery.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/libs/select2/select2.min.js"></script>
    {{--    <script src="{{asset('admin/assets/libs/jquery-mockjax/jquery.mockjax.min.js')}}"></script>--}}
    <script src="{{asset('admin/assets/libs/autocomplete/jquery.autocomplete.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-filestyle2/bootstrap-filestyle.min.js')}}"></script>

    <!-- Init js-->
    <script src="{{asset('admin/assets/js/pages/form-advanced.init.js')}}"></script>

    <!-- Summernote js -->
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/libs/summernote/summernote-bs4.min.js"></script>

    <!-- Init js -->
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/js/pages/form-summernote.init.js"></script>
    <script>$(function() {
            $('[data-dynamicrows]').dynamicrows({
                animation: 'fade',
                copyValues: true,
                minrows: 1
            });
        });
    </script>

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

    <!-- Summernote css -->
    <link href="https://coderthemes.com/adminox/layouts/vertical/assets/libs/summernote/summernote-bs4.css" rel="stylesheet" type="text/css" />

@stop
