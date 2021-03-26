@extends('Admin.Layout.layout')
@section('title')
    Cập nhật nội dung #{{$customer->id}}
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
                            <li class="breadcrumb-item"><a href="{{route('admin.customer.index')}}">Danh sách khách hàng</a></li>
                            <li class="breadcrumb-item active">Cập nhật nội dung</li>
                            <li class="breadcrumb-item active">#{{$customer->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật nội dung #{{$customer->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Clickable Wizard -->
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <form action="{{route('admin.customer.update',$customer)}}" method="post" enctype="multipart/form-data">
                    <div class="card-box" id="wizard-clickable">
                        @csrf
                        @method('PATCH')
                        <fieldset title="1">
                            <legend>Đánh giá</legend>
                            <div class="row mt-1">
                                <div class="col-md-7 mr-2">
                                    <div class="form-group">
                                        <label for="name">Tên khách hàng <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{$customer->name ?? old('name')}}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Đánh giá <span class="required">*</span></label>
                                        <textarea class="form-control summernote" id="summernote" name="description" required>{!! $customer->description ?? old('description') !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="mb-0">Trạng thái</label>
                                    <hr>
                                    <div class="checkbox">
                                        <input id="checkbox_public" {{$customer->public == 1 ? "checked" : ""}} type="checkbox" name="public">
                                        <label for="checkbox_public">Hiển thị</label>
                                    </div>

                                    <div class="checkbox">
                                        <input id="checkbox_status" {{$customer->status == 1 ? "checked" : ""}} type="checkbox" name="status">
                                        <label for="checkbox_status" class="">Nổi bật</label>
                                    </div>
                                    <hr>
                                    <div class="position-relative box-action-image">
                                        <label for="image">Ảnh đại diện</label>
                                        <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif</p>

                                        <input type="file" name="image" class="filestyle" id="fileUpload" data-btnClass="btn-primary">
                                        <div class="text-center mt-2 image-holder" id="image-holder">
                                            @if(file_exists($customer->image)) <img src="{{asset($customer->image)}}" alt="{{$customer->name}}"> @endif
                                        </div>
                                        <div class="box-position btn btn-default waves-effect waves-light text-left  @if(!file_exists($customer->image)) show-box @endif">
                                            {{--                                            <div class="checkbox checkbox-warning checkbox-circle checkbox-unlink-watermark">--}}
                                            {{--                                                <input id="checkbox_watermark" class="watermark" type="checkbox" name="watermark">--}}
                                            {{--                                                <label for="checkbox_watermark">Gắn watermark</label>--}}
                                            {{--                                            </div>--}}

                                            <div class="checkbox checkbox-unlink-image">
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

                            <div class="mt-1">
                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" value="{{$customer->hotline ?? old('phone')}}" name="phone">
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="email" value="{{$customer->email ?? old('email')}}" name="email">
                                </div>

                                <div class="form-group">
                                    <label for="job">Công việc</label>
                                    <input type="text" class="form-control" value="{{$customer->job ?? old('job')}}" id="job" name="job">
                                </div>

                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{$customer->address ?? old('address')}}</textarea>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset title="3">
                            <legend>Liên kết</legend>
                            <div class="mt-1">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="facebook">Facebook</label>
                                            <input type="text" class="form-control" value="{{$customer->facebook ?? old('facebook')}}" id="facebook" name="facebook">
                                        </div>
                                        <div class="form-group">
                                            <label for="skype">Skype</label>
                                            <input type="text" class="form-control" value="{{$customer->skype ?? old('skype')}}" id="skype" name="skype">
                                        </div><div class="form-group">
                                            <label for="zalo">Zalo</label>
                                            <input type="text" class="form-control" value="{{$customer->zalo ?? old('zalo')}}" id="zalo" name="zalo">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="twitter">Twitter</label>
                                            <input type="text" class="form-control" value="{{$customer->twitter ?? old('twitter')}}" id="twitter" name="twitter">
                                        </div>
                                        <div class="form-group">
                                            <label for="instagram">Instagram</label>
                                            <input type="text" class="form-control" value="{{$customer->instagram ?? old('instagram')}}"  id="instagram" name="instagram">
                                        </div>
                                        <div class="form-group">
                                            <label for="youtube">Youtube</label>
                                            <input type="text" class="form-control" value="{{$customer->youtube ?? old('youtube')}}" id="youtube" name="youtube">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <button type="submit" class="btn btn-primary stepy-finish"><span class="icon-button"><i class="fe-send"></i></span> Lưu lại</button>
                    </div>
                    <div class="mt-3">
                        <a href="{{route('admin.customer.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary waves-effect waves-light float-right" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
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
    <script src="{{asset('admin/assets/libs/bootstrap-filestyle2/bootstrap-filestyle.min.js')}}"></script>
@stop

@section('css')
    <style>
        .box-action-image img {
            max-height: 150px;
        }
    </style>

@stop
