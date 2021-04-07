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
                            <li class="breadcrumb-item"><a href="{{route('admin.menus.index')}}">Danh sách menu</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Thêm mới</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div> <!-- end container-fluid -->
    <div class="container">
        <div class="form-group">
            <a href="{{route('admin.menus.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
            <button type="submit" class="btn btn-primary waves-effect width-md waves-light float-right" onclick="document.querySelector('#form-store').submit()" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
        </div>
        <form method="post" action="{{route('admin.menus.store')}}" id="form-store" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card-box">
                        <div class="form-group">
                            <label>Vị trí</label>
                            <select id="position" class="form-control" data-toggle="select2">
                                <option value="top" @if(Session::get('menu_position') == 'top') selected @endif class="form-control">MENU TOP</option>
                                <option value="home" @if(Session::get('menu_position') == 'home') selected @endif class="form-control">MENU HOME</option>
                                <option value="left" @if(Session::get('menu_position') == 'left') selected @endif class="form-control">MEN LEFT</option>
                                <option value="right" @if(Session::get('menu_position') == 'right') selected @endif class="form-control">MENU RIGHT</option>
                                <option value="bottom" @if(Session::get('menu_position') == 'bottom') selected @endif class="form-control">MENU BOTTOM</option>
                            </select>
                            <textarea id="nestable-output" name="menuval" style="display: none;"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Tiêu đề</label>
                            <input id="title" class="form-control" onkeyup="ChangeToSlug()" name="data[name]" type="text">
                        </div>
                        <div class="form-group">
                            <label>Đường dẫn</label>
                            <input id="alias" class="form-control" name="data[url]" type="text">
                        </div>

                        <div class="form-group">
                            <label>Danh mục cha</label>
                            <select id="parent_id" name="data[parent_id]" class="form-control" data-toggle="select2">
                                <option value="0">-----</option>
                                @foreach($menus->where('parent_id', 0) as $items)
                                    <option value="{{$items->id}}">{{$items->name}}</option>
                                    {{sub_option_category($menus,$items->id)}}
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea name="description" id="summernote" rows="6" class="form-control summernote"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-box">
                        <label>Target</label>
                        <select id="target" name="data[target]" class="form-control" data-toggle="select2">
                            <option value="_self">-----</option>
                            <option value="_parent">_parent</option>
                            <option value="_top">_top</option>
                            <option value="_blank">_blank</option>
                            <option value="_self">_self</option>
                        </select>
                    </div>
                    <div class="card-box">
                        <label>Kiểu menu</label>
                        <select id="type" name="data[type]" class="form-control" data-toggle="select2">
                            <option value="default">Default</option>
                            <option value="mega">Mega Menu</option>
                        </select>
                    </div>
                    <div class="card-box">
                        <div class="position-relative box-action-image">
                            <label>Icon</label>
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
                <div class="col-md-12">
                    <a href="{{route('admin.menus.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    <button type="submit" class="btn btn-primary waves-effect width-md waves-light float-right" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                </div>
            </div> <!-- end row -->
        </form>
    </div>
    <script>
        CKEDITOR.replace('summernote',{
            height:150
        })
    </script>
@stop

@section('css')
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />
@stop
@section('javascript')
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
@stop
