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
        <form method="post" action="{{route('admin.videos.update',$video)}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @method('PATCH')
                <div class="col-lg-8">
                    <div class="card-box">
                        <div class="form-group">
                            <label class="font-weight-bold">Tiêu đề <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{$video->title ?? old('title')}}" id="title" onkeyup="ChangeToSlug();" name="title" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Đường dẫn <span class="required">*</span></label>
                            <input type="text" class="form-control alias" id="alias" value="{{$video->alias ?? old('alias')}}" name="alias" required>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="note">{!!$video->content ?? old('note') !!}</textarea>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="form-group">
                            <label class="font-weight-bold">Tags</label>
                            <p>* Ghi chú: Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                            <input type="text" name="tags" value="{{$video->tags ?? old('tags')}}" class="form-control"  data-role="tagsinput"/>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="test-seo">
                            <div class="url-seo font-weight-bold mb-1">
                                <span class="alias-seo" id="alias_seo">{{route('alias',$video->alias)}}</span>
                            </div>
                            <div class="mb-1">
                                <a href="{{route('alias',$video->alias)}}" target="_blank" class="title-seo font-weight-bold font-15">{{$video->title_seo}}</a>
                            </div>
                            <div class="description-seo">
                                {{$video->description_seo}}
                            </div>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="form-group">
                            <label class="font-weight-bold">Title seo</label>
                            <p>* Ghi chú: Giới hạn tối đa 65 ký tự</p>

                            <input type="text" maxlength="70" value="{{$video->title_seo ?? old('title_seo')}}" name="title_seo" class="form-control" id="alloptions" />
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Description seo</label>
                            <p>* Ghi chú: Giới hạn tối đa 158 ký tự</p>
                            <input class="form-control" maxlength="158" value="{{$video->description_seo ?? old('description_seo')}}" name="description_seo" id="alloptions">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Keyword seo</label>
                            <p>* Ghi chú: Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                            <input type="text" name="keyword_seo" value="{{$video->keyword_seo ?? old('keyword_seo')}}" class="form-control"  data-role="tagsinput"/>
                        </div>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-box">
                        <label class="font-weight-bold mb-2">Trạng thái</label>
                        <div class="checkbox checkbox-primary checkbox-circle">
                            <input id="checkbox_public" {{$video->public == 1 ? "checked" : ""}} type="checkbox" name="public">
                            <label for="checkbox_public">Hiển thị</label>
                        </div>

                        <div class="checkbox checkbox-primary checkbox-circle">
                            <input id="checkbox_status" {{$video->status == 1 ? "checked" : ""}} type="checkbox" name="status">
                            <label for="checkbox_status">Nổi bật</label>
                        </div>
                    </div>
                    <div class="card-box">
                        <label class="font-weight-bold w-100">Ngôn ngữ</label>
                        @php
                            if($video->post_langs){
                                $id = array_unique($video->post_langs->pluck('post_id')->toArray());
                                $posts = \App\Models\News::whereIn('id',$id)->get()->load('language');
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
                    <div class="card-box">
                        <label class="font-weight-bold">Đường dẫn Video Youtube <span class="required">*</span></label>
                        <p>* Ghi chú: Coppy đường đẫn theo mẫu bên ảnh bên dưới.</p>
                        <p><img src="{{asset('admin/images/note_upload_video.png')}}" class="w-auto"></p>
                        <input class="form-control" value="https://www.youtube.com/watch?v={{$video->video}}" name="video">
                        <div class="mt-3">
                            <iframe width="100%" height="230" src="https://www.youtube.com/embed/{{$video->video}}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>
                    <div class="card-box position-relative box-action-image">
                        <label class="font-weight-bold">Ảnh đại diện</label>
                        <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>

                        <input type="file" name="image" class="filestyle" id="fileUpload" data-btnClass="btn-primary">
                        <div class="text-center mt-2 image-holder" id="image-holder">
                            @if(file_exists($video->image)) <img src="{{asset($video->image)}}" alt="{{$video->title}}" class="img-thumbnail img-responsive"> @endif
                        </div>
                        <div class="box-position btn btn-purple waves-effect waves-light text-left @if(!file_exists($video->image)) show-box @endif">

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
                </div>

                <div class="col-lg-12 text-center">
                    <div class="card-box">
                        <a href="{{route('admin.videos.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary waves-effect width-md waves-light" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </form>
    </div>

@stop

@section('javascript')
    <script type="text/javascript">
        jQuery(document).ready(function($) {

            var alias = $('.alias');
            var title_seo = $('input[name="title_seo"]');
            var description_seo = $('input[name="description_seo"]');

            title_seo.keyup(function() {
                /* Act on the event */
                $('.title-seo').html($(this).val());
                return false;
            });

            description_seo.keyup(function() {
                /* Act on the event */
                $('.description-seo').html($(this).val());
                return false;
            });
            alias.on('keyup change',function(){
                var url = "{{route('home')}}/";
                $('.alias-seo').text(url + $(this).val() + '.html');
            })

        });
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
