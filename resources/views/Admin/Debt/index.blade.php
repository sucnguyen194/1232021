@extends('Admin.Layout.layout')
@section('title') Vay vốn @stop
@section('content')
    <div class="container-fluid" id="debts-index">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item active">Vay vốn</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Công nợ: <strong class="font-20">{{number_format($users->sum('debt'))}}</strong></h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <div class="action-datatable mb-3">

                        <a href="{{route('admin.debts.create')}}" class="btn btn-primary waves-effect width-md waves-light float-right">
                            <span class="icon-button"><i class="fe-plus"></i></span> Thêm mới</a>
                    </div>

                    <table id="datatable-buttons" class="table  table-bordered table-hover bs-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th colspan="1">ID</th>
                            <th>Họ & tên</th>
                            <th>Công nợ</th>
                            <th>SĐT</th>
{{--                            <th>Địa chỉ</th>--}}
{{--                            <th>Trạng thái</th>--}}
                            <th>Hành động</th>
                        </tr>
                        </thead>

                        <tbody>

                        <tr v-for="user in users">
                                <td >@{{ user.id }}</td>
                                <td>@{{ user.name }}</td>
                                <td>@{{number_format(user.debt)}}</td>
                                <td>@{{user.phone}}</td>
                                <td>
                                    <button class="btn btn-purple waves-effect waves-light" data-toggle="modal" data-target="#updatedebt" v-on:click="choiseType(user.id, borrow, user.name, user.debt)">Vay</button>
                                    <button class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#updatedebt" v-on:click="choiseType(user.id, pay ,user.name , user.debt)">Trả</button>
                                    <a v-bind:href="'{{route('admin.debts.edit',':id')}}'.replace(':id', user.id)" class="btn btn-primary waves-effect waves-light">
                                        <span class="icon-button"><i class="fe-edit-2"></i></span></a>
                                    <form method="post" v-bind:action="'{{route('admin.debts.destroy',':id')}}'.replace(':id',user.id)" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" name="delete" onclick="return confirm('Bạn có chắc muốn xóa?');" value="delete" class="btn btn-warning waves-effect waves-light">
                                            <span class="icon-button"><i class="fe-x"></i></span></button>
                                    </form>
                                    <a v-bind:href="'{{route('admin.debts.show',':id')}}'.replace(':id',user.id)" class="btn btn-info waves-effect waves-light">Transaction</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- end row -->
        <div id="updatedebt" class="modal fade updatedebt" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <label v-if="type == borrow">Vay vốn: <span class="font-weight-bold">@{{ name }} (@{{ number_format(debt) }})</span></label>
                            <label v-else>Trả tiền : <span class="font-weight-bold">@{{ name }} (@{{ number_format(debt) }})</span></label>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">VNĐ</span>
                                </div>
                                <input class="form-control font-weight-bold text-primary" v-model="current">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"> Đóng</button>
                        <button type="button" :disabled="!id || !current || !type" class="btn btn-primary waves-effect waves-light" v-on:click="updateDebt(id, type, current)"> <span class="icon-button"><i class="fe-plus"></i></span> Xác nhận</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <script>
        var app = new Vue({
            el: '#debts-index',
            data: {
                users: @json($users),
                borrow: 'borrow',
                pay: 'pay',
                id: 0,
                current: 0,
                type: null,
                name: null,
                debt: 0,
            },
            methods:{
                updateDebt:function (id, type, current){
                    if(confirm('Xác nhận hành động?')){
                        let route = '{{route('admin.update.debts',[':id',':type',':current'])}}'.replace(':id',id).replace(':type',type).replace(':current',current);
                      fetch(route).then(function(res){
                            return res.json().then(function(data){
                                $('.updatedebt').modal('hide');
                                app.users = data;
                                flash({'message': 'success' ,'type': 'Cập nhật thành công'});
                            })
                        })
                    }
                },
                choiseType:function(user, type, name, debt){
                    app.type = type;
                    app.id = user;
                    app.name = name;
                    app.debt = debt;
                }
            }
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
@stop
