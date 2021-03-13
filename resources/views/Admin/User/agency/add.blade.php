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
                            <li class="breadcrumb-item"><a href="{{route('admin.agencys.index')}}">Nhà cung cấp</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Thêm mới</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <form method="post" action="{{route('admin.agencys.store')}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                <div class="col-lg-12">
                    <div class="card-box">
                        <div class="form-group">
                            <label class="font-weight-bold">Họ & tên <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{old('data.name')}}" id="name" name="data[name]" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Số điện thoại</label>
                            <input type="text" class="form-control" value="{{old('data.phone')}}" id="phone" name="data[phone]">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Địa chỉ</label>
                            <textarea name="data[address]" id="address" class="form-control" rows="3"></textarea>
                        </div>
{{--                        <div class="form-group">--}}
{{--                            <label class="font-weight-bold">Công nợ </label>--}}
{{--                            <input type="text" class="form-control" value="{{old('data.debt') ?? 0}}" id="debt" name="data[debt]">--}}
{{--                        </div>--}}
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Trạng thái </label>
                            <div class="checkbox checkbox-primary checkbox-circle">
                                <input id="checkbox_public" checked type="checkbox" name="data[status]" value="1">
                                <label for="checkbox_public">Hiển thị</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 text-center">
                    <div class="card-box">
                        <a href="{{route('admin.agencys.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary waves-effect width-md waves-light" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </form>
    </div>

@stop

@section('javascript')
    <script type="text/javascript">
        jQuery(document).ready(function($) {

            var alias = $('.alias');
            var title_seo = $('input[name="title_seo"]');
            var description_seo = $('input[name="description_seo"]');

            title_seo.keyup(function() {
                /* Act on the event */
                $('.title-seo').html($(this).val());
                return false;
            });

            description_seo.keyup(function() {
                /* Act on the event */
                $('.description-seo').html($(this).val());
                return false;
            });
            alias.on('keyup change',function(){
                var url = "{{route('home')}}/";
                $('.alias-seo').text(url + $(this).val() + '.html');
            })

        });
    </script>
    <script src="{{asset('admin/assets/libs/switchery/switchery.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/libs/select2/select2.min.js"></script>
    <script src="{{asset('admin/assets/libs/jquery-mockjax/jquery.mockjax.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/autocomplete/jquery.autocomplete.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-filestyle2/bootstrap-filestyle.min.js')}}"></script>

    <!-- Init js-->
    <script src="{{asset('admin/assets/js/pages/form-advanced.init.js')}}"></script>

    <!-- Summernote js -->
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/libs/summernote/summernote-bs4.min.js"></script>

    <!-- Init js -->
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/js/pages/form-summernote.init.js"></script>

@stop

@section('css')
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />

    <!-- Summernote css -->
    <link href="https://coderthemes.com/adminox/layouts/vertical/assets/libs/summernote/summernote-bs4.css" rel="stylesheet" type="text/css" />

@stop
