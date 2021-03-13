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
                            <li class="breadcrumb-item"><a href="{{route('admin.news.index')}}">Danh sách bài viết</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{$news->title}} <small>({{$lang}})</small></h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <form method="post" action="" enctype="multipart/form-data">
            <div class="row">
                @csrf
                <div class="col-lg-8">
                    <div class="card-box">
                        <div class="form-group">
                            <label>Tiêu đề <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{old('title')}}" id="title" onkeyup="ChangeToSlug();" name="title" required>
                        </div>


                        <div class="form-group">
                            <label>Đường dẫn <span class="required">*</span></label>
                            <input type="text" class="form-control alias" id="alias" value="{{old('alias')}}" name="alias" required>
                        </div>

                        <div class="form-group">
                            <label>Danh mục chính</label>
                            <select class="form-control" data-toggle="select2" name="category">
                                <option value="0">Chọn danh mục</option>
                                @foreach($category as $item )
                                    <option value="{{$item->id}}" {{old('category') == $item->id ? "selected" : ""}} class="font-weight-bold">{{$item->title}}</option>
                                    {{sub_menu_checkbox($category_sub,$item->id)}}
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Danh mục phụ</label>
                            <p>* Ghi chú: Chọn được nhiều danh mục</p>
                            <select class="form-control select2-multiple" data-toggle="select2" multiple="multiple" name="category_id[]" data-placeholder="Chọn danh mục">
                                @foreach($category as $item )
                                    <option value="{{$item->id}}" {{old('category') == $item->id ? "selected" : ""}} class="font-weight-bold">{{$item->title}}</option>
                                    {{sub_option_category($category_sub ,$item->id)}}
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="description">{!! old('description') !!}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Chi tiết</label>
                            <textarea class="form-control summerbody" id="summerbody" name="content">{!! old('content') !!}</textarea>
                        </div>

                    </div>

                    <div class="card-box">
                        <div class="form-group">
                            <label>Title seo</label>
                            <p>* Ghi chú: Giới hạn tối đa 65 ký tự</p>

                            <input type="text" maxlength="70" value="{{old('title_seo')}}" name="title_seo" class="form-control" id="alloptions" />
                        </div>
                        <div class="form-group">
                            <label>Description seo</label>
                            <p>* Ghi chú: Giới hạn tối đa 158 ký tự</p>
                            <input class="form-control" maxlength="158" value="{{old('description_seo')}}" name="description_seo" id="alloptions">
                        </div>
                        <div class="form-group">
                            <label>Keyword seo</label>
                            <p>* Ghi chú: Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                            <input type="text" name="keyword_seo" value="{{old('keyword_seo')}}" class="form-control"  data-role="tagsinput"/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-box">
                        <h4 class="header-title mb-2">Trạng thái</h4>

                        <div class="checkbox checkbox-primary checkbox-circle">
                            <input id="checkbox_public" checked type="checkbox" name="public">
                            <label for="checkbox_public">Hiển thị</label>
                        </div>

                        <div class="checkbox checkbox-primary checkbox-circle">
                            <input id="checkbox_status" type="checkbox" name="status">
                            <label for="checkbox_status">Nổi bật</label>
                        </div>
                    </div>

                    <div class="card-box position-relative box-action-image">
                        <h4 class="header-title mb-2">Ảnh đại diện</h4>
                        <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>

                        <input type="file" name="image" class="filestyle" id="fileUpload" data-btnClass="btn-primary">
                        <div class="text-center mt-2 image-holder" id="image-holder">

                        </div>
                        <div class="box-position btn btn-purple waves-effect waves-light text-left show-box">

                            <div class="checkbox checkbox-warning checkbox-circle checkbox-unlink-watermark">
                                <input id="checkbox_watermark" class="watermark" type="checkbox" name="watermark">
                                <label for="checkbox_watermark">Gắn watermark</label>
                            </div>

                            <div class="checkbox checkbox-warning checkbox-circle checkbox-unlink-image">
                                <input id="checkbox_unlink" class="unlink-image" type="checkbox" name="unlink">
                                <label for="checkbox_unlink" class="mb-0">Xóa ảnh</label>
                            </div>

                        </div>
                    </div>
                    <div class="card-box tags">
                        <h4 class="header-title mb-2">Tags</h4>
                        <p>* Ghi chú: Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>
                        <input class="form-control" name="tags" data-role="tagsinput" placeholder="add tags">
                    </div>
                </div>

                <div class="col-lg-12 text-center">
                    <div class="card-box">
                        <a href="{{route('admin.news.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary waves-effect width-md waves-light" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
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

{{--    <!-- Summernote js -->--}}
{{--    <script src="{{asset('admin/assets/libs/summernote/summernote-bs4.min.js')}}"></script>--}}

{{--    <!-- Init js -->--}}
{{--    <script src="{{asset('admin/assets/js/pages/form-summernote.init.js')}}"></script>--}}

    <!-- Plugins js -->
    <script src="{{asset('admin/assets/libs/katex/katex.min.js')}}"></script>

    <script src="{{asset('admin/assets/libs/quill/quill.min.js')}}"></script>
    <!-- Init js-->
    <script src="{{asset('admin/assets/js/pages/form-quilljs.init.js')}}"></script>
@stop

@section('css')
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />

{{--    <!-- Summernote css -->--}}
{{--    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />--}}

    <!-- Plugins css -->
    <link href="{{asset('admin/assets/libs/quill/quill.core.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/quill/quill.bubble.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/quill/quill.snow.css')}}" rel="stylesheet" type="text/css" />
@stop
