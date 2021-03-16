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
                            <li class="breadcrumb-item"><a href="{{route('admin.gallerys.index')}}">Danh sách bài viết</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Thêm mới</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <form method="post" action="{{route('admin.gallerys.store')}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                <div class="col-lg-8">
                    <div class="card-box option-height-left">
                        <div class="form-group">
                            <label class="font-weight-bold">Tiêu đề <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{old('title')}}" id="title" onkeyup="ChangeToSlug();" name="title" required>
                        </div>


                        <div class="form-group">
                            <label class="font-weight-bold">Đường dẫn <span class="required">*</span></label>
                            <input type="text" class="form-control alias" id="alias" value="{{old('alias')}}" name="alias" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tab hình ảnh</label>
                            <select class="form-control" data-toggle="select2" name="category">
                                <option value="0">-----</option>
                                @foreach($category as $item)
                                    <option value="{{$item->id}}" {{old('category') == $item->value ? "selected" : ""}}> {{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="description">{!! old('description') !!}</textarea>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="form-group">
                            <label class="font-weight-bold">Tags</label>
                            <p>* Ghi chú: Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                            <input type="text" name="tags" value="{{$gallery->tags ?? old('tags')}}" class="form-control"  data-role="tagsinput"/>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="test-seo">
                            <div class="url-seo font-weight-bold mb-1">
                                <span class="alias-seos">{{url('url-seo.html')}}</span>
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

                    <div class="card-box position-relative box-action-image">
                        <label class="font-weight-bold">Ảnh liên quan</label>
                        <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>
                        <input type="file" name="photo[]" multiple class="filestyle" id="fileUploadMultiple" data-btnClass="btn-primary">
                        <div class="card-box show-box autohide-scroll"  style="max-height: 415px; min-height: 415px">
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
                </div>

                <div class="col-lg-12 text-center">
                    <div class="card-box">
                        <a href="{{route('admin.gallerys.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary waves-effect width-md waves-light" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
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

                $('.alias-seos').text(url + $(this).val() + '.html');
            })

        });
    </script>

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

    <!-- Summernote css -->
{{--    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />--}}
@stop
