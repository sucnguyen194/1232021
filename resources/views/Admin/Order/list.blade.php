@extends('Admin.Layout.layout')
@section('title') Đơn hàng @stop
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item active">Đơn hàng</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Đơn hàng</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <form method="get">
                        <div class="row">

                            {{--                            <div class="col-md-2 mb-2 mb-lg-0 mb-md-0">--}}
                            {{--                                <label class="font-weight-bold">Trạng thái</label>--}}
                            {{--                                <select class="form-control" data-toggle="select2" name="status">--}}
                            {{--                                    <option value="">-----</option>--}}
                            {{--                                    <option value="true" {{request()->status == 'true' ? "selected" : ""}}>Hoàn thành</option>--}}
                            {{--                                    <option value="false" {{request()->status == 'false' && isset(request()->status) ? "selected" : ""}}> Chưa duyệt</option>--}}
                            {{--                                </select>--}}
                            {{--                            </div>--}}

                            <div class="col-md-3 mb-2 mb-lg-0 mb-md-0">
                                <label class="font-weight-bold">Ngày tạo đơn</label>
                                <input type="text" id="reportrange" name="date" value="{{request()->date}}" placeholder="Từ ngày - đến ngày" class="form-control"/>
                            </div>

                            <div class="col-md-3 mb-2 mb-lg-0 mb-md-0">
                                <label class="font-weight-bold">Người tạo</label>
                                <select class="form-control" data-toggle="select2" name="user_create">
                                    <option value="">-----</option>
                                    @foreach($users->where('lever',\App\Enums\LeverUser::ADMIN) as $item)
                                        <option value="{{$item->id}}" {{request()->user_create == $item->id ? "selected" : ""}}> {{$item->name ?? $item->account}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-2 mb-lg-0 mb-md-0">
                                <label class="font-weight-bold">Khách hàng</label>
                                <select class="form-control" data-toggle="select2" name="customer">
                                    <option value="">-----</option>
                                    @foreach($users->where('lever',\App\Enums\LeverUser::USER) as $item)
                                        <option value="{{$item->id}}" {{request()->customer == $item->id ? "selected" : ""}}> {{$item->name ?? $item->account}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="ql-color-white hidden-xs" style="opacity: 0">-</label>
                                <div class="mb-2 mb-lg-0 mb-md-0">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit"><span class="icon-button"><i class="fe-search"></i></span> Tìm kiếm</button>
                                    <a class="btn btn-purple waves-effect waves-light" href="{{route('admin.orders.index')}}"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="font-weight-bold">Doanh thu</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span id="basic-addon1" class="input-group-text">VNĐ</span>
                                </div>
                                <div class="font-weight-bold form-control">{{number_format($orders->sum('total'))}}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="font-weight-bold">Vốn</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span id="basic-addon1" class="input-group-text">VNĐ</span>
                                </div>
                                <div class="font-weight-bold form-control">{{number_format($orders->sum('total') - $orders->sum('revenue'))}}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="font-weight-bold">Lợi nhuận</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span id="basic-addon1" class="input-group-text">VNĐ</span>
                                </div>
                                <div class="font-weight-bold form-control">{{number_format($orders->sum('revenue'))}}</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="font-weight-bold">Công nợ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span id="basic-addon1" class="input-group-text">VNĐ</span>
                                </div>
                                <div class="font-weight-bold form-control">{{number_format($orders->sum('debt'))}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <div class="action-datatable mb-3">
                            <a href="{{route('admin.orders.create')}}" class="btn btn-primary waves-effect width-md waves-light">
                                <span class="icon-button"><i class="fe-plus"></i></span> Thêm mới</a>
{{--                            <button class="btn btn-warning waves-effect waves-light" onclick="return confirm('Bạn chắc chắn muốn xóa!')" type="submit" name="delall" value="delete"><span class="icon-button"><i class="fe-x-circle" aria-hidden="true"></i></span> Xóa tất cả</button>--}}
                        </div>
                    <table id="datatable-buttons" class="table table-bordered table-striped table-hover bs-table" style="border-collapse: collapse; border-spacing: 0; ;">
                            <thead>
                            <tr>

                                <th>ID</th>
                                <th>Ngày tạo</th>
                                <th style="width: 30%">Người tạo</th>
                                <th style="width: 30%">Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Thanh toán</th>
                                <th>Công nợ</th>
                                <th>Tổng lãi</th>
{{--                                <th>Trạng thái</th>--}}
                                <th>Hành động</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($orders as $item)
                                <tr>
                                    <td >{{$item->id}}</td>
                                    <td>
                                        {{$item->created_at->format('d/m/Y H:i')}}
                                    </td>
                                    <td  style="width: 10%"><a href="{{route('admin.user.index',['id'=> $item->user->id ?? 0])}}" class="text-primary font-weight-bold" target="_blank">{{ $item->user->name ?? 'Tên trống hoặc đã xóa'}}</a></td>
                                    <td style="width: 10%"><a href="{{route('admin.user.index',['id'=> $item->customer->id ?? 0])}}" class="text-primary font-weight-bold" target="_blank">{{ $item->customer->name ?? 'Tên trống hoặc đã xóa'}}</a></td>
                                    <td class="font-weight-bold">
                                        {{number_format($item->total)}} @if($item->transport > 0) <br> <small>Phí vận chuyển: {{number_format($item->transport)}}</small>@endif
                                    </td>
                                    <td class="font-weight-bold">
                                        {{number_format($item->checkout)}}
                                    </td>
                                    <td class="font-weight-bold">
                                        {{number_format($item->debt)}}
                                    </td>
                                    <td class="font-weight-bold">
                                        {{number_format($item->revenue)}}
                                    </td>
{{--                                    <td>--}}
{{--                                       @if($item->status == 1)  <span class="btn btn-xs btn-success">Hoàn thành</span> @else <span class="btn btn-xs btn-danger">Chưa duyệt</span> @endif--}}
{{--                                    </td>--}}

                                    <td>
                                        <a href="{{route('admin.orders.edit',$item)}}" class="btn btn-purple waves-effect waves-light">
                                            <span class="icon-button"><i class="pe-7s-magic-wand"></i> </span>Chi tiết</a>

                                        @if(!$item->sessions->count())
                                        <form method="post" action="{{route('admin.orders.destroy',$item)}}" class="d-inline-block">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa?');" class="btn btn-warning waves-effect waves-light"><span class="icon-button"><i class="fe-x"></i></span></button>
                                        </form>
                                        @endif
                                    </td>
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
