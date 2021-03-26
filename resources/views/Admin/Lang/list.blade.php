@extends('Admin.Layout.layout')
@section('title') Quản lý ngôn ngữ @stop
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item active">Quản lý ngôn ngữ</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Danh sách ngôn ngữ</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                        <div class="action-datatable text-right mb-3">
                            <a href="{{route('admin.lang.create')}}" class="btn btn-primary waves-effect width-md waves-light">
                                <span class="icon-button"><i class="fe-plus"></i></span> Thêm mới</a>
                        </div>
                        <table id="datatable-buttons" class="table table-bordered table-hover bs-table" style="border-collapse: collapse; border-spacing: 0; ;">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th style="width: 20%">Ngôn ngữ</th>
                                <th style="width: 20%">Giá trị</th>
                                <th>Thời gian tạo</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($lang as $item)
                                <tr>
                                    <td >{{$item->id}}</td>
                                    <td style="width: 30%">{{$item->name}} </td>
                                    <td style="width: 30%">{{$item->value}} </td>
                                    <td>
                                        {{$item->created_at->diffForHumans()}}
                                    </td>
                                    <td>
                                        @if($item->status == 1) <strong class="text-info"><span class="icon-button"><i class="pe-7s-star"></i></span> Mặc định</strong> @endif
                                    </td>
                                    <td>
                                        <a href="{{route('admin.lang.edit',$item)}}" class="btn btn-default waves-effect waves-light">
                                            <span class="icon-button"><i class="pe-7s-look"></i></span></a>
                                    <form method="post" action="{{route('admin.lang.destroy',$item)}}" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-default waves-effect waves-light" {{$lang->count() == 1 ? "disabled" : ""}} onclick="return confirm('Bạn có chắc muốn xóa?');" ><span class="icon-button"><i class="fe-x"></i></span></button>
                                    </form>
                                        <a href="{{route('admin.active.lang',$item->id)}}" title="Đặt {{$item->name}} làm ngôn ngữ mặc định" onclick="return confirm('Bạn chắc chắn đặt {{$item->name}} làm ngôn ngữ mặc định?');" class="btn btn-info waves-effect waves-light">
                                            <span class="icon-button"><i class="fe-star-on"></i></span></a>
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
