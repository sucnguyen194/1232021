@extends('Admin.Layout.layout')
@section('title') Danh mục bộ lọc @stop
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item active">Danh mục bộ lọc</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Danh mục bộ lọc</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card-box table-responsive">
                    <div class="action-datatable text-right mb-3">
                        <a href="{{route('admin.attributes.categories.create')}}" class="btn btn-primary waves-effect width-md waves-light">
                            <span class="icon-button"><i class="fe-plus"></i></span> Thêm mới</a>
                    </div>
                    <table id="datatable-buttons" class="table table-bordered dt-responsive" style="border-collapse: collapse; border-spacing: 0; ;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>STT</th>
                            <th>Danh mục</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($category as $item)

                            <tr>
                                <td >{{$item->id}}</td>
                                <td class="position-relative"><input style="width: 120px" type="number" class="form-control" name="sort" data-id="{{$item->id}}" value="{{$item->sort}}"> <span id="change-sort-success_{{$item->id}}" class="change-sort"></span></td>
                                <td style="width: 30%">{{ $item->name}} </td>
                                <td>
                                    {{$item->updated_at->diffForHumans()}}
                                </td>
                                <td>
                                    <div class="checkbox">
                                        <input id="checkbox_public_{{$item->id}}"  {{$item->public == 1 ? "checked" : ''}} type="checkbox" name="public">
                                        <label for="checkbox_public_{{$item->id}}" class="data_public"  data-id="{{$item->id}}">Hiển thị</label>
                                    </div>
                                </td>

                                <td>
                                    <a href="{{route('admin.attributes.categories.edit',$item)}}" class="btn btn-primary waves-effect waves-light">
                                        <span class="icon-button"><i class="fe-edit-2"></i></span></a>
                                    <form method="post" action="{{route('admin.attributes.categories.destroy',$item)}}" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Bạn có chắc muốn xóa?');" class="btn btn-warning waves-effect waves-light"><span class="icon-button"><i class="fe-x"></i></span></button>
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
                type = '{{\App\Enums\SystemsModuleType::ATTRIBUTE_CATEGORY}}';
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
            $('.data_public').click(function(){
                url = "{{route('admin.ajax.data.public')}}";
                id = $(this).attr('data-id');
                _token = $('input[name=_token]').val();
                type = '{{\App\Enums\SystemsModuleType::ATTRIBUTE_CATEGORY}}';
                $.ajax({
                    url:url,
                    type:'GET',
                    cache:false,
                    data:{'_token':_token,'id':id,'type':type},
                    success:function(data){
                        console.log(data);
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
