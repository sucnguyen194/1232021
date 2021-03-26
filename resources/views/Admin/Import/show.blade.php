@extends('Admin.Layout.layout')
@section('title')
    Thông tin đơn hàng #{{$import->id}}
@stop
@section('content')

    <div class="container-fluid" id="update-import">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.imports.index')}}">Danh sách nhập kho</a></li>
                            <li class="breadcrumb-item active">Thông tin đơn hàng #{{$import->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Thông tin đơn hàng #{{$import->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
            <div class="row">
               <div class="col-lg-12">
                        <div class="card-box">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3">Ngày nhập hàng</div>
                                    <div class="col-lg-9 font-weight-bold">{{$import->created_at->format('d/m/Y H:i')}}</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3">Người nhập hàng</div>
                                    <div class="col-lg-9 font-weight-bold"><a href="{{route('admin.user.index',['id' => $import->user->id ?? 0])}}" target="_blank">{{$import->user->name ?? 'Tên trống hoặc đã xóa'}}</a> </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-3">Nhà cung cấp</div>
                                    <div class="col-lg-9 font-weight-bold"><a href="{{route('admin.user.index',['id' => $import->user->id ?? 0])}}" target="_blank">{{$import->agency->name ?? 'Tên trống hoặc đã xóa'}}</a> </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-12">Ghi chú</div>
                                    @if($import->note)
                                    <div class="col-lg-12 mt-2 font-weight-bold">
                                        <textarea class="form-control" readonly rows="7">{!! $import->note !!}</textarea>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#myModal"><span class="icon-button"><i class="fe-edit-2"></i></span> Sửa</button>

                                <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                    <form method="POST" action="{{route('admin.imports.update',$import)}}">
                                        @csrf
                                        @method('PUT')
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Người nhập hàng</label>
                                                    <select class="form-control" name="user" v-model="update_import.admin" id="admins">
                                                        <option value="0">--Chọn người nhập hàng--</option>
                                                        @foreach($users as $user)
                                                            <option value="{{$user->id}}" {{$import->user->id == $user->id ? "selected" : ""}}>{{$user->name ?? $user->account}}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger mt-2" v-if="update_import.admin == 0">Vui lòng chọn người nhập hàng</p>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nhà cung cấp</label>
                                                    <select class="form-control" name="agency" v-model="update_import.agency" id="agencys">
                                                        <option value="0">--Chọn nhà cung cấp--</option>
                                                        @foreach($agencys as $agency)
                                                            <option value="{{$agency->id}}" {{$import->agency->id == $agency->id ? "selected" : ""}}>{{$agency->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-danger mt-2" v-if="update_import.agency == 0">Vui lòng chọn nhà cung cấp</p>
                                                </div>
                                                <div class="note-group">
                                                    <label>Ghi chú</label>
                                                    <textarea class="form-control" rows="4" name="note"> {!! $import->note !!}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"> Đóng</button>
                                                <button type="submit" name="send" value="user" :disabled="update_import.admin == 0 || update_import.agency == 0" class="btn btn-primary waves-effect waves-light" onclick="return confirm('Xác nhận thông tin?')"> <span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                    </form>
                                </div><!-- /.modal -->

                            </div>
                            <div class="form-group">
                                <label>Danh sách sản phẩm</label>
                                <table class="table table-bordered table-striped">
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
                                    @foreach($import->sessions()->get()->load('product') as $item)
                                        <tr>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">SP</span>
                                                    </div>
                                                    <div class="form-control font-weight-bold">{{$item->product->name ?? "Sản phẩm đã xóa"}}</div>
                                                </div>
                                               </td>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">SL</span>
                                                    </div>
                                                    <div class="form-control font-weight-bold">{{$item->amount}}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                    </div>
                                                    <div class="form-control font-weight-bold">{{number_format($item->price_in)}}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                    </div>
                                                    <div class="form-control font-weight-bold">{{number_format($item->price_in*$item->amount)}}</div>
                                                </div>
                                            </td>
                                            <td><a href="javascript:void(0)" class="btn btn-default waves-effect waves-light" v-on:click="getSession({{$item->id}})" data-toggle="modal" data-target="#product-item-{{$item->id}}"><span class="icon-button"><i class="fe-edit-2"></i></span> </a> <a href="{{route('admin.destroy.session',$item->id)}}" class="btn btn-default waves-effect waves-light" onclick="return confirm('Xóa sản phẩm?')"><span class="icon-button"><i class="fe-x"></i></span> </a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                @foreach($import->sessions()->get()->load('product') as $item)
                    <div id="product-item-{{$item->id}}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <form method="post" action="{{route('admin.update.session',$item->id)}}">
                            @csrf
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Tên sản phẩm</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">SP</span>
                                                </div>
                                                <div class="form-control font-weight-bold">{{$item->product->name ?? 'Sản phẩm đã xóa'}}"</div>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Số lượng</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">SL</span>
                                                </div>
                                                <input type="number" name="amount" class="form-control text-primary font-weight-bold" oninput="validity.valid||(value = 1);" v-model="amount" v-bind:value="{{$item->amount}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Giá nhập</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                </div>
                                                <input type="number" name="price" class="form-control text-primary font-weight-bold" oninput="validity.valid||(value = 0);" v-model="price" v-bind:value="{{$item->price_in}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Thành tiền</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                                </div>
                                                <div class="form-control font-weight-bold">@{{ provisional.toLocaleString() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"> Đóng</button>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light" onclick="return confirm('Xác nhận thông tin?')"> <span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </form>
                    </div><!-- /.modal -->
                @endforeach

                <div class="col-lg-12">
                    <form method="post" action="{{route('admin.imports.update',$import)}}">
                        @csrf
                        @method('PUT')
                    <div class="card-box">
                        <div class="row">
                            <div class="col-lg-4">
                                <label>Tổng tiền</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                    </div>
                                    <div class="form-control font-weight-bold">@{{ total.toLocaleString() }}</div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>Thanh toán</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                    </div>
                                    <input type="text" class="form-control text-primary font-weight-bold" v-model="checkout" name="checkout" oninput="validity.valid||(value = 0);" v-bind:VALUE="checkout">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>Công nợ</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                    </div>
                                    <div class="form-control font-weight-bold">@{{ debts.toLocaleString() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <a class="btn btn-default waves-effect waves-light" href="{{route('admin.imports.index')}}"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" name="send" value="checkout" class="btn btn-primary waves-effect waves-light float-right" onclick="return confirm('Xác nhận thông tin?')"> <span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                    </div>
                    </form>
                </div>
            </div>
            <!-- end row -->
    </div>
<script>

    var app = new Vue({
       el:'#update-import',
       data: {
           total: {{$import->total}},
           checkout: {{$import->checkout}},
           debt: {{$import->debt}},

           amount:0,
           price:0,

           update_import: {
               admin: {{$import->user->id}},
               agency: {{$import->agency->id}},
           }
       },
        methods:{
            getSession:function(id){
                fetch('{{route('admin.ajax.session',':id')}}'.replace(':id',id)).then(function (response){
                    return response.json().then(function(data){
                        app.amount = data.amount;
                        app.price = data.price_in;
                    })
                })
            }
        },
        watch:{
            amount:function (val){
                this.amount = val;
            },
            price:function (val){
                this.price = val;
            },
            checkout:function(val){
                this.checkout = val
            }
        },
        computed:{
            provisional:function(){
                return this.amount * this.price;
            },
            debts:function(){
                return this.total - this.checkout;
            }
        }
    });

    function js_select2(){
        return $("#admins ,#agencys").select2()
            .on("select2:select", e => {
                const event = new Event("change", { bubbles: true, cancelable: true });
                e.params.data.element.parentElement.dispatchEvent(event);
            })
            .on("select2:unselect", e => {
                const event = new Event("change", { bubbles: true, cancelable: true });
                e.params.data.element.parentElement.dispatchEvent(event);
            });
    }
    $(document).ready(function(){
        js_select2();
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

    <script src="{{asset('admin/assets/libs/custombox/custombox.min.js')}}"></script>
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
    <link href="{{asset('admin/assets/libs/custombox/custombox.min.css')}}" rel="stylesheet" type="text/css" >
@stop
