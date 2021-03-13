@extends('Admin.Layout.layout')
@section('title')
    Cập nhật nội dung #{{$gallery->id}}
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
                            <li class="breadcrumb-item"><a href="{{route('admin.gallerys.index')}}">Danh sách viết</a></li>
                            <li class="breadcrumb-item active">Cập nhật nội dung</li>
                            <li class="breadcrumb-item active">#{{$gallery->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật nội dung #{{$gallery->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->



        <form method="post" action="{{route('admin.gallerys.update',$gallery)}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @method('PATCH')
                <div class="col-lg-8">
                    <div class="card-box option-height-left">
                        <div class="form-group">
                            <label class="font-weight-bold">Tiêu đề <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{$gallery->title ?? old('title')}}" id="title" onkeyup="ChangeToSlug();" name="title" required>
                        </div>


                        <div class="form-group">
                            <label class="font-weight-bold">Đường dẫn <span class="required">*</span></label>
                            <input type="text" class="form-control alias" id="alias" value="{{$gallery->alias ??  old('alias')}}" name="alias" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Tab hình ảnh</label>
                            <select class="form-control" data-toggle="select2" name="category">
                                <option value="0">-----</option>
                                @foreach($category as $item)
                                    <option value="{{$item->id}}" {{$gallery->category_id == $item->id || old('category') == $item->id ? "selected" : ""}}> {{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="description">{!! $gallery->description ?? old('description') !!}</textarea>
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
                                <span class="alias-seos">{{url($gallery->alias)}}</span>
                            </div>
                            <div class="mb-1">
                                <a href="javascript:void(0)" class="title-seo font-weight-bold font-15">{{$gallery->title_seo ?? $gallery->title}}</a>
                            </div>
                            <div class="description-seo">
                                {{$gallery->description_seo ?? $gallery->title}}
                            </div>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="form-group">
                            <label class="font-weight-bold">Title seo</label>
                            <p>* Ghi chú: Giới hạn tối đa 65 ký tự</p>

                            <input type="text" maxlength="70" value="{{$gallery->title_seo ?? old('title_seo')}}" name="title_seo" class="form-control" id="alloptions" />
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Description seo</label>
                            <p>* Ghi chú: Giới hạn tối đa 158 ký tự</p>
                            <input class="form-control" maxlength="158" value="{{$gallery->description_seo ?? old('description_seo')}}" name="description_seo" id="alloptions">
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Keyword seo</label>
                            <p>* Ghi chú: Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                            <input type="text" name="keyword_seo" value="{{$gallery->keyword_seo ?? old('keyword_seo')}}" class="form-control"  data-role="tagsinput"/>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-box">
                        <label class="font-weight-bold w-100">Ngôn ngữ</label>
                        @php
                            if($gallery->post_langs){
                                $id = array_unique($gallery->post_langs->pluck('post_id')->toArray());
                                $gallerys = \App\Models\Gallerys::whereIn('id',$id)->get()->load('language');
                                $langs = \App\Models\Lang::whereNotIn('value',$gallerys->pluck('lang'))->where('value','<>',$gallery->lang)->get();
                            }else{
                                $langs = \App\Models\Lang::where('value','<>',$gallery->lang)->get();
                            }

                        @endphp

                        @foreach($langs as $lang)
                            <a href="{{route('admin.gallerys.add.lang',[$lang->value,$gallery->id])}}" class="btn btn-primary waves-effect width-md waves-light mb-1"><span class="icon-button"><i class="fe-plus"></i> {{$lang->name}}</a>
                        @endforeach

                        @if($gallery->post_langs)
                            @foreach($gallerys as $item)
                                <a href="{{route('admin.gallerys.edit',$item->id)}}" class="btn btn-purple waves-effect waves-light mb-1"><span class="icon-button"><i class="fe-edit-2" aria-hidden="true"></i></span> {{$item->language->name}} #{{$item->id}}</a>
                            @endforeach
                        @endif

                    </div>
                    <div class="card-box box-action-image">
                        <label class="font-weight-bold">Ảnh đại diện</label>
                        <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>
                        <input type="file" name="image" class="filestyle" id="fileUpload" data-btnClass="btn-primary">
                    </div>
                    <div class="card-box autohide-scroll @if(!file_exists($gallery->image)) show-one-box @endif position-relative"  style="max-height: 300px;">
                        <div class="grid-gallery">
                            <section class="grid-wrap">
                                <ul class="grid" id="list-item">
                                    <span class="image-holder" id="image-holder">
                                        @if(file_exists($gallery->image)) <img src="{{asset($gallery->image)}}"> @endif
                                    </span>
                                </ul>
                            </section><!-- // grid-wrap -->
                        </div><!-- // grid-gallery -->
                        <div class="box-position btn btn-purple waves-effect waves-light text-left @if(!file_exists($gallery->image)) show-box @endif">

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
                        <label class="font-weight-bold">Ảnh liên quan</label>
                        <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>
                        <input type="file" name="photo[]" multiple class="filestyle" id="fileUploadMultiple" data-btnClass="btn-primary">

                    </div>
                    <div class="card-box @if(!file_exists($gallery->image)) show-multiple-box @endif autohide-scroll"  style="max-height: 250px;">
                        <div id="grid-gallery" class="grid-gallery">
                            <section class="grid-wrap">
                                <ul class="grid" id="list-item">
                                    <span class="image-holder-multiple" id="image-holder-multiple">

                                    </span>
                                </ul>
                            </section><!-- // grid-wrap -->
                        </div><!-- // grid-gallery -->
                    </div>
                    @if($photo->count())
                    <div class="card-box">
                        <label class="font-weight-bold">Danh sách ảnh liên quan</label>
                        <div class="row autohide-scroll" style="max-height: 280px;">
                            @foreach($photo as $item)
                                <div class="col-xl-6 col-lg-4 col-sm-6">
                                    <div class="file-man-box rounded mb-3 mt-1">

                                        <a class="file-close" href="{{route('admin.media.del',$item->id)}}" onclick="return confirm('Bạn có chắc muốn xóa?');" ><i class="mdi mdi-close-circle"></i></a>

                                        <div class="file-img-box">
                                            <img src="{{asset($item->image)}}" class="img-thumbnail img-responsive" alt="{{$item->title}}">
                                        </div>
                                        <a href="{{route('admin.media.edit',$item)}}" class="file-download btn text-purple"><i class="fe-edit-2"></i> </a>
                                        <div class="file-man-title">
                                            <h5 class="mb-0 text-overflow">{{$item->updated_at->diffForHumans()}}</h5>
                                            <p class="mb-0"><small>{{$item->name ?? "Nomal"}}</small></p>
                                        </div>
                                        <div class="file-sort">
                                            <input type="number" name="sort" class="form-control font-weight-bold input-sort" data-id="{{$item->id}}" value="{{$item->sort}}">
                                            <span id="change-sort-success_{{$item->id}}" class="change-sort"></span>
                                        </div>
                                        <div class="file-public">
                                            <div class="checkbox checkbox-primary checkbox-circle" >
                                                <input id="checkbox_public_{{$item->id}}"  {{$item->public == 1 ? "checked" : ''}} type="checkbox" name="public">
                                                <label for="checkbox_public_{{$item->id}}" class="media_public"  data-id="{{$item->id}}"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-lg-12 text-center">
                    <div class="card-box">
                        <a href="{{route('admin.gallerys.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary waves-effect width-md waves-light" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </form>

    </div>
    <style>
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        .file-man-box .file-img-box {
            height: 120px;
            line-height: 120px;
        }
        .file-man-box .file-img-box img{
            max-height: 120px;
            height: auto;
        }
        .file-man-box .file-close button {
            background: none;
            border:none;
            color: #f96a74;
        }
        .file-man-box .file-sort {
            position: absolute;
            line-height: 24px;
            font-size: 24px;
            right: 60px;
            bottom: 25px;
            visibility: hidden;
            width: 70px;
        }
        .file-man-box .file-public {
            position: absolute;
            left: 15px;
            top: 5px;
            visibility: hidden;

        }
        .file-man-box:hover .file-sort, .file-man-box:hover .file-public {
            visibility: visible;
        }
        .change-sort {
            position: absolute;
            top: 17%;
            right: 7%;
            font-size: 13px;
        }
        .file-man-box .file-download {
            font-size: 25px;
        }
    </style>
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
    <script type="text/javascript">
        $(document).ready(function(){
            $('input[name=sort]').keyup(function(){
                url = "{{route('admin.ajax.media.sort')}}";
                id = $(this).attr('data-id');
                num = $(this).val();
                _token = $('input[name=_token]').val();
                $.ajax({
                    url:url,
                    type:'GET',
                    cache:false,
                    data:{'_token':_token,'id':id,'num':num},
                    success:function(data){
                        $('#change-sort-success_'+id+'').html('<i class="fa fa-check text-success" aria-hidden="true"></i>');
                        $('#change-sort-success_'+id+'').fadeIn(1000);
                        $('#change-sort-success_'+id+'').fadeOut(5000);
                    }
                });
            });

            $('.media_public').click(function(){
                url = "{{route('admin.ajax.media.public')}}";
                id = $(this).attr('data-id');
                _token = $('input[name=_token]').val();
                $.ajax({
                    url:url,
                    type:'GET',
                    cache:false,
                    data:{'_token':_token,'id':id},
                    success:function(data){
                        console.log(data);
                    }
                });
            });
        })
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
{{--    <script src="{{asset('admin/assets/libs/jquery-mockjax/jquery.mockjax.min.js')}}"></script>--}}
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
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />

@stop
