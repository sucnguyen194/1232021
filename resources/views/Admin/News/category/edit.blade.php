@extends('Admin.Layout.layout')
@section('title')
    Cập nhật nội dung #{{$news_category->id}}
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
                            <li class="breadcrumb-item">Cập nhật nội dung</li>
                            <li class="breadcrumb-item">#{{$news_category->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật nội dung #{{$news_category->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>
    <div class="container">
        <form method="post" action="{{route('admin.news_category.update',$news_category)}}" enctype="multipart/form-data">
            <div class="row">
                @method('PATCH')
                @csrf
                <div class="col-lg-8">
                    <div class="card-box">
                        <div class="form-group">
                            <label>Tiêu đề <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{$news_category->title ?? old('title')}}" id="title" onkeyup="ChangeToSlug();" name="title" required>
                        </div>

                        <div class="form-group">
                            <label>Danh mục cha</label>
                            <select class="form-control" data-toggle="select2" name="category">
                                <option value="0">Chọn danh mục</option>
                                @foreach($categorys->where('parent_id', 0) as $item )
                                    <option value="{{$item->id}}" {{$news_category->parent_id == $item->id || old('category') == $item->id ? "selected" : ""}} class="font-weight-bold">{{$item->title}}</option>
                                    {{sub_option_category($categorys,$item->id,$news_category->parent_id)}}
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Vị trí</label>
                            <select class="form-control" data-toggle="select2" name="position">
                                <option value="0">Chọn ví trí</option>
                                <option value="1" {{$news_category->position == 1 || old('position') == 1 ? "selected" : ""}}>Vị trí số 1</option>
                                <option value="2" {{$news_category->position == 2 || old('position') == 2 ? "selected" : ""}}>Vị trí số 2</option>
                                <option value="3" {{$news_category->position == 3 || old('position') == 3 ? "selected" : ""}}>Vị trí số 3</option>
                                <option value="4" {{$news_category->position == 4 || old('position') == 4 ? "selected" : ""}}>Vị trí số 4</option>
                                <option value="5" {{$news_category->position == 5 || old('position') == 5 ? "selected" : ""}}>Vị trí số 5</option>
                                <option value="6" {{$news_category->position == 6 || old('position') == 6 ? "selected" : ""}}>Vị trí số 6</option>
                                <option value="7" {{$news_category->position == 7 || old('position') == 7 ? "selected" : ""}}>Vị trí số 7</option>
                                <option value="8" {{$news_category->position == 8 || old('position') == 8 ? "selected" : ""}}>Vị trí số 8</option>
                                <option value="9" {{$news_category->position == 9 || old('position') == 9 ? "selected" : ""}}>Vị trí số 9</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="description">{!! $news_category->description ?? old('description') !!}</textarea>
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
                                <a href="javascript:void(0)" class="title-seo">{{$news_category->title_seo}}</a>
                            </div>
                            <div class="url-seo">
                                <span class="alias-seo" id="alias_seo">{{route('alias',$news_category->alias)}}</span>
                            </div>
                            <div class="description-seo">
                                {{$news_category->description_seo}}
                            </div>
                        </div>

                        <div class="change-seo" id="change-seo">
                            <hr>
                            <div class="form-group">
                                <label>Tiêu đề trang</label>
                                <p class="font-13">* Ghi chú: Giới hạn tối đa 70 ký tự</p>
                                <input type="text" maxlength="70" value="{{$news_category->title_seo ??  old('title_seo')}}" name="title_seo" class="form-control" id="alloptions" />
                            </div>
                            <div class="form-group">
                                <label>Mô tả trang</label>
                                <p class="font-13">* Ghi chú: Giới hạn tối đa 320 ký tự</p>
                                <textarea  class="form-control" rows="3" name="description_seo" maxlength="320" id="alloptions">{{$news_category->description_seo ?? old('description_seo')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Từ khóa</label>
                                <p class="font-13">* Ghi chú: Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                                <input type="text" name="keyword_seo" value="{{$news_category->keyword_seo ?? old('keyword_seo')}}" class="form-control"  data-role="tagsinput"/>
                            </div>
                            <div class="form-group">
                                <label>Đường dẫn <span class="required">*</span></label>
                                <div class="d-flex form-control">
                                    <span>{{route('home')}}/</span><input type="text" class="border-0 alias" id="alias" value="{{$news_category->alias ?? old('alias')}}" name="alias" required>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="card-box">
                        <label class="mb-0">Trạng thái</label>
                        <hr>
                        <div class="checkbox">
                            <input id="checkbox_public" id="public" {{$news_category->public == 1 ? "checked" : ""}} type="checkbox" name="public">
                            <label for="checkbox_public">Hiển thị</label>
                        </div>

                        <div class="checkbox">
                            <input id="checkbox_status" id="status" {{$news_category->status == 1 ? "checked" : ""}} type="checkbox" name="status">
                            <label for="checkbox_status">Nổi bật</label>
                        </div>
                    </div>

                    <div class="card-box position-relative">
                        <label>Ảnh đại diện</label>
                        <p class="font-13">* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>

                        <input type="file" name="image" class="filestyle" id="fileUpload" data-btnClass="btn-primary">
                        <div class="text-center mt-2 image-holder" id="image-holder">
                            @if(file_exists($news_category->image)) <img src="{{asset($news_category->image)}}" class="img-responsive img-thumbnail" alt="{{$news_category->title}}">@endif
                        </div>
                        <div class="box-position btn btn-purple waves-effect waves-light text-left {{!file_exists($news_category->image) ? "show-box" : ""}}">
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

                    <div class="card-box position-relative">
                        <label>Ảnh nền</label>
                        <p class="font-13">* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>

                        <input type="file" name="background" class="filestyle" id="backgroundUpload" data-btnClass="btn-primary">
                        <div class="text-center mt-2 background-holder" id="background-holder">
                            @if(file_exists($news_category->background)) <img src="{{asset($news_category->background)}}" class="img-responsive img-thumbnail" alt="{{$news_category->title}}">@endif
                        </div>
                        <div class="box-position btn btn-purple waves-effect waves-light text-left {{!file_exists($news_category->background) ? "show-box-bg" :"" }}">
                            <div class="checkbox checkbox-warning checkbox-circle checkbox-unlink-background">
                                <input id="checkbox_unlink_background" class="unlink-background" type="checkbox" name="unlink_bg">
                                <label for="checkbox_unlink_background" class="mb-0">Xóa ảnh</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-box">
                        <label class="w-100">Ngôn ngữ</label>
                        @php
                            if($news_category->post_langs){
                                $id = array_unique($news_category->post_langs->pluck('post_id')->toArray());
                                $category = \App\Models\NewsCategory::whereIn('id',$id)->get()->load('language');
                                $langs = \App\Models\Lang::whereNotIn('value',$category->pluck('lang'))->where('value','<>',$news_category->lang)->get();
                            }else{
                                $langs = \App\Models\Lang::where('value','<>',$news_category->lang)->get();
                            }

                        @endphp

                        @foreach($langs as $lang)
                            <a href="{{route('admin.news.category.add.lang',[$lang->value,$news_category->id])}}" class="btn btn-primary waves-effect width-md waves-light mb-1"><span class="icon-button"><i class="fe-plus"></i> {{$lang->name}}</a>
                        @endforeach

                        @if($news_category->post_langs)
                            @foreach($category as $item)
                                <a href="{{route('admin.news_category.edit',$item->id)}}" class="btn btn-purple waves-effect waves-light mb-1"><span class="icon-button"><i class="fe-edit-2" aria-hidden="true"></i></span> {{$item->language->name}} #{{$item->id}}</a>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-lg-12">
                    <a href="{{route('admin.news_category.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
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
