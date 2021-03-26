@extends('Admin.Layout.layout')
@section('title')
    Cập nhật nội dung #{{$news->id}}
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
                            <li class="breadcrumb-item active">Cập nhật nội dung</li>
                            <li class="breadcrumb-item">#{{$news->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật nội dung #{{$news->id}} <small>({{$news->lang}})</small></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <!-- end page title -->
        <form method="post" action="{{route('admin.news.update',$news)}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @method('PATCH')
                <div class="col-lg-8">
                    <div class="card-box">
                        <div class="form-group">
                            <label>Tiêu đề <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{$news->title ?? old('title')}}" id="title" onkeyup="ChangeToSlug();" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="description">{!! $news->description ?? old('description') !!}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Nội dung</label>
                            <textarea class="form-control summerbody" id="summerbody" name="body">{!! $news->content ?? old('body') !!}</textarea>
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
                                <a href="javascript:void(0)" class="title-seo">{{$news->title_seo}}</a>
                            </div>
                            <div class="url-seo">
                                <span class="alias-seo" id="alias_seo">{{route('alias',$news->alias)}}</span>
                            </div>
                            <div class="description-seo">
                                {{$news->description_seo}}
                            </div>
                        </div>

                        <div class="change-seo" id="change-seo">
                            <hr>
                            <div class="form-group">
                                <label>Tiêu đề trang</label>
                                <p class="font-13">* Giới hạn tối đa 70 ký tự</p>
                                <input type="text" maxlength="70" value="{{$news->title_seo ??  old('title_seo')}}" name="title_seo" class="form-control" id="alloptions" />
                            </div>
                            <div class="form-group">
                                <label>Mô tả trang</label>
                                <p class="font-13">* Giới hạn tối đa 320 ký tự</p>
                                <textarea  class="form-control" rows="3" name="description_seo" maxlength="320" id="alloptions">{{$news->description_seo ?? old('description_seo')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Từ khóa</label>
                                <p class="font-13">* Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                                <input type="text" name="keyword_seo" value="{{$news->keyword_seo ?? old('keyword_seo')}}" class="form-control"  data-role="tagsinput"/>
                            </div>
                            <div class="form-group">
                                <label>Đường dẫn <span class="required">*</span></label>
                                <div class="d-flex form-control">
                                    <span>{{route('home')}}/</span><input type="text" class="border-0 alias" id="alias" value="{{$news->alias ?? old('alias')}}" name="alias" required>
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
                            <input id="checkbox_public" {{$news->public == 1 ? "checked" : ""}} type="checkbox" name="public">
                            <label for="checkbox_public">Hiển thị</label>
                        </div>

                        <div class="checkbox">
                            <input id="checkbox_status" {{$news->status == 1 ? "checked" : ""}} type="checkbox" name="status">
                            <label for="checkbox_status">Nổi bật</label>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="form-group">
                            <label>Danh mục chính</label>
                            <select class="form-control" data-toggle="select2" name="category">
                                <option value="0">Chọn danh mục</option>
                                @foreach($category->where('parent_id', 0) as $item )
                                    <option value="{{$item->id}}" {{$news->category_id == $item->id || old('category') == $item->id ? "selected" : ""}} class="font-weight-bold">{{$item->title}}</option>
                                    {{sub_option_category($category,$item->id,$news->id)}}
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-box">
                        <label>Danh mục phụ</label>
                        <select class="form-control select2-multiple" data-toggle="select2" multiple="multiple" name="category_id[]" data-placeholder="Chọn danh mục">
                            @foreach($category->where('parent_id', 0) as $item )
                                <option value="{{$item->id}}" {{selected($item->id,$news->categorys->pluck('category_id')->toArray())}} class="font-weight-bold">{{$item->title}}</option>
                                {{sub_menu_checkbox($category ,$item->id, $news)}}
                            @endforeach
                        </select>
                    </div>

                    <div class="card-box position-relative box-action-image">
                        <label class="font-15">Ảnh đại diện</label>
                        <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif</p>
                        <input type="file" name="image" class="filestyle" id="fileUpload" data-btnClass="btn-primary">
                        <div class="text-center mt-2 image-holder" id="image-holder">
                            @if(file_exists($news->image)) <img src="{{asset($news->image)}}" class="img-responsive img-thumbnail" alt="{{$news->title}}">@endif
                        </div>
                        <div class="box-position btn btn-default waves-effect waves-light text-left @if(!file_exists($news->image)) show-box @endif">

{{--                            <div class="checkbox checkbox-primary checkbox-circle checkbox-unlink-watermark">--}}
{{--                                <input id="checkbox_watermark" class="watermark" type="checkbox" name="watermark">--}}
{{--                                <label for="checkbox_watermark">Gắn watermark</label>--}}
{{--                            </div>--}}

                            <div class="checkbox checkbox-unlink-image">
                                <input id="checkbox_unlink" class="unlink-image" type="checkbox" name="unlink">
                                <label for="checkbox_unlink" class="mb-0">Xóa ảnh</label>
                            </div>

                        </div>
                    </div>
                    <div class="card-box">
                        <label class="font-15 w-100">Ngôn ngữ</label>
                        @php
                            if($news->post_langs){
                                $id = array_unique($news->post_langs->pluck('post_id')->toArray());
                                $posts = \App\Models\News::whereIn('id',$id)->get()->load('language');
                                $langs = \App\Models\Lang::whereNotIn('value',$posts->pluck('lang'))->where('value','<>',$news->lang)->get();
                            }else{
                                $langs = \App\Models\Lang::where('value','<>',$news->lang)->get();
                            }

                        @endphp

                        @foreach($langs as $lang)
                            <a href="{{route('admin.news.add.lang',[$lang->value,$news->id])}}" class="btn btn-primary waves-effect width-md waves-light mb-1"><span class="icon-button"><i class="fe-plus"></i> {{$lang->name}}</a>
                        @endforeach

                        @if($news->post_langs)
                            @foreach($posts as $item)
                                <a href="{{route('admin.news.edit',$item->id)}}" class="btn btn-purple waves-effect waves-light mb-1"><span class="icon-button"><i class="fe-edit-2" aria-hidden="true"></i></span> {{$item->language->name}} #{{$item->id}}</a>
                            @endforeach
                        @endif
                    </div>
                    <div class="card-box tags">
                        <label class="font-15">Tags</label>
                        <p class="font-13">* Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>
                        <input class="form-control" name="tags" data-role="tagsinput" value="{{$news->tags}}" placeholder="add tags">
                    </div>

                </div>

                <div class="col-lg-12">
                        <a href="{{route('admin.news.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary float-right waves-effect width-md waves-light" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
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
