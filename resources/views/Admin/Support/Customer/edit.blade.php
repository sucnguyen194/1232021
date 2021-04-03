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
                            <li class="breadcrumb-item"><a href="{{route('admin.supports.customers.index')}}">Đội ngũ hỗ trợ</a></li>
                            <li class="breadcrumb-item active">Cập nhật nội dung #ID{{$customer->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật nội dung #{{$customer->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <!-- End row -->
    </div>
    <div class="container">

        <!-- Clickable Wizard -->
        <div class="row">
            <form action="{{route('admin.supports.update',$customer)}}" method="post" enctype="multipart/form-data">
                <div id="wizard-clickable">
                    @csrf
                    @method('PATCH')
                    <fieldset title="1">
                        <legend>Đánh giá</legend>
                        <div class="row mt-1">
                            <div class="col-md-8">
                                <div class="card-box">
                                    <div class="form-group">
                                        <label for="name">Tên khách hàng <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="data[name]" id="name" value="{{$customer->name}}" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="description">Ý kiến khách hàng <span class="required">*</span></label>
                                        <textarea class="form-control summernote" id="summernote" name="data[description]" required>{!! $customer->description !!}</textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="card-box">
                                    <label class="mb-0">Trạng thái</label>
                                    <hr>
                                    <div class="checkbox">
                                        <input id="checkbox_public" {{$customer->public == 1 ? "checked" : ""}} type="checkbox" name="data[public]" value="1">
                                        <label for="checkbox_public">Hiển thị</label>
                                    </div>

                                    <div class="checkbox">
                                        <input id="checkbox_status" type="checkbox" {{$customer->status == 1 ? "checked" : ""}} name="status">
                                        <label for="checkbox_status" class="mb-0">Nổi bật</label>
                                    </div>
                                </div>

                                <div class="card-box position-relative box-action-image">
                                    <label>Hình ảnh</label>
                                    <div class="position-absolute font-weight-normal text-primary" id="box-input" style="right:2.2rem;top:1.3rem">
                                        <label class="item-input">
                                            <input type="file" name="image" class="d-none" id="fileUpload"> Chọn ảnh
                                        </label>
                                    </div>
                                    <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif</p>
                                    <div class="dropzone p-2 text-center">
                                        @if(!file_exists($customer->image))
                                            <div class="dz-message text-center needsclick mb-2" id="remove-label">
                                                <label for="fileUpload" class="w-100 mb-0">
                                                    <div class="icon-dropzone pt-2">
                                                        <i class="h1 text-muted dripicons-cloud-upload"></i>
                                                    </div>
                                                    <span class="text-muted font-13">Sử dụng nút <strong>Chọn ảnh</strong> để thêm ảnh</span>
                                                </label>
                                            </div>
                                        @endif
                                        <div class="{{!file_exists($customer->image) ? "show-box" : ""}} image-holder pl-0 mb-0 w-100">
                                            @if(file_exists($customer->image)) <img src="{{asset($customer->image)}}" alt="{{$customer->name}}"> @endif
                                        </div>
                                        <div class="box-position btn btn-default waves-effect waves-light text-left @if(!file_exists($customer->image)) show-box @endif">
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
                                        <label for="hotline">Số điện thoại</label>
                                        <input type="text" class="form-control" id="hotline" value="{{$customer->hotline}}" name="data[hotline]">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" value="{{$customer->email}}" name="data[email]">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="job">Công việc</label>
                                        <input type="text" class="form-control" value="{{$customer->job}}" id="job" name="data[job]">
                                    </div>

                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label for="address">Địa chỉ</label>
                                        <textarea name="data[address]" id="address" rows="10" class="form-control">{{$customer->address}}</textarea>
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
                                        <input type="text" class="form-control" value="{{$customer->facebook}}" id="facebook" name="data[facebook]">
                                    </div>
                                    <div class="form-group">
                                        <label for="skype">Skype</label>
                                        <input type="text" class="form-control" value="{{$customer->skype}}" id="skype" name="data[skype]">
                                    </div><div class="form-group">
                                        <label for="zalo">Zalo</label>
                                        <input type="text" class="form-control" value="{{$customer->zalo}}" id="zalo" name="data[zalo]">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="twitter">Twitter</label>
                                        <input type="text" class="form-control" value="{{$customer->twitter}}" id="twitter" name="data[twitter]">
                                    </div>
                                    <div class="form-group">
                                        <label for="instagram">Instagram</label>
                                        <input type="text" class="form-control" value="{{$customer->instagram}}"  id="instagram" name="data[instagram]">
                                    </div>
                                    <div class="form-group">
                                        <label for="youtube">Youtube</label>
                                        <input type="text" class="form-control" value="{{$customer->youtube}}" id="youtube" name="data[youtube]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <button type="submit" class="btn btn-primary stepy-finish"><span class="icon-button"><i class="fe-send"></i></span> Lưu lại</button>
                </div>
                <div class="mt-3">
                    <a href="{{route('admin.supports.customers.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    <button type="submit" class="btn btn-primary waves-effect waves-light float-right" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                </div>
            </form>
        </div>
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

