@extends('Admin.Layout.layout')
@section('title') Gallery @stop
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item active">Gallery</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Gallery</h4>
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
                                    <a class="btn btn-purple waves-effect waves-light" href="{{route('admin.gallerys.index')}}"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
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
                    <form method="post" action="{{route('admin.pages.delMulti')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="action-datatable mb-3">
                            <a href="{{route('admin.gallerys.create')}}" class="btn btn-primary waves-effect width-md waves-light">
                                <span class="icon-button"><i class="fe-plus"></i></span> Thêm mới</a>

                            <button class="btn btn-warning waves-effect waves-light" onclick="return confirm('Bạn chắc chắn muốn xóa!')" type="submit" name="delall" value="delete"><span class="icon-button"><i class="fe-x-circle" aria-hidden="true"></i></span> Xóa tất cả</button>
                        </div>
                        <table id="datatable-buttons" class="table table-bordered table-striped table-hover bs-table" style="border-collapse: collapse; border-spacing: 0; ;">
                            <thead>
                            <tr>
                                <th>
                                    <div class="checkbox checkbox-primary checkbox-circle">
                                        <input id="check-all" class="check_del" {{$gallery->count() == 0 ? "disabled" : "" }} type="checkbox" name="checkAll">
                                        <label for="check-all"></label>
                                    </div>
                                </th>
                                <th>ID</th>
                                <th>STT</th>
                                <th>Ảnh</th>
                                <th style="width: 30%">Tiêu đề</th>
                                <th>Ngôn ngữ</th>
                                <th>Ngày tạo</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($gallery as $item)

                                <tr>
                                    <td>
                                        <div class="checkbox checkbox-primary checkbox-circle">
                                            <input id="checkbox_del_{{$item->id}}" class="check_del" type="checkbox" value="{{$item->id}}" name="check_del[]">
                                            <label for="checkbox_del_{{$item->id}}"></label>
                                        </div>
                                    </td>
                                    <td>{{$item->id}}</td>
                                    <td class="position-relative"><input style="width: 120px" type="number" class="form-control" name="sort" data-id="{{$item->id}}" value="{{$item->sort}}"> <span id="change-sort-success_{{$item->id}}" class="change-sort"></span></td>
                                    <td>@if(file_exists($item->image))<img src="{{asset($item->image)}}" width="100"> @endif</td>
                                    <td style="width: 30%"><a href="{{route('alias',$item->alias)}}" title="{{$item->title}}" class="text-secondary" target="_blank">{{ $item->title}}</a> </td>

                                    <td>{{$item->language->name}} </td>
                                    <td>
                                        {{$item->updated_at->diffForHumans()}}
                                    </td>
                                    <td>
                                        <div class="checkbox checkbox-primary checkbox-circle" >
                                            <input id="checkbox_public_{{$item->id}}"  {{$item->public == 1 ? "checked" : ''}} type="checkbox" name="public">
                                            <label for="checkbox_public_{{$item->id}}" class="gallery_public"  data-id="{{$item->id}}">Hiển thị</label>
                                        </div>

                                        <div class="checkbox checkbox-primary checkbox-circle">
                                            <input id="checkbox_status_{{$item->id}}" {{$item->status == 1 ? "checked" : ''}} type="checkbox" name="status">
                                            <label for="checkbox_status_{{$item->id}}" class="mb-0 gallery_status" data-id="{{$item->id}}">Nổi bật</label>
                                        </div>
                                    </td>

                                    <td>
                                        <a href="{{route('admin.gallerys.edit',$item)}}" class="btn btn-purple waves-effect waves-light">
                                            <span class="icon-button"><i class="fe-edit-2"></i></span></a>

                                        <a href="{{route('admin.pages.del',$item->id)}}" onclick="return confirm('Bạn có chắc muốn xóa?');" class="btn btn-warning waves-effect waves-light">
                                            <span class="icon-button"><i class="fe-x"></i></span></a>
                                    </td>
                                </tr>
                            @endforeach
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
                type = '{{\App\Enums\SystemsModuleType::GALLERY}}';
                _token = $('input[name=_token]').val();
                $.ajax({
                    url:url,
                    type:'GET',
                    cache:false,
                    data:{'_token':_token,'id':id,'num':num,'type':type},
                    success:function(data){
                        flash('success','Cập nhật thành công');
                    }
                });
            });

            $('.gallery_status').click(function(){
                url = "{{route('admin.ajax.data.status')}}";
                id = $(this).attr('data-id');
                _token = $('input[name=_token]').val();
                type = '{{\App\Enums\SystemsModuleType::GALLERY}}';
                $.ajax({
                    url:url,
                    type:'GET',
                    cache:false,
                    data:{'_token':_token,'id':id,'type':type},
                    success:function(data){
                        flash('success','Cập nhật thành công');
                    }
                });
            });

            $('.gallery_status').click(function(){
                url = "{{route('admin.ajax.data.public')}}";
                id = $(this).attr('data-id');
                _token = $('input[name=_token]').val();
                type = '{{\App\Enums\SystemsModuleType::GALLERY}}';
                $.ajax({
                    url:url,
                    type:'GET',
                    cache:false,
                    data:{'_token':_token,'id':id,'type':type},
                    success:function(data){
                        flash('success','Cập nhật thành công');
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
