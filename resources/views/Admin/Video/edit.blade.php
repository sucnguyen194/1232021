@extends('Admin.Layout.layout')
@section('title')
    Sửa video #{{$video->id}}
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
                            <li class="breadcrumb-item"><a href="{{route('admin.videos.index')}}">Danh sách video</a></li>
                            <li class="breadcrumb-item active">Sửa video</li>
                            <li class="breadcrumb-item">ID #{{$video->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Sửa video #{{$video->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>
    <div class="container">
        <form method="post" action="{{route('admin.videos.update',$video)}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @method('PATCH')
                <div class="col-lg-8">
                    <div class="card-box">
                        <div class="form-group">
                            <label>Tiêu đề <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{$video->title ?? old('title')}}" id="title" onkeyup="ChangeToSlug();" name="title" required>
                        </div>

                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="note">{!!$video->content ?? old('note') !!}</textarea>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="form-group">
                            <label>Tags</label>
                            <p class="font-13">* Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                            <input type="text" name="tags" value="{{$video->tags ?? old('tags')}}" class="form-control"  data-role="tagsinput"/>
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
                                <a href="javascript:void(0)" class="title-seo">{{$video->title_seo}}</a>
                            </div>
                            <div class="url-seo">
                                <span class="alias-seo" id="alias_seo">{{route('alias',$video->alias)}}</span>
                            </div>
                            <div class="description-seo">
                                {{$video->description_seo}}
                            </div>
                        </div>

                        <div class="change-seo" id="change-seo">
                            <hr>
                            <div class="form-group">
                                <label>Tiêu đề trang</label>
                                <p class="font-13">* Giới hạn tối đa 70 ký tự</p>
                                <input type="text" maxlength="70" value="{{$video->title_seo ??  old('title_seo')}}" name="title_seo" class="form-control" id="alloptions" />
                            </div>
                            <div class="form-group">
                                <label>Mô tả trang</label>
                                <p class="font-13">* Giới hạn tối đa 320 ký tự</p>
                                <textarea  class="form-control" rows="3" name="description_seo" maxlength="320" id="alloptions">{{$video->description_seo ?? old('description_seo')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Từ khóa</label>
                                <p class="font-13">* Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                                <input type="text" name="keyword_seo" value="{{$video->keyword_seo ?? old('keyword_seo')}}" class="form-control"  data-role="tagsinput"/>
                            </div>
                            <div class="form-group">
                                <label>Đường dẫn <span class="required">*</span></label>
                                <div class="d-flex form-control">
                                    <span>{{route('home')}}/</span><input type="text" class="border-0 alias" id="alias" value="{{$video->alias ?? old('alias')}}" name="alias" required>
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
                            <input id="checkbox_public" {{$video->public == 1 ? "checked" : ""}} type="checkbox" name="public">
                            <label for="checkbox_public">Hiển thị</label>
                        </div>

                        <div class="checkbox">
                            <input id="checkbox_status" {{$video->status == 1 ? "checked" : ""}} type="checkbox" name="status">
                            <label for="checkbox_status">Nổi bật</label>
                        </div>
                    </div>

                    <div class="card-box">
                        <label>Đường dẫn Video Youtube <span class="required">*</span></label>
                        <p class="font-13">* Coppy đường đẫn theo mẫu bên ảnh bên dưới.</p>
                        <p><img src="{{asset('admin/images/note_upload_video.png')}}" class="w-auto"></p>
                        <input class="form-control" value="https://www.youtube.com/watch?v={{$video->video}}" name="video">
                        <div class="mt-3">
                            <iframe width="100%" height="230" src="https://www.youtube.com/embed/{{$video->video}}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="card-box position-relative box-action-image">
                        <label>Ảnh đại diện</label>
                        <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif</p>

                        <input type="file" name="image" class="filestyle" id="fileUpload" data-btnClass="btn-primary">
                        <div class="text-center mt-2 image-holder" id="image-holder">
                            @if(file_exists($video->image)) <img src="{{asset($video->image)}}" alt="{{$video->title}}" class="img-thumbnail img-responsive"> @endif
                        </div>
                        <div class="box-position btn btn-purple waves-effect waves-light text-left @if(!file_exists($video->image)) show-box @endif">

                            <div class="checkbox checkbox-unlink-watermark">
                                <input id="checkbox_watermark" class="watermark" type="checkbox" name="watermark">
                                <label for="checkbox_watermark">Gắn watermark</label>
                            </div>

                            <div class="checkbox checkbox-unlink-image">
                                <input id="checkbox_unlink" class="unlink-image" type="checkbox" name="unlink">
                                <label for="checkbox_unlink" class="mb-0">Xóa ảnh</label>
                            </div>

                        </div>
                    </div>
                    <div class="card-box">
                        <label class="w-100">Ngôn ngữ</label>
                        @php
                            if($video->post_langs){
                                $id = array_unique($video->post_langs->pluck('post_id')->toArray());
                                $posts = \App\Models\Videos::whereIn('id',$id)->get()->load('language');
                                $langs = \App\Models\Lang::whereNotIn('value',$posts->pluck('lang'))->where('value','<>',$video->lang)->get();
                            }else{
                                $langs = \App\Models\Lang::where('value','<>',$video->lang)->get();
                            }

                        @endphp

                        @foreach($langs as $lang)
                            <a href="{{route('admin.videos.add.lang',[$lang->value,$video->id])}}" class="btn btn-primary waves-effect width-md waves-light mb-1"><span class="icon-button"><i class="fe-plus"></i> {{$lang->name}}</a>
                        @endforeach

                        @if($video->post_langs)
                            @foreach($posts as $item)
                                <a href="{{route('admin.videos.edit',$item->id)}}" class="btn btn-purple waves-effect waves-light mb-1"><span class="icon-button"><i class="fe-edit-2" aria-hidden="true"></i></span> {{$item->language->name}} #{{$item->id}}</a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-lg-12">
                    <a href="{{route('admin.videos.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
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
