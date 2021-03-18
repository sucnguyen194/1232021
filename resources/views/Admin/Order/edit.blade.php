@extends('Admin.Layout.layout')
@section('title')
    Thông tin đơn hàng #{{$order->id}}
@stop
@section('content')
    <div class="container-fluid" id="edit-order">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.orders.index')}}">Danh sách xuất hàng</a></li>
                            <li class="breadcrumb-item active">Thông tin đơn hàng #{{$order->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Thông tin đơn hàng #{{$order->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <section>
            <div class="card-box">
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-2 font-weight-bold">Ngày xuất hàng</div>
                        <div class="col-lg-10 font-weight-bold">{{$order->created_at->format('d/m/Y H:i')}}</div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-2 font-weight-bold">Người xuất hàng</div>
                        <div class="col-lg-10 font-weight-bold"><a href="{{route('admin.user.index',['id' => $order->user->id ?? 0])}}" target="_blank">{{$order->user->name ?? 'Tên trống hoặc đã xóa'}}</a> </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-2 font-weight-bold">Khách hàng</div>
                        <div class="col-lg-10 font-weight-bold"><a href="{{route('admin.user.index',['id' => $order->customer->id ?? 0])}}" target="_blank">{{$order->customer->name ?? 'Tên trống hoặc đã xóa'}}</a> </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-12 font-weight-bold">Ghi chú</div>
                        @if($order->note)
                            <div class="col-lg-12 mt-2 font-weight-bold">
                                <textarea class="form-control" readonly rows="7">{!! $order->note !!}</textarea>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group-end">
                    <button type="button" class="btn btn-purple waves-effect waves-light" data-toggle="modal" data-target="#myModal"><span class="icon-button"><i class="fe-edit-2"></i></span> Sửa</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onclick="open_bonus()"><span class="icon-button"><i class="fe-plus"></i></span> Bổ xung đơn hàng</button>
                </div>
            </div>
        </section>
        <form method="post" action="{{route('admin.orders.update',$order)}}">
            @csrf
            @method('PUT')
        <section class="bonus-cart">
            <div class="card-box">
                <!-- BONUS ORDER -->
                    <div class="form-group">
                        <label class="font-weight-bold">Tên sản phẩm <span class="required">*</span></label>
                        <select class="form-control choise-product" id="products" :disabled="action.sessions.customer == 0" v-on:change="choiseProduct()" v-model="action.products.id">
                            <option value="0">--Chọn sản phẩm--</option>
                            <option v-for="item in products" v-bind:value="item.id">
                                @{{ item.name }} (@{{  item.amount }})
                            </option>
                        </select>
                        <p v-if="action.products.id == 0" class="text-danger font-weight-bold mt-1">Vui lòng chọn sản phẩm</p>
                    </div>
                    <div class="form-group" v-if="action.products.id > 0">
                        <label class="font-weight-bold">Thông tin sản phẩm</label>
                        <div class="alert alert-icon alert-danger text-danger alert-dismissible fade show" role="alert" v-if="action.products.amount > action.products.max">
                            <button type="button" class="close" data-dismiss="alert"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <i class="mdi mdi-block-helper mr-2"></i>
                            <strong>Thông báo!</strong> Số lượng tối đa có thể thêm là: <span class="text-primary"> (@{{ action.products.max }})</span> Vui lòng <span class="text-purple">[ Cập nhật ]</span> thêm số lượng sản phẩm!
                        </div>
                        <div class="product-info" id="product_info" v-if="action.products.id > 0">
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
                                <tr>
                                    <td><div class="font-weight-bold mb-1"> @{{ action.products.name }} <a href="javascript:void(0)" v-if="action.products.amount > action.products.max" data-toggle="modal" v-on:click="getProduct(action.products.id)" data-target="#update-product" class="font-weight-bold text-purple"> [ Cập nhật ]</a></div>
                                        <div class="text-primary font-weight-bold">[Giá nhập: <strong class="text-danger">@{{ number_format(action.products.price_in) }}</strong>]</div>
                                        <div class="text-primary font-weight-bold">[Giá bán gần nhất: <strong class="text-danger">@{{ number_format(action.products.price_buy) }}</strong>]</div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">SL</span>
                                            </div>
                                            <input type="number" min="1" oninput="validity.valid||(value = 1);" class="form-control text-primary font-weight-bold" v-model="action.products.amount" v-on:keyup="sumRevenue(action.products.id, $event.target.value, action.products.price)">
                                        </div>
                                    </td>

                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                            </div>
                                            <input type="number" min="0" class="form-control text-primary font-weight-bold" v-model="action.products.price" oninput="validity.valid||(value = 0);" v-on:keyup="sumRevenue(action.products.id, action.products.amount, $event.target.value)">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                            </div>
                                            <div class="form-control font-weight-bold">@{{ number_format(action.products.amount * action.products.price ) }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                            </div>
                                            <div class="form-control font-weight-bold">@{{ number_format(action.carts.revenue) }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary" v-on:click="addCart()" :disabled="action.products.amount == 0 || action.products.price == 0 || action.products.amount > action.products.max" type="button"><span class="icon-button"><i class="fe-plus"></i></span> Thêm</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="" v-if="action.products.id > 0">
                        <label class="font-weight-bold">Danh sách sản phẩm (bổ xung)</label>
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá nhập</th>
                                <th>Giá bán</th>
                                <th>Thành tiền</th>
                                <th>Tiền lãi</th>
                                <th>Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="cart in carts">
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">SP</span>
                                        </div>
                                        <div class="form-control font-weight-bold">@{{ cart.name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">SL</span>
                                        </div>
                                        <div class="form-control font-weight-bold">@{{ cart.qty }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <div class="form-control font-weight-bold">@{{ number_format(cart.options.price_in) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <div class="form-control font-weight-bold">@{{ number_format(cart.price) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <div class="form-control font-weight-bold">@{{ number_format(cart.price * cart.qty) }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                        </div>
                                        <div class="form-control font-weight-bold">@{{ number_format(cart.options.revenue) }}</div>
                                    </div>
                                </td>
                                <td><a href="javascript:void(0)" class="btn btn-purple waves-effect waves-light" v-on:click="getItemCart(cart.rowId)" data-toggle="modal" data-target="#item-cart"><span class="icon-button"><i class="fe-edit-2"></i></span> </a>
                                    <button type="button" class="btn btn-warning waves-effect waves-light" v-on:click="destroyItemCart(cart.rowId)"><span class="icon-button"><i class="fe-x"></i></span> </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- END BONUS ORDER -->
            </div>
        </section>
        <section>
            <div class="card-box" v-if="action.sessions.total > 0">
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
                            <th>Tiền lãi</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="session in sessions">
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">SP</span>
                                    </div>
                                    <div class="form-control font-weight-bold" v-if="session.product">@{{session.product.name}}</div>
                                    <div class="form-control font-weight-bold" v-else="session.product">Đã xóa</div>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">SL</span>
                                    </div>
                                    <div class="form-control font-weight-bold">@{{ session.amount }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                    </div>
                                    <div class="form-control font-weight-bold">@{{ number_format(session.price_in) }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                    </div>
                                    <div class="form-control font-weight-bold">@{{ number_format(session.price) }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                    </div>
                                    <div class="form-control font-weight-bold">@{{ number_format(session.price * session.amount) }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                    </div>
                                    <div class="form-control font-weight-bold">@{{ number_format((session.revenue)) }}</div>
                                </div>
                            </td>
                            <td><a href="javascript:void(0)" class="btn btn-purple waves-effect waves-light" v-on:click="getItemSession(session.id)" data-toggle="modal" data-target="#item-session"><span class="icon-button"><i class="fe-edit-2"></i></span> </a>
                                <button type="button" class="btn btn-warning waves-effect waves-light" v-on:click="destroyItemSession(session.id)"><span class="icon-button"><i class="fe-x"></i></span> </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="form-group" v-if="action.sessions.total > 0">
                    <p>1. Thông số bên dưới bao gồm thông tin của đơn hàng và danh sách bổ sung (nếu có).</p>
                    <p>2. Cick vào <strong>In đơn hàng</strong> hoặc <strong>In đơn bổ xung</strong> để xem thông số riêng của mỗi loại.</p>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Tổng tiền</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ number_format(total_bonus) }}</div>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Tổng lãi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ number_format(revenue_session) }}</div>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Công nợ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ number_format(debt_session) }}</div>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label class="font-weight-bold">Phải trả</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ number_format(total_all) }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label class="font-weight-bold">Phí vận chuyển</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>

                                <input type="number" class="form-control text-primary font-weight-bold" name="transport" min="0" v-bind:value="action.sessions.transport" v-model="action.sessions.transport">
                            </div>
                        </div>

                        <div class="col-lg-4 form-group">
                            <label class="font-weight-bold">Giảm giá</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>

                                <input type="number" class="form-control text-primary font-weight-bold" name="discount" min="0" v-bind:value="action.sessions.discount" v-model="action.sessions.discount">
                            </div>
                        </div>


                        <div class="col-lg-4 form-group">
                            <label class="font-weight-bold">Thanh toán</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>

                                <input type="number" class="form-control text-primary font-weight-bold" name="checkout" min="0" v-bind:value="action.sessions.checkout" v-model="action.sessions.checkout">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="justify-content-end" style="display: -webkit-box" v-if="action.sessions.total > 0">
                    <a href="#print-order" id="tooltip-hover-session" title="* Danh sách sản phẩm đã lên đơn!" v-on:click="printCart(action.sessions.customer)" class="btn btn-purple waves-effect cancel waves-light align-right mb-1 mr-2" data-toggle="modal" data-target="#print-order"><span class="icon-button"><i class="pe-7s-print"></i></span> In đơn hàng</a>

                    <a href="#print-cart" v-if="action.carts.total > 0"  id="tooltip-hover" title="* Danh sách sản phẩm được bổ xung (tạm tính)!"  v-on:click="printCart(action.sessions.customer)" class="btn btn-purple waves-effect cancel waves-light mb-1 tooltip-hover" data-toggle="modal" data-target="#print-cart"><span class="icon-button"><i class="pe-7s-print"></i></span> In đơn bổ xung</a>
                </div>

            </div>
            <div class="card-box text-center" v-if="action.sessions.total > 0">
                <a href="{{route('admin.orders.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                <button type="submit" class="btn btn-primary waves-effect save width-md waves-light" onclick="return confirm('Xác nhận thông tin?')" name="send" value="checkout"><span class="icon-button"><i class="fe-plus"></i></span> Cập nhật đơn hàng</button>
            </div>
        </section>

        </form>
        <!-- end section -->
        <div id="item-session" class="modal fade item-session" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="alert alert-icon alert-danger text-danger alert-dismissible fade show" role="alert" v-if="action.sessions.amount > action.sessions.max">
                            <i class="mdi mdi-block-helper mr-2"></i>
                            <strong>Thông báo!</strong> Số lượng tối đa có thể thêm là: <span class="text-primary"> (@{{ action.sessions.max }})</span> Vui lòng <a href="javascript:void(0)" data-toggle="modal" data-target="#update-product" class="font-weight-bold text-purple"> [ Cập nhật ]</a> thêm số lượng sản phẩm!
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tên sản phẩm</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">SP</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ action.sessions.name }} (@{{action.sessions.max}})</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Giá nhập</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ number_format(action.sessions.price_in) }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Số lượng</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">SL</span>
                                </div>
                                <input type="number" class="form-control text-primary font-weight-bold" oninput="validity.valid||(value = 1);" v-model="action.sessions.amount" v-on:keyup="getRevenueSession(action.sessions.id, $event.target.value, action.sessions.price)">
                            </div>
                            <p class="font-weight-bold text-danger mt-2" v-if="action.sessions.amount == 0"> Số lượng sản phẩm lớn hơn <span class="text-primary">0</span></p>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Giá bán</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <input type="number" class="form-control text-primary font-weight-bold" oninput="validity.valid||(value = 1);" v-model="action.sessions.price" v-on:keyup="getRevenueSession(action.sessions.id, action.sessions.amount, $event.target.value)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Thành tiền</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ number_format(action.sessions.price * action.sessions.amount) }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tiền lãi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ number_format( action.sessions.revenue_item ) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"> Đóng</button>
                        <button type="button" :disabled="action.sessions.id == 0 || action.sessions.amount == 0 || action.sessions.amount > action.sessions.max" v-on:click="updateItemSession(action.sessions.id, action.sessions.amount, action.sessions.price, action.sessions.revenue_item)" class="btn btn-primary waves-effect waves-light"> <span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="item-cart" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="alert alert-icon alert-danger text-danger alert-dismissible fade show" role="alert" v-if="action.carts.qty > action.carts.max">
                            <button type="button" class="close" data-dismiss="alert"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <i class="mdi mdi-block-helper mr-2"></i>
                            <strong>Thông báo!</strong> Số lượng tối đa có thể thêm là: <span class="text-primary"> (@{{ action.carts.max }})</span> Vui lòng <a href="javascript:void(0)" data-toggle="modal" data-target="#update-product" v-on:click="getProduct(action.carts.id)" class="font-weight-bold text-purple"> [ Cập nhật ]</a> thêm số lượng sản phẩm!
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tên sản phẩm</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">SP</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ action.carts.name }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Giá nhập</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="font-weight-bold form-control">@{{ number_format(action.carts.price_in) }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Số lượng</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">SL</span>
                                </div>
                                <input type="number" class="form-control text-primary font-weight-bold" v-model="action.carts.qty" oninput="validity.valid||(value = 1);" v-on:keyup="sumRevenueItem(action.carts.id, $event.target.value, action.carts.price)">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Giá bán</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <input type="number" name="price" class="form-control text-primary font-weight-bold" v-model="action.carts.price" oninput="validity.valid||(value = 0);" v-on:keyup="sumRevenueItem(action.carts.id, action.carts.qty, $event.target.value)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Thành tiền</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="font-weight-bold form-control">@{{ number_format(action.carts.qty * action.carts.price) }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tiền lãi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="font-weight-bold form-control">@{{ number_format(action.carts.revenue_update) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"> Đóng</button>
                        <button type="submit" class="btn btn-primary waves-effect waves-light" v-on:click="updateItemCart(action.carts.rowId, action.carts.qty, action.carts.price, action.carts.revenue_update)"> <span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <form method="POST" action="{{route('admin.orders.update',$order)}}">
                @csrf
                @method('PUT')
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="font-weight-bold">Người xuất hàng</label>
                                <select class="form-control" name="user" id="admins">
                                    <option value="0">--Chọn người xuất hàng--</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" {{$order->user_create == $user->id ? "selected" : ""}}>{{$user->name ?? $user->account}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Khách hàng</label>
                                <select class="form-control" name="customer" id="customers">
                                    <option value="0">--Chọn khách hàng--</option>
                                    @foreach($customers as $customer)
                                        <option value="{{$customer->id}}" {{$order->user_id == $customer->id ? "selected" : ""}}>{{$customer->name ?? $customer->account}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="note-group">
                                <label class="font-weight-bold">Ghi chú</label>
                                <textarea class="form-control" rows="4" name="note"> {!! $order->note !!}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"> Đóng</button>
                            <button type="submit" name="send" value="update" class="btn btn-primary waves-effect waves-light" onclick="return confirm('Xác nhận thông tin?')"> <span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </form>
        </div><!-- /.modal -->

        <div id="print-cart" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <div class="CssPrintRow" style="padding: 2px 0;font-size: 13px;">Ngày giờ: @{{ action.print.time }}</div>
                                <div class="CssPrintRow" style="padding: 2px 0;font-size: 13px;">Thu Ngân: Quản trị {{$setting->name}}</div>
                                {{--                                <div class="CssPrintRow">Số phiếu: #XBA.2021.1084</div>--}}
                                <div class="CssPrintRow" style="padding: 2px 0 4px 0;font-size: 13px;">Khách hàng: @{{ action.print.name }}</div>
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
                                            <th nowrap="" style="padding-right: 4px; border-bottom: 1px dotted black; width: 250px;">@{{ item.name }}</th>
                                            <th nowrap="" style="padding-right:4px;border-bottom:dotted 1px black">@{{ item.qty }}</th>
                                            <th nowrap="" style="padding-right:4px;border-bottom:dotted 1px black">@{{ number_format(item.price) }}</th>
                                            <th nowrap="" style="padding-right:4px;border-bottom:dotted 1px black">@{{ (item.price*item.amount).toLocaleString() }}</th>
                                        </tr>
                                        <tr>
                                            <td nowrap="" colspan="3" class="CssNoLine" style="font-weight: bold">Tổng cộng </td>
                                            <td nowrap="" class="CssNoLine" style="text-align:right; font-weight: bold">@{{number_format(action.carts.total)}}</td>
                                        </tr>

                                        <tr>
                                            <td class="CssNoLine" colspan="3" style="font-weight: bold">Phải trả:</td>
                                            <td class="CssNoLine" style="text-align:right; font-weight: bold">@{{number_format(action.carts.total)}}</td>
                                        </tr>
                                        <tr>
                                            <td class="CssNoLine" colspan="4"><span style="font-style: italic; font-weight: bold">Bằng chữ: @{{ DocTienBangChu(action.carts.total) }}</span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="CssBillDetail" style="font-size: 12px">
                                    <strong>* Ghi chú: <div v-html="action.print.note" style="padding-left: 15px"></div></strong>
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
        </div><!-- /.modal -->
        <div id="update-product" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tên sản phẩm</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">SP</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{ action.update.name }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nhà cung cấp</label>
                            <select class="form-control agency-update" id="customer_update" v-model="action.update.agency">
                                <option value="0">--Chọn nhà cung cấp--</option>
                                <option v-for="(agency,key) in agencys" v-bind:value="key">@{{ agency }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Số lượng</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">SL</span>
                                </div>
                                <input type="number" min="1" oninput="validity.valid||(value = 1);"  class="form-control text-primary font-weight-bold" v-model="action.update.amount">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Giá nhập</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <input type="number" min="0" oninput="validity.valid||(value = 0);" class="form-control text-primary font-weight-bold" v-model="action.update.price_in">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Thành tiền</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{provisional_update.toLocaleString()}}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Công nợ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <div class="form-control font-weight-bold">@{{detb_update.toLocaleString()}}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Thanh toán</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <input type="number" oninput="validity.valid||(value = 0);"  min="0" class="form-control text-primary font-weight-bold" v-model="action.update.checkout">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal"> Đóng</button>
                        <button type="button" class="btn btn-primary waves-effect waves-light" :disabled="action.update.agency == 0 || action.update.price_in  == 0 || action.update.amount == 0" v-on:click="updateProduct()"> <span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>

<script>

    var app = new Vue({
        el:'#edit-order',
        data:{
            sessions: @json($order->sessions()->get()->load('product')),
            carts: @json(Cart::instance('export_'.$order->id)->content()),
            products: @json($products),
            agencys: @json($agencys),
            order_id: {{$order->id}},
            action: {
                sessions:{
                    id: 0,
                    name: 0,
                    amount: 1,
                    price: 0,
                    price_buy: 0,
                    max: 0,
                    total: {{$order->total}},
                    checkout: {{$order->checkout}},
                    transport: {{$order->transport}},
                    revenue: {{$order->revenue}},
                    debt: {{$order->debt}},
                    user: {{$order->user_create}},
                    customer: {{$order->user_id}},
                    revenue_item: 0,
                    discount_default: {{$order->discount}},
                    discount: {{$order->discount}},
                },
                print:{
                    note: '{!! $order->note !!}',
                    time: 0,
                    name: 0,
                },
                products: {
                    id: {{@$product->id ?? 0}},
                    name: '{{@$product->name}}',
                    price_in: '{{@$price_in}}',
                    price_buy: '{{@$price}}',
                    max: {{@$product->amount ?? 1}},
                    amount: 1,
                    price: 0
                },
                update:{
                    name: 0,
                    amount: 1,
                    price_in: 0,
                    checkout:0,
                    agency: 0
                },
                carts: {
                    id:0,
                    rowId: 0,
                    name: 0,
                    qty: 0,
                    price: 0,
                    price_in: 0,
                    max: 0,
                    transport: 0,
                    checkout: 0,
                    revenue: 0,
                    revenue_update: 0,
                    total: {{str_replace(',','',Cart::instance('export_'.$order->id)->subtotal(0))}},
                }
            }
        },
        methods:{
            //carts
            sumRevenue:function(id,amount, price){
                if(amount > 0 && price > 0){
                    fetch('{{route('admin.ajax.get.revenue',[':id',':amount',':price'])}}'.replace(':id',id).replace(':amount',amount).replace(':price',price)).then(function(response){
                        return response.json().then(function(data){
                            app.action.carts.revenue = data;
                            //console.log(data);
                        })
                    })
                }
            },
            sumRevenueItem:function(id,amount, price){
                if(amount > 0 && price > 0){
                    fetch('{{route('admin.ajax.get.revenue',[':id',':amount',':price'])}}'.replace(':id',id).replace(':amount',amount).replace(':price',price)).then(function(response){
                        return response.json().then(function(data){
                            app.action.carts.revenue_update = data;
                            //console.log(data);
                        })
                    })
                }
            },
            getItemCart: function(rowId){
                fetch('{{route('admin.orders.get.cart',[':order',':rowId'])}}'.replace(':order',this.order_id).replace(':rowId',rowId)).then(function (reponse){
                    return reponse.json().then(function(data){
                        app.action.carts.id = data.item.id;
                        app.action.carts.name = data.item.name;
                        app.action.carts.qty = data.item.qty;
                        app.action.carts.price = data.item.price;
                        app.action.carts.rowId = data.item.rowId;
                        app.action.carts.price_in = data.item.options.price_in;
                        app.action.carts.revenue_update = data.item.options.revenue;
                        app.action.carts.max = data.product.amount
                    });
                })
            },
            updateItemCart:function (rowId,amount,price,revenue){
                if(confirm('Xác nhận thông tin?')) {
                    fetch('{{route('admin.orders.update.cart',[':order',':rowId',':amount',':price',':revenue'])}}'.replace(':order', this.order_id).replace(':rowId', rowId).replace(':amount', amount).replace(':price', price).replace(':revenue', revenue)).then(function (response) {
                        return response.json().then(function (data) {
                            if(data == 'error'){
                                flash('error', 'Số lượng trong kho không đủ. Vui lòng cập nhật thêm!');
                            }else{
                                app.carts = data.cart;
                                app.action.carts.total = data.total.replaceAll(',','');
                                $('#item-cart').modal('hide');
                                flash('success', 'Cập nhật thành công!');
                            }
                        })
                    })
                }
            },
            destroyItemCart:function(rowId){
                if(confirm('Xóa sản phẩm')) {
                    fetch('{{route('admin.orders.destroy.cart',[':order',':rowId',])}}'.replace(":order", this.order_id).replace(":rowId", rowId)).then(function (reponse) {
                        return reponse.json().then(function (data) {
                            app.carts = data.cart;
                            app.action.carts.total = data.total.replaceAll(',','');
                            flash('success', 'Xóa sản phẩm thành công!');
                        });
                    })
                }
            },
            getProduct:function (id){
                fetch('{{route('admin.ajax.get.product.export',':id')}}'.replace(':id',id)).then(function (response){
                    return response.json().then(function(data){
                        $('#customer_update').select2('destroy');
                        setTimeout(function(){
                            $('#customer_update').select2();
                        },0);
                        $('#item-cart').modal('hide');
                        app.action.update.name = data.product.name;
                        if(data.session){
                            app.action.update.agency = data.session.agency_id ?? 0;
                            app.action.update.price_in = data.session.price_in ?? 0;
                        }
                    })
                })
            },
            updateProduct:function(){
                if(confirm('Xác nhận thông tin?')){
                    fetch("{{route('admin.ajax.update.product',[':id',':amount',':price',':checkout',':agency',':customer'])}}"
                        .replace(":id",this.action.products.id)
                        .replace(":amount",this.action.update.amount)
                        .replace(":price",this.action.update.price_in)
                        .replace(":checkout",this.action.update.checkout)
                        .replace(":agency",this.action.update.agency)
                        .replace(":customer",this.action.sessions.customer))
                        .then(function (response){
                            return response.json().then(function(data){
                                $('#products').select2('destroy');
                                app.products = data.lists;
                                app.action.products.max = data.product.amount;
                                app.action.products.price_in = data.price_in;
                                app.action.products.price_buy = data.price;
                                setTimeout(function(){
                                    $('#products').select2();
                                },0)
                                $('#update-product').modal('hide');
                                flash('success','Cập nhật số lượng thành công!');
                            })
                        })
                }
            },
            addCart: function(){
                fetch('{{route('admin.orders.add.cart',[':order',':id',':amount',':price',':revenue'])}}'.replace(":order",this.order_id).replace(":id",this.action.products.id).replace(":amount",this.action.products.amount).replace(":price",this.action.products.price).replace(":revenue",this.action.carts.revenue)).then(function(reponse){
                    return reponse.json().then(function(data){
                        app.carts = data.cart;
                        app.action.carts.total = data.total.replaceAll(',','');
                        flash('success','Thêm sản phẩm thành công!');
                    });
                })
            },

            choiseProduct:function(){
                console.log(this.action.products.id);
                fetch("{{route('admin.ajax.choise.export.product',[':id',':user'])}}".replace(":id",this.action.products.id).replace(":user",this.action.sessions.customer))
                    .then(function (response){
                        return response.json().then(function(data){
                            app.action.products.id = data.product.id;
                            console.log(data);
                            app.action.products.name = data.product.name;
                            app.action.products.price_in = data.price_in;
                            app.action.products.price_buy = data.price;
                            app.action.products.max = data.product.amount;
                            app.action.update.price_in = data.price_in;
                            app.action.update.amount = 1;
                            //app.price_out = 0;
                            $('#customer_update').select2();
                        });
                    })
            },
            //sessions
            getRevenueSession:function(id, quantity, price){
              if(quantity > 0 && price > 0){
                fetch('{{route('admin.ajax.get.revenue.old',[':id',':quantity',':price'])}}'.replace(':id',id).replace(':quantity',quantity).replace(':price',price)).then(function(response){
                    return response.json().then(function(data){
                        app.action.sessions.revenue_item = data;
                    })
                })
              }
            },
            getItemSession:function(id){
                fetch('{{route('admin.orders.get.session',':id')}}'.replace(':id',id)).then(function(response){
                    return response.json().then(function(data){
                        if(data == 'error'){
                            flash(data,'Đã có lỗi xảy ra. Vui lòng thử lại!');
                        }else{
                            app.action.sessions.name = data.product.name;
                            app.action.sessions.max = Number(data.product.amount) + Number(data.amount) ;
                            app.action.sessions.id = data.id;
                            app.action.sessions.amount = data.amount;
                            app.action.sessions.price = data.price;
                            app.action.sessions.price_in = data.price_in;
                            app.action.sessions.revenue_item = data.revenue;
                        }
                    })
                })
            },
            updateItemSession:function(id,amount,price,revenue){
                if(confirm('Xác nhận thông tin?')){
                    fetch('{{route('admin.orders.update.session',[':id',':amount',':price',':revenue'])}}'.replace(':id',id).replace(':amount',amount).replace(':price',price).replace(':revenue',revenue)).then(function(response){
                        return response.json().then(function (data){
                            if(data == 'error'){
                                flash(data,'Đã có lỗi xảy ra. Vui lòng thử lại!');
                            }else{
                                app.sessions = data.session;
                                app.action.sessions.total = data.order.total;
                                app.action.sessions.checkout = data.order.checkout;
                                app.action.sessions.revenue = data.order.revenue;
                                $('.item-session').modal('hide');
                                flash('success','Cập nhật thành công!');
                            }
                        })
                    })
                }
            },
            destroyItemSession:function(id){
                if(confirm('Xóa sản phẩm?')){
                    fetch('{{route('admin.orders.destroy.session',':id')}}'.replace(':id',id)).then(function(response){
                        return response.json().then(function (data){
                            if(data == 'error'){
                                flash(data,'Đã có lỗi xảy ra. Vui lòng thử lại!');
                            }else{
                                app.sessions = data.session;
                                app.action.sessions.total = data.order.total;
                                app.action.sessions.checkout = data.order.checkout;
                                app.action.sessions.revenue = data.order.revenue;
                                flash('success','Xóa sản phẩm thành công!');
                            }
                        })
                    })
                }
            },
            printCart:function(customer){
                fetch('{{route('admin.ajax.get.data.print',':customer')}}'.replace(':customer',customer)).then(function(response){
                    return response.json().then(function(data){
                        app.action.print.time = data.time;
                        app.action.print.name = data.customer.name;
                    })
                })
            },
        },
        watch:{

        },
        computed:{
            total_all:function(){
                return Number(this.action.sessions.total) + Number(this.action.carts.total) + Number(this.action.sessions.transport) - Number(this.action.sessions.discount);
            },
            total_session:function(){
                return Number(this.action.sessions.total) + Number(this.action.sessions.transport) - Number(this.action.sessions.discount);
            },
            total_bonus:function(){
                return Number(this.action.sessions.total) + Number(this.action.carts.total);
            },
            debt_session:function(){
                return Number(this.total_all - Number(this.action.sessions.checkout));
            },
            provisional_update:function(){
                return this.action.update.amount * this.action.update.price_in;
            },
            detb_update:function(){
                return Number(this.provisional_update) - Number(this.action.update.checkout);
            },
            debt_carts:function(){
                return Number(this.action.carts.total) + Number(this.action.carts.transport) - Number(this.action.carts.checkout);
            },
            revenue_carts:function(){
                let revenue = 0;
                $.each(this.carts,function(key,value){
                    revenue += Number(value.options.revenue);
                })
                return revenue - this.action.sessions.discount;
            },
            revenue_session:function(){
                return this.action.sessions.revenue - (this.action.sessions.discount - this.action.sessions.discount_default);
            }
        }
    })

    function js_select2(){
        return $("#admins ,#customers, #products,#customer_update").select2()
            .on("select2:select", e => {
                const event = new Event("change", { bubbles: true, cancelable: true });
                e.params.data.element.parentElement.dispatchEvent(event);
            })
            .on("select2:unselect", e => {
                const event = new Event("change", { bubbles: true, cancelable: true });
                e.params.data.element.parentElement.dispatchEvent(event);
            });
    }
    function open_bonus(){
        let bonus = $('.bonus-cart');
        if($(bonus).css('display') == 'block'){
            $(bonus).fadeOut();
        }else{
            $(bonus).fadeIn();
        }
    }
    $(document).ready(function(){
        js_select2();
        $('.bonus-cart').hide();
    })
</script>
@stop

@section('javascript')
    <!-- Required datatable js -->
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

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

    <script src="{{asset('admin/assets/libs/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{asset('admin/assets/libs/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/datatables/buttons.bootstrap4.min.js')}}"></script>

    <script src="{{asset('admin/assets/libs/datatables/buttons.print.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/datatables/buttons.colVis.js')}}"></script>

    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>

    <!-- Responsive examples -->
    <script src="{{asset('admin/assets/libs/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/datatables/responsive.bootstrap4.min.js')}}"></script>

    <script src="{{asset('admin/assets/libs/tooltipster/tooltipster.bundle.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/pages/tooltipster.init.js')}}"></script>

    <!-- Datatables init -->
    <script src="{{asset('admin/assets/js/pages/datatables.init.js')}}"></script>

@stop

@section('css')
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- third party css -->
    <link href="{{asset('admin/assets/libs/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/datatables/buttons.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/datatables/responsive.bootstrap4.css')}}" rel="stylesheet" type="text/css" />

    <link href="/admin/assets/libs/tooltipster/tooltipster.bundle.min.css" rel="stylesheet" type="text/css" >

@stop
