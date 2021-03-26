@extends('Admin.Layout.layout')
@section('title') Module | {{$module->name}} @stop
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item "><a href="{{route('admin.modules.index')}}">Danh sách modules</a></li>
                        </ol>
                    </div>
                    <h4 class="page-title">Module | {{$module->name}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <table id="datatable-buttons" class="table table-hover bs-table" style="border-collapse: collapse; border-spacing: 0; ;">
                        <thead>
                        <tr>
                            <th>Tên cột</th>
                            <th style="width: 30%">Tên hiển thị</th>
                            <th style="width: 30%">Kiểu hiển thị</th>
                            <th>Kiểu dữ liệu</th>
                            <th>Độ dài</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach(json_decode($module->fields) as $field)
                            <tr>
                                <td >{{$field->name}}</td>
                                <td>{{$field->display_name}}</td>
                                <td>
                                    @switch($field->display_type)
                                        @case(0)
                                        Text
                                        @break
                                        @case(1)
                                        Checkbox
                                        @break
                                        @case(2)
                                        Number
                                        @break
                                        @case(3)
                                        Radio
                                        @break
                                        @case(4)
                                        Select
                                        @break
                                        @case(5)
                                        File
                                        @break

                                        @default
                                        Textarea
                                    @endswitch

                                </td>
                                <td>
                                    @switch($field->type)
                                        @case(0)
                                        Text
                                        @break
                                        @case(1)
                                        Integer
                                        @break
                                        @case(2)
                                        Varchar
                                        @break
                                        @case(3)
                                        Text
                                        @break
                                        @default
                                        Date
                                    @endswitch
                                </td>
                                <td>{{$field->length}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-right">
                        <a href="{{route('admin.modules.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    </div>
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
    // <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>


    <script src="{{asset('admin/assets/libs/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{asset('admin/assets/libs/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/datatables/buttons.bootstrap4.min.js')}}"></script>

{{--    <script src="{{asset('admin/assets/libs/datatables/buttons.print.min.js')}}"></script>--}}
{{--    <script src="{{asset('admin/assets/libs/datatables/buttons.colVis.js')}}"></script>--}}

{{--    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>--}}
{{--    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>--}}
{{--    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>--}}
{{--    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>--}}
{{--    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>--}}


    <!-- Responsive examples -->
    <script src="{{asset('admin/assets/libs/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/datatables/responsive.bootstrap4.min.js')}}"></script>

    <!-- Datatables init -->
{{--    <script src="{{asset('admin/assets/js/pages/datatables.init.js')}}"></script>--}}

@stop
