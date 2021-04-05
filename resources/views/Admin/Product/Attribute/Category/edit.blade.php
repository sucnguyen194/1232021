@extends('Admin.Layout.layout')
@section('title')
    Cập nhật nội dung #{{$attribute_category->id}}
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
                            <li class="breadcrumb-item"><a href="{{route('admin.attributes.categories.index')}}">Danh mục bộ lọc</a></li>
                            <li class="breadcrumb-item active">Cập nhật nội dung #{{$attribute_category->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật nội dung #{{$attribute_category->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>
    <div class="container">
        <form method="post" action="{{route('admin.attributes.categories.update',$attribute_category)}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @method('PUT')
                <div class="col-lg-12">
                    <div class="card-box">
                        <div class="form-group">
                            <label>Tên danh mục <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{$attribute_category->name}}" id="name" name="data[name]" required>
                        </div>
                        <div class="">
                            <div class="checkbox">
                                <input id="checkbox_public" {{$attribute_category->public == 1 ? "checked" : ""}} type="checkbox" name="data[public]" value="1">
                                <label for="checkbox_public">Hiển thị</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <a href="{{route('admin.attributes.categories.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    <button type="submit" class="btn btn-primary waves-effect width-md waves-light float-right" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                </div>
            </div>
            <!-- end row -->
        </form>
    </div>
@stop
