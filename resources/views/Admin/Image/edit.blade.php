@extends('Admin.Layout.layout')
@section('title')
    Cập nhật thông tin #ID {{$media->id}}
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
                            @switch ($media->type)
                                @case(\App\Enums\MediaType::GALLERY)
                                <li class="breadcrumb-item"><a href="{{route('admin.gallerys.edit',$media->type_id)}}">{{$media->gallery->title}}</a></li>
                                @break

                                @default
                                <li class="breadcrumb-item"><a href="{{route('admin.media.index')}}">Thư viện ảnh</a></li>
                            @endswitch

                            <li class="breadcrumb-item active">Cập nhật thông tin</li>
                            <li class="breadcrumb-item active">#ID{{$media->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật thông tin #ID{{$media->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>
    <div class="container">
        <form method="post" action="{{route('admin.media.update',$media)}}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-lg-12  box-action-image">
                    <div class="card-box">
                        <div class="form-group">
                            <label>Hình ảnh</label>
                            <div class="position-absolute font-weight-normal text-primary" id="box-input" style="right:2.2rem;top:1.3rem">
                                <label class="item-input">
                                    <input type="file" name="image" class="d-none" id="fileUpload"> Chọn ảnh
                                </label>
                            </div>
                            <p class="font-13">* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>
                        </div>
                        <div class="dropzone image-holder text-center p-2" id="image-holder">
                            @if(file_exists($media->image))<img src="{{asset($media->image)}}" class="rounded">@endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Vị trí hiển thị</label>
                                    <select data-toggle="select2" name="position" class="form-control">
                                        <option value="Nomal">----</option>
                                        @foreach($position as $item)
                                            <option value="{{$item->value}}" {{$media->position == $item->value ? "selected" : ""}}>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tiêu đề </label>
                                    <input type="text" class="form-control" value="{{$media->name ?? old('name')}}" id="name" name="name">
                                </div>
                                <div class="form-group">
                                    <label>Đường dẫn</label>
                                    <input type="text" class="form-control alias" id="path" value="{{$media->path ??  old('path')}}" name="path">
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <label class="w-100">Link ảnh</label>
                                        <div class="form-control" id="urlImage">{{asset($media->image)}}</div>
                                        <div class="input-group-prepend" style="cursor: pointer" title="Coppy link ảnh" onclick="copyToClipboard('#urlImage')"><span id="basic-addon1" class="bg-primary text-white input-group-text">COPPY</span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <textarea class="form-control summernote" id="summernote" name="description">{!!$media->description ?? old('description') !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    @switch ($media->type)
                        @case(\App\Enums\MediaType::GALLERY)
                        <a href="{{route('admin.gallerys.edit',$media->type_id)}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>

                        @break

                        @default
                        <a href="{{route('admin.media.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    @endswitch

                    <button type="submit" class="btn btn-primary waves-effect width-md waves-light float-right" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                </div>
            </div>
            <!-- end row -->
        </form>
    </div>
<style>
    .box-action-image img {
        max-height: unset;
        max-height: 400px;
    }
    </style>
    <script>
        CKEDITOR.replace( 'summerbody' ,{
            height:300
        });
    </script>
@stop

@section('javascript')
    <script src="{{asset('admin/assets/libs/switchery/switchery.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/libs/select2/select2.min.js"></script>
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
