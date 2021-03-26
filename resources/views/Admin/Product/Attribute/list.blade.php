@extends('Admin.Layout.layout')
@section('title') Danh sách bộ lọc @stop
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item active">Danh sách bộ lọc</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Danh sách bộ lọc</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <form method="get">
                        <div class="row">
                            <div class="col-md-4 mb-2 mb-lg-0 mb-md-0">
                                <label>Danh mục</label>
                                <select class="form-control" data-toggle="select2" name="category">
                                    <option value="">-----</option>
                                    <option value="-1" {{request()->category == -1 ? "selected" : ""}} class="font-weight-bold">Chưa có danh mục</option>
                                    @foreach($category as $item )
                                        <option value="{{$item->id}}" {{request()->category == $item->id ? "selected" : ""}} class="font-weight-bold">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="ql-color-white hidden-xs" style="opacity: 0">-</label>
                                <div class="mb-2 mb-lg-0 mb-md-0">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit"><span class="icon-button"><i class="fe-search"></i></span> Tìm kiếm</button>
                                    <a class="btn btn-default waves-effect waves-light" href="{{route('admin.attributes.index')}}"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
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
                            <a href="{{route('admin.attributes.create')}}" class="btn btn-primary waves-effect width-md waves-light">
                                <span class="icon-button"><i class="fe-plus"></i></span> Thêm mới</a>
                        </div>
                    <table id="datatable-buttons" class="table table-bordered table-hover bs-table" style="border-collapse: collapse; border-spacing: 0; ;">
                            <thead>
                            <tr>

                                <th class="sorting_desc">ID</th>
                                <th>STT</th>
                                <th>Tiêu đề</th>
                                <th>Danh mục</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($attribute as $item)

                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td class="position-relative"><input style="width: 120px" type="number" class="form-control" name="sort" data-id="{{$item->id}}" value="{{$item->sort}}"> <span id="change-sort-success_{{$item->id}}" class="change-sort"></span></td>
                                    <td style="width: 30%">{{$item->name}}</a> </td>
                                    <td>{{$item->category->name ?? "Chưa có danh mục"}}</a> </td>
                                    <td>
                                        {{$item->updated_at->diffForHumans()}}
                                    </td>

                                    <td>
                                        <a href="{{route('admin.attributes.edit',$item)}}" class="btn btn-default waves-effect waves-light">
                                            <span class="icon-button"><i class="fe-edit-2"></i></span></a>
                                        <form method="post" action="{{route('admin.attributes.destroy',$item)}}" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa?');" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-x"></i></span></button>
                                        </form>
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

    <script type="text/javascript">
        $(document).ready(function(){
            $('input[name=sort]').keyup(function(){
                url = "{{route('admin.ajax.data.sort')}}";
                id = $(this).attr('data-id');
                num = $(this).val();
                type = '{{\App\Enums\SystemsModuleType::ATTRIBUTE}}';
                _token = $('input[name=_token]').val();
                $.ajax({
                    url:url,
                    type:'GET',
                    cache:false,
                    data:{'_token':_token,'id':id,'num':num,'type':type},
                    success:function(data){
                        $('#change-sort-success_'+id+'').html('<i class="fa fa-check text-success" aria-hidden="true"></i>');
                        $('#change-sort-success_'+id+'').fadeIn(1000);
                        $('#change-sort-success_'+id+'').fadeOut(5000);
                    }
                });
            });
        })
    </script>

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
