@extends('Admin.Layout.layout')
@section('title')
    Cập nhật nội dung #{{$post->id}}
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
                            <li class="breadcrumb-item"><a href="{{route('admin.posts.index')}}">Danh sách bài viết</a></li>
                            <li class="breadcrumb-item active">Cập nhật nội dung</li>
                            <li class="breadcrumb-item">#{{$post->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật nội dung #{{$post->id}} <small>({{$post->lang}})</small></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <!-- end page title -->
        <form method="post" action="{{route('admin.posts.update',$post)}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @method('PATCH')
                <div class="col-lg-8">
                    <div class="card-box">
                        <div class="form-group">
                            <label>Tiêu đề <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{$post->title}}" id="title" onkeyup="ChangeToSlug();" name="data[title]" >
                        </div>
                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="data[description]">{!! $post->description!!}</textarea>
                        </div>

                        <div class="form-group mb-0">
                            <label>Nội dung</label>
                            <textarea class="form-control summerbody" id="summerbody" name="data[content]">{!! $post->content !!}</textarea>
                        </div>

                    </div>
                    <div class="card-box">
                        <div class="d-flex mb-2">
                            <label class="font-weight-bold">Tối ưu SEO</label>
                            <a href="javascript:void(0)" onclick="changeSeo()" class="edit-seo">Chỉnh sửa SEO</a>
                        </div>

                        <p class="font-13">Thiết lập các thẻ mô tả giúp khách hàng dễ dàng tìm thấy trang trên công cụ tìm kiếm như Google.</p>
                        <div class="test-seo">
                            <div class="mb-1">
                                <a href="javascript:void(0)" class="title-seo">{{$post->title_seo}}</a>
                            </div>
                            <div class="url-seo">
                                <span class="alias-seo" id="alias_seo">{{route('alias',$post->alias)}}</span>
                            </div>
                            <div class="description-seo">
                                {{$post->description_seo}}
                            </div>
                        </div>

                        <div class="change-seo" id="change-seo">
                            <hr>
                            <div class="form-group">
                                <label>Tiêu đề trang</label>
                                <p class="font-13">* Giới hạn tối đa 70 ký tự</p>
                                <input type="text" maxlength="70" value="{{$post->title_seo}}" name="data[title_seo]" class="form-control" id="alloptions" />
                            </div>
                            <div class="form-group">
                                <label>Mô tả trang</label>
                                <p class="font-13">* Giới hạn tối đa 320 ký tự</p>
                                <textarea  class="form-control" rows="3" name="data[description_seo]" maxlength="320" id="alloptions">{{$post->description_seo}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Từ khóa</label>
                                <p class="font-13">* Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                                <input type="text" name="data[keyword_seo]" value="{{$post->keyword_seo}}" class="form-control"  data-role="tagsinput"/>
                            </div>
                            <div class="form-group mb-0">
                                <label>Đường dẫn <span class="required">*</span></label>
                                <div class="d-flex form-control">
                                    <span>{{route('home')}}/</span><input type="text" class="border-0 alias" id="alias" value="{{$post->alias}}" name="data[alias]" required>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="card-box">
                        <label class="font-15 mb-0">Trạng thái</label>
                        <hr>
                        <div class="checkbox">
                            <input id="checkbox_public" {{$post->public == 1 ? "checked" : ""}} type="checkbox" name="public">
                            <label for="checkbox_public">Hiển thị</label>
                        </div>

                        <div class="checkbox">
                            <input id="checkbox_status" {{$post->status == 1 ? "checked" : ""}} type="checkbox" name="status">
                            <label for="checkbox_status" class="mb-0">Nổi bật</label>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="form-group">
                            <label>Danh mục chính</label>
                            <select class="form-control" data-toggle="select2" name="data[category_id]">
                                <option value="0">Chọn danh mục</option>
                                @foreach($categories->where('parent_id', 0) as $item )
                                    <option value="{{$item->id}}" {{$post->category_id == $item->id ? "selected" : ""}} class="font-weight-bold">{{$item->name}}</option>
                                    {{sub_option_category($categories,$item->id,$post->category_id)}}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-box">
                        <label>Danh mục phụ</label>

                        <select class="form-control select2-multiple" data-toggle="select2" multiple="multiple" name="category_id[]" data-placeholder="Chọn danh mục phụ">
                            @foreach($categories->where('parent_id', 0) as $item )
                                <option value="{{$item->id}}" {{selected($item->id, $post->categories->pluck('id')->toArray())}} class="font-weight-bold">{{$item->name}}</option>
                                {{sub_option_category($categories ,$item->id, $post)}}
                            @endforeach
                        </select>
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
                            @if(!file_exists($post->image))
                                <div class="dz-message text-center needsclick mb-2" id="remove-label">
                                    <label for="fileUpload" class="w-100 mb-0">
                                        <div class="icon-dropzone pt-2">
                                            <i class="h1 text-muted dripicons-cloud-upload"></i>
                                        </div>
                                        <span class="text-muted font-13">Sử dụng nút <strong>Chọn ảnh</strong> để thêm ảnh</span>
                                    </label>
                                </div>
                            @endif
                            <div class="{{!file_exists($post->image) ? "show-box" : ""}} image-holder pl-0 mb-0 w-100">
                                @if(file_exists($post->image)) <img src="{{asset($post->image)}}" alt="{{$post->name}}"> @endif
                            </div>
                            <div class="box-position btn btn-default waves-effect waves-light text-left @if(!file_exists($post->image)) show-box @endif">
                                <div class="checkbox checkbox-unlink-image">
                                    <input id="checkbox_unlink" class="unlink-image" type="checkbox" name="unlink">
                                    <label for="checkbox_unlink" class="mb-0">Xóa ảnh</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-box">
                        <label class="font-15 w-100">Ngôn ngữ</label>
                        @foreach($langs as $lang)
                            <a href="{{route('admin.posts.lang',[$lang->value,$post->id])}}" class="btn btn-primary waves-effect width-md waves-light mb-1"><span class="icon-button"><i class="fe-plus"></i> {{$lang->name}}</a>
                        @endforeach

                        @if($post->postLangsBefore)
                            @foreach($posts as $item)
                                <a href="{{route('admin.posts.edit',$item->id)}}" class="btn btn-purple waves-effect waves-light mb-1"><span class="icon-button"><i class="fe-edit-2" aria-hidden="true"></i></span> {{$item->language->name }} #{{$item->id}}</a>
                            @endforeach
                        @endif
                    </div>
                    <div class="card-box tags">
                        <label class="font-15">Tags</label>
                        <p class="font-13">* Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>
                        <input class="form-control" name="data[tags]" data-role="tagsinput" value="{{$post->tags}}" placeholder="add tags">
                    </div>

                </div>

                <div class="col-lg-12">
                        <a href="{{route('admin.posts.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary float-right waves-effect width-md waves-light" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                </div>
            </div>
            <!-- end row -->
        </form>
    </div>
    <script>
        CKEDITOR.replace( 'summernote' ,{
            height:150
        });
        CKEDITOR.replace( 'summerbody' ,{
            height:300
        });
    </script>
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
