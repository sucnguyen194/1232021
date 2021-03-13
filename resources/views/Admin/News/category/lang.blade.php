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
                            <li class="breadcrumb-item"><a href="{{route('admin.news_category.index')}}">Danh mục bài viết</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>

                        </ol>
                    </div>
                    <h4 class="page-title">{{$cate->title}} <small>({{$lang}})</small> </h4>
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
                            <label class="font-weight-bold">Tiêu đề <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{old('title')}}" id="title" onkeyup="ChangeToSlug();" name="title" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Danh mục cha</label>
                            <select class="form-control" data-toggle="select2" name="category">
                                <option value="0">Chọn danh mục</option>
                                @foreach($category->where('parent_id', 0) as $item )
                                    <option value="{{$item->id}}" {{old('category') == $item->id ? "selected" : ""}} class="font-weight-bold">{{$item->title}}</option>
                                    {{sub_option_category($category,$item->id)}}
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Vị trí</label>
                            <select class="form-control" data-toggle="select2" name="position">
                                <option value="0">Chọn ví trí</option>
                                <option value="1" {{old('position') == 1 ? "selected" : ""}}>Vị trí số 1</option>
                                <option value="2" {{old('position') == 2 ? "selected" : ""}}>Vị trí số 2</option>
                                <option value="3" {{old('position') == 3 ? "selected" : ""}}>Vị trí số 3</option>
                                <option value="4" {{old('position') == 4 ? "selected" : ""}}>Vị trí số 4</option>
                                <option value="5" {{old('position') == 5 ? "selected" : ""}}>Vị trí số 5</option>
                                <option value="6" {{old('position') == 6 ? "selected" : ""}}>Vị trí số 6</option>
                                <option value="7" {{old('position') == 7 ? "selected" : ""}}>Vị trí số 7</option>
                                <option value="8" {{old('position') == 8 ? "selected" : ""}}>Vị trí số 8</option>
                                <option value="9" {{old('position') == 9 ? "selected" : ""}}>Vị trí số 9</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Đường dẫn <span class="required">*</span></label>
                            <input type="text" class="form-control alias" id="alias" value="{{$news_category->alias ?? old('alias')}}" name="alias" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="description">{!! old('description') !!}</textarea>
                        </div>

                    </div>
                    <div class="card-box">
                        <div class="test-seo">
                            <div class="url-seo font-weight-bold mb-1">
                                <span class="alias-seo" id="alias_seo">{{url('url-seo.html')}}</span>
                            </div>
                            <div class="mb-1">
                                <a href="javascript:void(0)" class="title-seo font-weight-bold font-15">Tiêu đề bài viết</a>
                            </div>
                            <div class="description-seo">
                                <strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
                            </div>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="form-group">
                            <label class="font-weight-bold">Title seo</label>
                            <p>* Ghi chú: Giới hạn tối đa 65 ký tự</p>

                            <input type="text" maxlength="70" value="{{old('title_seo')}}" name="title_seo" class="form-control" id="alloptions" />
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Description seo</label>
                            <p>* Ghi chú: Giới hạn tối đa 158 ký tự</p>
                            <input class="form-control" maxlength="158" value="{{old('description_seo')}}" name="description_seo" id="alloptions">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Keyword seo</label>
                            <p>* Ghi chú: Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                            <input type="text" name="keyword_seo" value="{{old('keyword_seo')}}" class="form-control"  data-role="tagsinput"/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-box">
                        <label class="font-weight-bold">Trạng thái</label>
                        <div class="checkbox checkbox-primary">
                            <input id="checkbox_public" checked type="checkbox" name="public">
                            <label for="checkbox_public">Hiển thị</label>
                        </div>

                        <div class="checkbox checkbox-primary">
                            <input id="checkbox_status" type="checkbox" name="status">
                            <label for="checkbox_status">Nổi bật</label>
                        </div>
                    </div>

                    <div class="card-box position-relative box-action-image">
                        <label class="font-weight-bold">Ảnh đại diện</label>
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

                    <div class="card-box position-relative box-action-background">
                        <label class="font-weight-bold">Ảnh nền</label>
                        <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>

                        <input type="file" name="background" class="filestyle" id="backgroundUpload" data-btnClass="btn-primary">
                        <div class="text-center mt-2 background-holder" id="background-holder">

                        </div>
                        <div class="box-position btn btn-purple waves-effect waves-light text-left show-box-bg">
                            <div class="checkbox checkbox-warning checkbox-circle checkbox-unlink-background">
                                <input id="checkbox_unlink_background" class="unlink-background" type="checkbox" name="unlink_bg">
                                <label for="checkbox_unlink_background" class="mb-0">Xóa ảnh</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 text-center">
                    <div class="card-box">
                        <a href="{{route('admin.news_category.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
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

    <!-- scrollbar init-->
    <script src="{{asset('assets/js/pages/scrollbar.init.js')}}"></script>
@stop

@section('css')
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />

    <!-- Summernote css -->
{{--    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />--}}
@stop