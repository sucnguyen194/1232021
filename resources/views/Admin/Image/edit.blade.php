@extends('Admin.Layout.layout')
@section('title')
    Sửa #ID {{$media->id}}
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

                            <li class="breadcrumb-item active">Sửa</li>
                            <li class="breadcrumb-item active">#ID{{$media->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Sửa #ID {{$media->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <form method="post" action="{{route('admin.media.update',$media)}}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-lg-7">
                    <div class="card-box">
                        <div class="form-group">
                            <label class="font-weight-bold">Vị trí hiển thị</label>

                            <select data-toggle="select2" name="position" class="form-control">
                                <option value="Nomal">----</option>
                                @foreach($position as $item)
                                    <option value="{{$item->value}}" {{$media->position == $item->value ? "selected" : ""}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tiêu đề </label>
                            <input type="text" class="form-control" value="{{$media->name ?? old('name')}}" id="name" name="name">
                        </div>


                        <div class="form-group">
                            <label class="font-weight-bold">Đường dẫn</label>
                            <input type="text" class="form-control alias" id="path" value="{{$media->path ??  old('path')}}" name="path">
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="description">{!!$media->description ?? old('description') !!}</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">

                    <div class="card-box">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Hình ảnh</label>
                            <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>
                            <input type="file" name="image" class="filestyle" id="fileUpload" data-btnClass="btn-primary">
                        </div>

                    </div>
                    <div class="card-box autohide-scroll" style="height:275px">
                            <div id="grid-gallery" class="grid-gallery">
                                <section class="grid-wrap">
                                    <ul class="grid" id="list-item">
                                        <span class="image-holder" id="image-holder">
                                        @if(file_exists($media->image))<li id="item"><img src="{{asset($media->image)}}" class="rounded"> </li>@endif
                                        </span>
                                    </ul>
                                </section><!-- // grid-wrap -->
                            </div><!-- // grid-gallery -->
                        </div>
                </div>
                <div class="col-lg-12 text-center">
                    <div class="card-box">
                        @switch ($media->type)
                            @case(\App\Enums\MediaType::GALLERY)
                            <a href="{{route('admin.gallerys.edit',$media->type_id)}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>

                            @break

                            @default
                            <a href="{{route('admin.media.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        @endswitch

                        <button type="submit" class="btn btn-primary waves-effect width-md waves-light" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </form>
    </div>

@stop

@section('javascript')
    <script src="{{asset('admin/js/grid/modernizr.custom.js')}}"></script>
    <script src="{{asset('admin/js/grid/imagesloaded.pkgd.min.js')}}"></script>
    <script src="{{asset('admin/js/grid/masonry.pkgd.min.js')}}"></script>
    <script src="{{asset('admin/js/grid/classie.js')}}"></script>
    <script src="{{asset('admin/js/grid/cbpGridGallery.js')}}"></script>

    <script>
        new CBPGridGallery( document.getElementById( 'grid-gallery' ) );
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

    <!-- scrollbar init-->
    <script src="{{asset('admin/assets/js/pages/scrollbar.init.js')}}"></script>
@stop

@section('css')
    <style>
        .grid-gallery li {
            list-style: none;

        }
    </style>
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />
    <!-- Plugins css -->
    <link href="{{asset('admin/assets/libs/quill/quill.core.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/quill/quill.bubble.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/quill/quill.snow.css')}}" rel="stylesheet" type="text/css" />
@stop
