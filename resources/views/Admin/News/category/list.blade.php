@extends('Admin.Layout.layout')
@section('title') Danh mục bài viết @stop
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item active">Danh mục bài viết</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Danh mục bài viết</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">
                    <form method="get">
                        <div class="row">
                            <div class="col-md-2 mb-2 mb-lg-0 mb-md-0">
                                <label class="font-weight-bold">Hiển thị</label>
                                <select class="form-control" data-toggle="select2" name="public">
                                    <option value="">-----</option>
                                    <option value="true" {{request()->public == 'true' ? "selected" : ""}}>Kích hoạt</option>
                                    <option value="false" {{request()->public == 'false' && isset(request()->public) ? "selected" : ""}}> Không kích hoạt</option>
                                </select>
                            </div>

                            <div class="col-md-2 mb-2 mb-lg-0 mb-md-0">
                                <label class="font-weight-bold">Nổi bật</label>
                                <select class="form-control" data-toggle="select2" name="status">
                                    <option value="">-----</option>
                                    <option value="true" {{request()->status == 'true' ? "selected" : ""}}>Kích hoạt</option>
                                    <option value="false" {{request()->status == 'false' && isset(request()->status) ? "selected" : ""}}> Không kích hoạt</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2 mb-lg-0 mb-md-0">
                                <label class="font-weight-bold">Ngôn ngữ</label>
                                <select class="form-control" data-toggle="select2" name="lang">
                                    <option value="">-----</option>
                                    @foreach($lang as $item)
                                    <option value="{{$item->value}}" {{request()->lang == $item->value ? "selected" : ""}}> {{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2 mb-lg-0 mb-md-0">
                                <label class="font-weight-bold">Thành viên</label>
                                <select class="form-control" data-toggle="select2" name="user">
                                    <option value="">-----</option>
                                    @foreach($user as $item)
                                        <option value="{{$item->id}}" {{request()->user == $item->id ? "selected" : ""}}> {{$item->account}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="ql-color-white hidden-xs" style="opacity: 0">-</label>
                                <div class="mb-2 mb-lg-0 mb-md-0">
                                    <button class="btn btn-primary waves-effect waves-light" type="submit"><span class="icon-button"><i class="fe-search"></i></span> Tìm kiếm</button>
                                    <a class="btn btn-purple waves-effect waves-light" href="{{route('admin.news_category.index')}}"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
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
                    <form method="post" action="{{route('admin.news.category.delMulti')}}" enctype="multipart/form-data">
                        @csrf
                    <div class="action-datatable mb-3">
                        <a href="{{route('admin.news_category.create')}}" class="btn btn-primary waves-effect width-md waves-light">
                            <span class="icon-button"><i class="fe-plus"></i></span> Thêm mới</a>

                        <button class="btn btn-warning waves-effect waves-light" onclick="return confirm('Bạn chắc chắn muốn xóa!')" type="submit" name="delall" value="delete"><span class="icon-button"><i class="fe-x-circle" aria-hidden="true"></i></span> Xóa tất cả</button>
                    </div>
                    <table id="datatable-buttons" class="table table-bordered table-striped table-hover bs-table" style="border-collapse: collapse; border-spacing: 0; ;">
                        <thead>
                        <tr>
                            <th>
                                <div class="checkbox checkbox-primary checkbox-circle">
                                    <input id="check-all" class="check_del" {{$category->count() == 0 ? "disabled" : "" }} type="checkbox" name="checkAll">
                                    <label for="check-all"></label>
                                </div>
                            </th>
                            <th>ID</th>
                            <th>STT</th>
                            <th>Tiêu đề</th>
                            <th>Danh mục cha</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>

                        <tbody>
                            {{list_news_category($category)}}
                        </tbody>
                    </table>
                    </form>
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
                type = '{{\App\Enums\SystemsModuleType::NEWS_CATEGORY}}';
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

            $('.data_status').click(function(){
                url = "{{route('admin.ajax.data.status')}}";
                id = $(this).attr('data-id');
                _token = $('input[name=_token]').val();
                type = '{{\App\Enums\SystemsModuleType::NEWS_CATEGORY}}';
                $.ajax({
                    url:url,
                    type:'GET',
                    cache:false,
                    data:{'_token':_token,'id':id,'type':type},
                    success:function(data){
                    }
                });
            });

            $('.data_public').click(function(){
                url = "{{route('admin.ajax.data.public')}}";
                id = $(this).attr('data-id');
                _token = $('input[name=_token]').val();
                type = '{{\App\Enums\SystemsModuleType::NEWS_CATEGORY}}';
                $.ajax({
                    url:url,
                    type:'GET',
                    cache:false,
                    data:{'_token':_token,'id':id,'type':type},
                    success:function(data){
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
