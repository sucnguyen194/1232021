@extends('Admin.Layout.layout')
@section('title') {{request()->type == \App\Enums\ProductSessionType::getKey(\App\Enums\ProductSessionType::export) ? "Lịch sử xuất hàng #".$product->id : "Lịch sử nhập hàng #".$product->id}} @stop
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.products.index')}}">Danh sách sản phẩm</a></li>
                            <li class="breadcrumb-item active">{{request()->type == \App\Enums\ProductSessionType::getKey(\App\Enums\ProductSessionType::export) ? "Lịch sử xuất hàng #".$product->id : "Lịch sử nhập hàng #".$product->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{request()->type == \App\Enums\ProductSessionType::getKey(\App\Enums\ProductSessionType::export) ? "Lịch sử xuất hàng : ".$product->name : "Lịch sử nhập hàng: ".$product->name}} ({{$sessions->sum('amount')}})</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <form method="get">
                        <input type="hidden" name="type" value="{{request()->type}}">
                        <div class="row">
                            <div class="col-md-2 mb-2 mb-lg-0 mb-md-0">
                                <label class="font-weight-bold">@if(\request()->type == \App\Enums\ProductSessionType::getKey(\App\Enums\ProductSessionType::export)) Người xuất hàng  @else Người nhập hàng @endif</label>
                                <select class="form-control" data-toggle="select2" name="user_create">
                                    <option value="">-----</option>
                                    @foreach($user->where('lever',\App\Enums\LeverUser::ADMIN) as $item)
                                        <option value="{{$item->id}}" {{request()->user_create == $item->id ? "selected" : ""}}> {{$item->name ?? $item->account}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if(\request()->type == \App\Enums\ProductSessionType::getKey(\App\Enums\ProductSessionType::export))
                                <div class="col-md-2 mb-2 mb-lg-0 mb-md-0">
                                    <label class="font-weight-bold">Khách hàng</label>
                                    <select class="form-control" data-toggle="select2" name="user">
                                        <option value="">-----</option>
                                        @foreach($user->where('lever',\App\Enums\LeverUser::USER) as $item)
                                            <option value="{{$item->id}}" {{request()->user == $item->id ? "selected" : ""}}> {{$item->name ?? $item->account}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="col-md-2 mb-2 mb-lg-0 mb-md-0">
                                    <label class="font-weight-bold">Nhà cung cấp</label>
                                    <select class="form-control" data-toggle="select2" name="agency">
                                        <option value="">-----</option>
                                        @foreach($agencys as $item)
                                            <option value="{{$item->id}}" {{request()->agency == $item->id ? "selected" : ""}}> {{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-md-2 mb-2 mb-lg-0 mb-md-0">
                                <label class="font-weight-bold"> @if(\request()->type == \App\Enums\ProductSessionType::getKey(\App\Enums\ProductSessionType::export)) Ngày xuất hàng @else Ngày nhập hàng @endif</label>
                                <input type="text" id="reportrange" name="date" value="{{request()->date}}" placeholder="Từ ngày - đến ngày" class="form-control"/>
                            </div>
                            <div class="col-md-2">
                                <label class="ql-color-white hidden-xs" style="opacity: 0">-</label>
                                <div class="mb-2 mb-lg-0 mb-md-0">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit"><span class="icon-button"><i class="fe-search"></i></span> Tìm kiếm</button>
                                    <a class="btn btn-purple waves-effect waves-light" href="{{route('admin.products.show',[$product, 'type'=> request()->type])}}"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <div class="action-datatable text-right mb-3">
                        <a href="{{route('admin.products.index')}}" class="btn btn-purple waves-effect waves-light">
                            <span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    </div>

                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; ;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            @if(\request()->type == \App\Enums\ProductSessionType::getKey(\App\Enums\ProductSessionType::export))
                                <th>Ngày xuất kho</th>
                                <th>Số lượng</th>
                                <th>Người xuất</th>
                                <th>Khách hàng</th>
                            @else
                                <th>Ngày nhập kho</th>
                                <th>Số lượng</th>
                                <th>Người nhập</th>
                                <th>Nhà cung cấp</th>
                            @endif
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($sessions as $item)
                            <tr>
                                    <td >{{$item->id}}</td>
                                    <td >{{$item->created_at->format('d/m/Y H:i')}}</td>
                                    <td>{{$item->amount}}</td>
                                @if(request()->type == \App\Enums\ProductSessionType::getKey(\App\Enums\ProductSessionType::export))
                                    <td class="font-weight-bold"><a href="{{route('admin.user.index',['id' => $item->admin->id])}}" target="_blank">{{$item->admin->name ?? $item->admin->account}}</a> </td>
                                    <td class="font-weight-bold"><a href="{{route('admin.user.index',['id' => $item->user->id])}}" target="_blank">{{$item->user->name ?? $item->user->account}}</a> </td>
                                @else
                                    <td class="font-weight-bold"><a href="{{route('admin.user.index',['id' => $item->admin->id])}}" target="_blank">{{$item->admin->name ?? $item->admin->account}}</a> </td>
                                    <td class="font-weight-bold"><a href="{{route('admin.agencys.index',['id' => $item->agency->id])}}" target="_blank">{{$item->agency->name}}</a> </td>

                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- end row -->

    </div>

@stop

@section('css')
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- third party css -->
    <link href="{{asset('admin/assets/libs/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/datatables/buttons.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/datatables/responsive.bootstrap4.css')}}" rel="stylesheet" type="text/css" />

    <link href="/admin/assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/libs/clockpicker/bootstrap-clockpicker.min.css" rel="stylesheet">
    <link href="/admin/assets/libs/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/libs/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
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

    <script src="/admin/assets/libs/moment/moment.min.js"></script>
    <script src="/admin/assets/libs/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
    <script src="/admin/assets/libs/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="/admin/assets/libs/clockpicker/bootstrap-clockpicker.min.js"></script>
    <script src="/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/admin/assets/libs/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- Init js-->
    <script src="{{asset('admin/assets/js/pages/form-pickers.init.js')}}"></script>

@stop
