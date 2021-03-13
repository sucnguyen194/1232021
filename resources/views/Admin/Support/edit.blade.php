@extends('Admin.Layout.layout')
@section('title')
    Cập nhật nội dung #{{$support->id}}
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
                            <li class="breadcrumb-item"><a href="{{route('admin.support.index')}}">Đội ngũ hỗ trợ</a></li>
                            <li class="breadcrumb-item active">Cập nhật nội dung</li>
                            <li class="breadcrumb-item active">#{{$support->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật nội dung #{{$support->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Clickable Wizard -->
        <div class="row">
            <div class="col-md-12">
                <form action="{{route('admin.support.update',$support)}}" method="post" enctype="multipart/form-data">
                    <div class="card-box" id="wizard-clickable">
                        @csrf
                        @method('PATCH')
                        <fieldset title="1">
                            <legend>Lời nhắn</legend>
                            <div class="row mt-3">
                                <div class="col-md-8">
                                    <div class="card-box">
                                        <div class="form-group">
                                            <label for="account">Tên nhân viên <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{$support->name ?? old('name')}}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Lời nhắn <span class="required">*</span></label>
                                            <textarea class="form-control summernote" id="summernote" name="description" required>{!! $support->description ?? old('description') !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-box">
                                        <h4 class="header-title mb-2">Trạng thái</h4>

                                        <div class="checkbox checkbox-primary checkbox-circle">
                                            <input id="checkbox_public" {{$support->public == 1 ? "checked" : ""}} type="checkbox" name="public">
                                            <label for="checkbox_public">Hiển thị</label>
                                        </div>

                                        <div class="checkbox checkbox-primary checkbox-circle">
                                            <input id="checkbox_status" {{$support->status == 1 ? "checked" : ""}} type="checkbox" name="status">
                                            <label for="checkbox_status" class="">Nổi bật</label>
                                        </div>
                                    </div>
                                    <div class="card-box position-relative box-action-image">
                                        <label for="phone">Ảnh đại diện</label>
                                        <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>

                                        <input type="file" name="image" class="filestyle" id="fileUpload" data-btnClass="btn-primary">
                                        <div class="text-center mt-2 image-holder" id="image-holder">
                                            @if(file_exists($support->image)) <img src="{{asset($support->image)}}" alt="{{$support->name}}"> @endif
                                        </div>
                                        <div class="box-position btn btn-purple waves-effect waves-light text-left  @if(!file_exists($support->image)) show-box @endif">
                                            {{--                                            <div class="checkbox checkbox-warning checkbox-circle checkbox-unlink-watermark">--}}
                                            {{--                                                <input id="checkbox_watermark" class="watermark" type="checkbox" name="watermark">--}}
                                            {{--                                                <label for="checkbox_watermark">Gắn watermark</label>--}}
                                            {{--                                            </div>--}}

                                            <div class="checkbox checkbox-warning checkbox-circle checkbox-unlink-image">
                                                <input id="checkbox_unlink" class="unlink-image" type="checkbox" name="unlink">
                                                <label for="checkbox_unlink" class="mb-0">Xóa ảnh</label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset title="2">
                            <legend>Thông tin cá nhân</legend>
                            <div class="row mt-3">
                                <div class="col-md-4 offset-md-4">
                                    <div class="card-box">
                                        <div class="form-group">
                                            <label for="phone">Số điện thoại</label>
                                            <input type="text" class="form-control" id="phone" value="{{$support->hotline ?? old('phone')}}" name="phone">
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">Email</label>
                                            <input type="text" class="form-control" id="email" value="{{$support->email ?? old('email')}}" name="email">
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">Công việc</label>
                                            <input type="text" class="form-control" value="{{$support->job ?? old('job')}}" id="job" name="job">
                                        </div>

                                        <div class="form-group">
                                            <label for="note">Địa chỉ</label>
                                            <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{$support->address ?? old('address')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </fieldset>
                        <fieldset title="3">
                            <legend>Liên kết</legend>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="card-box">
                                        <div class="form-group">
                                            <label for="phone">Facebook</label>
                                            <input type="text" class="form-control" value="{{$support->facebook ?? old('facebook')}}" id="facebook" name="facebook">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Skype</label>
                                            <input type="text" class="form-control" value="{{$support->skype ?? old('skype')}}" id="skype" name="skype">
                                        </div><div class="form-group">
                                            <label for="phone">Zalo</label>
                                            <input type="text" class="form-control" value="{{$support->zalo ?? old('zalo')}}" id="zalo" name="zalo">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card-box">
                                        <div class="form-group">
                                            <label for="phone">Twitter</label>
                                            <input type="text" class="form-control" value="{{$support->twitter ?? old('twitter')}}" id="twitter" name="twitter">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Instagram</label>
                                            <input type="text" class="form-control" value="{{$support->instagram ?? old('instagram')}}"  id="instagram" name="instagram">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Youtube</label>
                                            <input type="text" class="form-control" value="{{$support->youtube ?? old('youtube')}}" id="youtube" name="youtube">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <button type="submit" class="btn btn-primary stepy-finish"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                    </div>
                    <div class="card-box mt-3 text-center">
                        <a href="{{route('admin.support.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- End row -->
    </div

        @stop

        @section('javascript')
        <!--Form Wizard-->
    <script src="{{asset('admin/assets/libs/stepy/jquery.stepy.js')}}"></script>

    <!-- Validation init js-->
    <script src="{{asset('admin/assets/js/pages/wizard.init.js')}}"></script>

    <!-- Summernote js -->
{{--    <script src="{{asset('admin/assets/libs/summernote/summernote-bs4.min.js')}}"></script>--}}

{{--    <!-- Init js -->--}}
{{--    <script src="{{asset('admin/assets/js/pages/form-summernote.init.js')}}"></script>--}}

    <script src="{{asset('admin/assets/libs/bootstrap-filestyle2/bootstrap-filestyle.min.js')}}"></script>
@stop

@section('css')
    <style>
        .box-action-image img {
            max-height: 150px;
        }
    </style>
    <!-- Summernote css -->
{{--    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />--}}

@stop
