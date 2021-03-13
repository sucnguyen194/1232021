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
                            <li class="breadcrumb-item"><a href="{{route('admin.media.index')}}">Thư viện ảnh</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Thêm mới</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <form method="post" action="{{route('admin.media.store')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-7">
                     <div class="card-box">
                      <div class="form-group">
                                <label class="font-weight-bold">Vị trí hiển thị</label>

                                <select data-toggle="select2" name="position" class="form-control">
                                    <option value="Nomal">----</option>
                                    @foreach($position as $item)
                                        <option value="{{$item->value}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold">Tiêu đề </label>
                                <input type="text" class="form-control" value="{{old('name')}}" id="name" name="name">
                            </div>


                            <div class="form-group">
                                <label class="font-weight-bold">Đường dẫn</label>
                                <input type="text" class="form-control alias" id="path" value="{{old('path')}}" name="path">
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Mô tả</label>
                                <textarea class="form-control summernote" id="summernote" name="description">{!! old('description') !!}</textarea>
                            </div>

                        </div>
                </div>
                <div class="col-lg-5 box-action-image">
                    <div class="card-box">
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Hình ảnh</label>
                            <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>
                            <input type="file" name="image[]" multiple class="filestyle" id="fileUploadMultiple" data-btnClass="btn-primary">
                        </div>
                    </div>
                    <div class="card-box autohide-scroll" style="height:275px">
                        <div id="grid-gallery" class="grid-gallery">
                            <section class="grid-wrap">
                                <ul class="grid" id="list-item">
                                    <span class="image-holder" id="image-holder">

                                    </span>
                                </ul>
                            </section><!-- // grid-wrap -->
                        </div><!-- // grid-gallery -->
                    </div>
                </div>
                <div class="col-lg-12 text-center">
                    <div class="card-box">
                        <a href="{{route('admin.media.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
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

{{--    <!-- Summernote js -->--}}
{{--    <script src="{{asset('admin/assets/libs/summernote/summernote-bs4.min.js')}}"></script>--}}

{{--    <!-- Init js -->--}}
{{--    <script src="{{asset('admin/assets/js/pages/form-summernote.init.js')}}"></script>--}}
    <!-- scrollbar init-->
    <script src="{{asset('admin/assets/js/pages/scrollbar.init.js')}}"></script>
@stop

@section('css')

    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />

{{--    <!-- Summernote css -->--}}
{{--    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />--}}

@stop
