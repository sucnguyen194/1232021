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
                            <li class="breadcrumb-item"><a href="{{route('admin.customer.index')}}">Ý kiến khách hàng</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Thêm mới</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Clickable Wizard -->
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <form action="{{route('admin.customer.store')}}" method="post" enctype="multipart/form-data">
                    <div id="wizard-clickable">
                        @csrf
                        <fieldset title="1">
                            <legend>Đánh giá</legend>
                            <div class="row mt-1">
                                <div class="col-md-8">
                                    <div class="card-box">
                                        <div class="form-group">
                                            <label for="name">Tên khách hàng <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{old('name')}}" required>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label for="description">Đánh giá <span class="required">*</span></label>
                                            <textarea class="form-control summernote" id="summernote" name="description" required>{!! old('description') !!}</textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="card-box">
                                        <label class="mb-0">Trạng thái</label>
                                        <hr>
                                        <div class="checkbox">
                                            <input id="checkbox_public" checked type="checkbox" name="public">
                                            <label for="checkbox_public">Hiển thị</label>
                                        </div>

                                        <div class="checkbox">
                                            <input id="checkbox_status" type="checkbox" name="status">
                                            <label for="checkbox_status" class="mb-0">Nổi bật</label>
                                        </div>
                                    </div>

                                    <div class="card-box">
                                        <div class="position-relative box-action-image">
                                            <label>Hình ảnh</label>
                                            <div class="position-absolute font-weight-normal text-primary" id="box-input" style="right:0;top:0">
                                                <label class="item-input">
                                                    <input type="file" name="image" class="d-none" id="fileUpload"> Chọn ảnh
                                                </label>
                                            </div>
                                            <p class="font-13">* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>
                                            <div class="dropzone p-2 text-center image-holder" id="image-holder">
                                                <label for="fileUpload" class="w-100 mb-0">
                                                    <div class="icon-dropzone pt-2">
                                                        <i class="h1 text-muted dripicons-cloud-upload"></i>
                                                    </div>
                                                    <span class="text-muted font-13">Sử dụng nút <strong>Chọn ảnh</strong> để thêm ảnh</span>
                                                </label>
                                            </div>
                                            <div class="box-position btn btn-default waves-effect waves-light text-left show-box">
                                                <div class="checkbox checkbox-unlink-image">
                                                    <input id="checkbox_unlink" class="unlink-image" type="checkbox" name="unlink">
                                                    <label for="checkbox_unlink" class="mb-0">Xóa ảnh</label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset title="2">
                            <legend>Thông tin cá nhân</legend>

                            <div class="card-box mt-1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="phone">Số điện thoại</label>
                                            <input type="text" class="form-control" id="phone" value="{{old('phone')}}" name="phone">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" id="email" value="{{old('email')}}" name="email">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="job">Công việc</label>
                                            <input type="text" class="form-control" value="{{old('job')}}" id="job" name="job">
                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-0">
                                            <label for="address">Địa chỉ</label>
                                            <textarea name="address" id="address" rows="10" class="form-control">{{ old('address')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </fieldset>
                        <fieldset title="3">
                            <legend>Liên kết</legend>
                            <div class="card-box mt-1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="facebook">Facebook</label>
                                            <input type="text" class="form-control" value="{{old('facebook')}}" id="facebook" name="facebook">
                                        </div>
                                        <div class="form-group">
                                            <label for="skype">Skype</label>
                                            <input type="text" class="form-control" value="{{old('skype')}}" id="skype" name="skype">
                                        </div><div class="form-group">
                                            <label for="zalo">Zalo</label>
                                            <input type="text" class="form-control" value="{{old('zalo')}}" id="zalo" name="zalo">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="twitter">Twitter</label>
                                            <input type="text" class="form-control" value="{{old('twitter')}}" id="twitter" name="twitter">
                                        </div>
                                        <div class="form-group">
                                            <label for="instagram">Instagram</label>
                                            <input type="text" class="form-control" value="{{old('instagram')}}"  id="instagram" name="instagram">
                                        </div>
                                        <div class="form-group">
                                            <label for="youtube">Youtube</label>
                                            <input type="text" class="form-control" value="{{old('youtube')}}" id="youtube" name="youtube">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <button type="submit" class="btn btn-primary stepy-finish"><span class="icon-button"><i class="fe-send"></i></span> Lưu lại</button>
                    </div>
                    <div class="mt-3">
                        <a href="{{route('admin.customer.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary waves-effect waves-light float-right" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End row -->
    </div>
<script>
    CKEDITOR.replace( 'summernote' ,{
        height:300
    });
    </script>
        @stop

        @section('javascript')
        <!--Form Wizard-->
    <script src="{{asset('admin/assets/libs/stepy/jquery.stepy.js')}}"></script>
    <!-- Validation init js-->
    <script src="{{asset('admin/assets/js/pages/wizard.init.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-filestyle2/bootstrap-filestyle.min.js')}}"></script>
@stop

@section('css')
    <style>
        .box-action-image img {
            max-height: 150px;
        }
    </style>

@stop
