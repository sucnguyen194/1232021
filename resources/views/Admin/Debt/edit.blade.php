@extends('Admin.Layout.layout')
@section('title')
    Cập nhật thông tin #{{$debt->id}}
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
                            <li class="breadcrumb-item"><a href="{{route('admin.debts.index')}}">Vay vốn</a></li>
                            <li class="breadcrumb-item active">Cập nhật thông tin #{{$debt->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật thông tin #{{$debt->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>
    <div class="container">
        <form method="post" action="{{route('admin.debts.update',$debt)}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @method('PUT')
                <div class="col-lg-12">
                    <div class="card-box">
                        <div class="form-group">
                            <label>Họ & tên <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{$debt->name}}" id="name" name="data[name]" required>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" class="form-control" value="{{$debt->phone}}" id="phone" name="data[phone]">
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ</label>
                            <textarea name="data[address]" id="address" class="form-control" rows="3">{!! $debt->address !!}</textarea>
                        </div>
                        <div class="form-group mb-0">
                            <div class="checkbox">
                                <input id="checkbox_public" {{$debt->status == 1 ? "checked" : ""}} type="checkbox" name="status" value="1">
                                <label for="checkbox_public">Hiển thị</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <a href="{{route('admin.debts.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    <button type="submit" class="btn btn-primary waves-effect width-md waves-light float-right" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                </div>
            </div>
            <!-- end row -->
        </form>
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
    <script src="{{asset('admin/assets/libs/bootstrap-filestyle2/bootstrap-filestyle.min.js')}}"></script>

    <!-- Init js-->
    <script src="{{asset('admin/assets/js/pages/form-advanced.init.js')}}"></script>

@stop

@section('css')
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />

@stop

