@extends('Admin.Layout.layout')
@section('title')
    Sửa đường dẫn #{{$alias->id}}
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
                            <li class="breadcrumb-item"><a href="{{route('admin.alias.index')}}">Danh sách đường dẫn</a></li>
                            <li class="breadcrumb-item active">Sửa đường dẫn</li>
                            <li class="breadcrumb-item active">ID #{{$alias->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Sửa đường dẫn #{{$alias->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <form method="post" action="{{route('admin.alias.update',$alias)}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @method('PATCH')
                <div class="col-lg-12">
                    <div class="card-box">
                        <div class="form-group">
                            <label>Tiêu đề<span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{old('title')}}" id="title" onkeyup="ChangeToSlug();" name="title">
                        </div>

                        <div class="form-group">
                            <label>Đường dẫn</label>
                            <input type="text" class="form-control alias" id="alias" readonly value="{{$alias->alias ?? old('alias')}}" name="alias" required>
                        </div>

                        <div class="form-group">
                            <label> Phân loại <span class="required">*</span></label>
                            <select class="form-control" data-toggle="select2" name="type">
                                <option value="0">-----</option>
                                @foreach(\App\Enums\AliasType::getInstances() as $item)
                                    <option value="{{$item->key}}" {{old('type') == $item->key ||  $alias->type == $item->key ? "selected" : ""}}> {{$item->description}} </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>ID</label>
                            <input type="number" class="form-control alias" id="type_id" min="1" value="{{$alias->type_id ?? old('type_id')}}" name="type_id">
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 text-center">
                    <div class="card-box">
                        <a href="{{route('admin.alias.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary waves-effect width-md waves-light" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                    </div>
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

    <!-- Summernote css -->
    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />

    <!-- Plugins css -->
    <link href="{{asset('admin/assets/libs/quill/quill.core.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/quill/quill.bubble.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/quill/quill.snow.css')}}" rel="stylesheet" type="text/css" />
@stop
