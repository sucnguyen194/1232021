@extends('Admin.Layout.layout')
@section('title')
    Thêm mới
@stop
@section('content')

    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.lang.index')}}">Danh sách ngôn ngữ</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Thêm mới</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

            <div class="row">
                <form method="post" class="w-100" action="{{route('admin.lang.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12">
                        <div class="card-box">
                            <label>Danh sách ngôn ngữ hiện tại <span class="required">*</span></label>
                            @foreach($lang as $item)
                                <blockquote class="blockquote mb-0">
                                    <footer class="blockquote-footer"><cite title="{{$item->name}} ({{$item->value}})" class="font-weight-bold">{{$item->name}} ({{$item->value}})</cite></footer>
                                </blockquote>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card-box">
                            <div class="form-group">
                                <label>Tên ngôn ngữ <span class="required">*</span></label>
                                <p>* Ghi chú: tên ngôn ngữ phải khác nhau</p>
                                <input type="text" class="form-control" value="{{old('name')}}" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>Giá trị <span class="required">*</span></label>
                                <p>* Ghi chú: Giới hạn tối đa 2 ký tự</p>
                                <input type="text" maxlength="2" value="{{old('value')}}" name="value" class="form-control" id="alloptions" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 text-center">
                        <div class="card-box">
                            <a href="{{route('admin.lang.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                            <button type="submit" class="btn btn-primary waves-effect width-md waves-light" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- end row -->
    </div>
@stop

@section('javascript')

    <script src="{{asset('admin/assets/libs/switchery/switchery.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/libs/select2/select2.min.js"></script>
    <script src="{{asset('admin/assets/libs/jquery-mockjax/jquery.mockjax.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/autocomplete/jquery.autocomplete.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <!-- Init js-->
    <script src="{{asset('admin/assets/js/pages/form-advanced.init.js')}}"></script>

    <!-- Summernote js -->
    <script src="{{asset('admin/assets/libs/summernote/summernote-bs4.min.js')}}"></script>

    <!-- Init js -->
    <script src="{{asset('admin/assets/js/pages/form-summernote.init.js')}}"></script>
@stop

@section('css')
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />

    <!-- Summernote css -->
    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />

@stop
