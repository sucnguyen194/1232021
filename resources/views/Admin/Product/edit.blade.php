@extends('Admin.Layout.layout')
@section('title')
    Cập nhật nội dung #{{$product->id}}
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
                            <li class="breadcrumb-item"><a href="{{route('admin.products.index')}}">Danh sách sản phẩm</a></li>
                            <li class="breadcrumb-item active">Cập nhật nội dung #{{$product->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật nội dung #{{$product->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>
    <div class="container">
        <form method="post" action="{{route('admin.products.update',$product)}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @method('PUT')
                <div class="col-lg-8">
                    <div class="card-box">
                        <div class="form-group">
                            <label>Tên sản phẩm <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{$product->name ?? old('data.name')}}" id="title" onkeyup="ChangeToSlug();" name="data[name]" required>
                        </div>
                        <div class="form-group">
                            <label>Danh mục chính</label>
                            <select class="form-control" data-toggle="select2" name="data[category_id]">
                                <option value="0">Chọn danh mục</option>
                                @foreach($category->where('parent_id', 0) as $item )
                                    <option value="{{$item->id}}" {{$product->category_id == $item->id ? "selected" : ""}} class="font-weight-bold">{{$item->name}}</option>
                                    {{sub_option_category($category ,$item->id,$product->id)}}
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Danh mục phụ</label>
                            <p class="font-13">* Chọn được nhiều danh mục</p>
                            <select class="form-control select2-multiple" data-toggle="select2" multiple="multiple" name="category_id[]" data-placeholder="Chọn danh mục">
                                @foreach($category->where('parent_id', 0) as $item )
                                    <option value="{{$item->id}}" {{selected($item->id,$product->categorys->pluck('category_id')->toArray())}} class="font-weight-bold">{{$item->name}}</option>
                                    {{sub_menu_checkbox($category ,$item->id,$product)}}
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-12">
                                <div class="form-group">
                                    <label>Mã sản phẩm</label>
                                    <input type="text" class="form-control" value="{{$product->code ?? old('data.code')}}" id="code" name="data[code]">
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="form-group">
                                    <label>Giá bán</label>
                                    <input type="text" class="form-control" value="{{$product->price ?? old('data.price')}}" id="price" name="data[price]">
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="form-group">
                                    <label>Giá khuyến mại</label>
                                    <input type="text" class="form-control" value="{{$product->price_sale ?? old('data.price_sale')}}" id="price_sale" name="data[price_sale]">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="data[description]">{!! $product->description ?? old('data.description') !!}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Chi tiết</label>
                            <textarea class="form-control summerbody" id="summerbody" name="data[content]">{!! $product->content ?? old('data.content') !!}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Thuộc tính sản phẩm</label>
                            <table data-dynamicrows class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Tên thuộc tính</th>
                                    <th>Giá trị</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                                <tbody>

                                @if($product->option)

                                    @foreach($product->option as $key => $item)
                                        <tr>
                                            <td><input type="text" name="fields[{{$key}}][name]" value="{{$item['name']}}" class="form-control"></td>
                                            <td><input type="text" name="fields[{{$key}}][value]" value="{{$item['value']}}" class="form-control"></td>
                                            <td>
                                                <i class="fas fa-minus" data-remove></i>
                                                <i class="fas fa-arrows-alt" data-move></i>
                                                <i class="fas fa-plus" data-add></i>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td><input type="text" name="fields[0][name]"  class="form-control"></td>
                                        <td><input type="text" name="fields[0][value]" class="form-control"></td>
                                        <td>
                                            <i class="fas fa-minus" data-remove></i>
                                            <i class="fas fa-arrows-alt" data-move></i>
                                            <i class="fas fa-plus" data-add></i>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="card-box">
                        <div class="d-flex mb-2">
                            <label class="font-weight-bold">Tối ưu SEO</label>
                            <a href="javascript:void(0)" onclick="changeSeo()" class="edit-seo">Chỉnh sửa SEO</a>
                        </div>

                        <p class="font-13">Thiết lập các thẻ mô tả giúp khách hàng dễ dàng tìm thấy trang trên công cụ tìm kiếm như Google.</p>

                        <div class="test-seo">
                            <div class="">
                                <a href="javascript:void(0)" class="title-seo">{{$product->title_seo}}</a>
                            </div>
                            <div class="url-seo font-weight-bold mb-1">
                                <span class="alias-seo" id="alias_seo">{{route('alias', $product->alias)}}</span>
                            </div>
                            <div class="description-seo">{!! $product->description_seo !!}</div>
                        </div>

                        <div class="change-seo" id="change-seo">
                            <hr>
                            <div class="form-group">
                                <label>Tiêu đề trang</label>
                                <p class="font-13">* Ghi chú: Giới hạn tối đa 70 ký tự</p>
                                <input type="text" maxlength="70" value="{{$product->title_seo ?? old('data.title_seo')}}" name="data[title_seo]" class="form-control" id="alloptions" />
                            </div>
                            <div class="form-group">
                                <label>Mô tả trang</label>
                                <p class="font-13">* Ghi chú: Giới hạn tối đa 320 ký tự</p>
                                <textarea  class="form-control" rows="3" name="data[description_seo]" maxlength="320" id="alloptions">{{$product->description_seo ?? old('data.description_seo')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Từ khóa</label>
                                <p class="font-13">* Ghi chú: Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                                <input type="text" name="data[keyword_seo]" value="{{$product->keyword_seo ?? old('data.keyword_seo')}}" class="form-control"  data-role="tagsinput"/>
                            </div>
                            <div class="form-group">
                                <label>Đường dẫn <span class="required">*</span></label>
                                <div class="d-flex form-control">
                                    <span>{{route('home')}}/</span><input type="text" class="border-0 alias" id="alias" value="{{$product->alias ?? old('data.alias')}}" name="data[alias]" required>
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
                            <input id="checkbox_public" {{$product->public == 1 ? "checked" : ""}} type="checkbox" name="data[public]" value="1">
                            <label for="checkbox_public">Hiển thị</label>
                        </div>

                        <div class="checkbox">
                            <input id="checkbox_status" type="checkbox" {{$product->public == 1 ? "checked" : ""}} name="data[status]" value="1">
                            <label for="checkbox_status">Nổi bật</label>
                        </div>
                    </div>

                    <div class="card-box position-relative box-action-image">
                        <label class="font-weight-bold">Ảnh đại diện</label>
                        <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif</p>

                        <input type="file" name="image" class="filestyle" id="fileUpload" data-btnClass="btn-primary">
                        <div class="text-center image-holder" id="image-holder">
                            @if(file_exists($product->image)) <img src="{{asset($product->image)}}" class="img-fluid mt-2 img-responsive" height="120"> @endif
                        </div>
                        <div class="box-position btn btn-default waves-effect waves-light text-left @if(!file_exists($product->image)) show-box @endif">
{{--                            <div class="checkbox checkbox-warning checkbox-circle checkbox-unlink-watermark">--}}
{{--                                <input id="checkbox_watermark" class="watermark" type="checkbox" name="watermark">--}}
{{--                                <label for="checkbox_watermark">Gắn watermark</label>--}}
{{--                            </div>--}}
                            <div class="checkbox checkbox-unlink-image">
                                <input id="checkbox_unlink" class="unlink-image" type="checkbox" name="unlink">
                                <label for="checkbox_unlink" class="mb-0">Xóa ảnh</label>
                            </div>

                        </div>
                    </div>
                    <div class="card-box position-relative box-action-image">

                        <label>Ảnh liên quan</label>
                        <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif</p>
                        <input type="file" name="photo[]" multiple class="filestyle" id="fileUploadMultiple" data-btnClass="btn-primary">
                        <div class="show-box autohide-scroll"  style="min-height: 250px;">
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

                    @if($photo->count())
                        <div class="card-box">
                            <label>Danh sách ảnh liên quan</label>
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
                                                <div class="checkbox">
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

                    <div class="card-box">
                        <label class="w-100">Ngôn ngữ</label>
                        @php
                            if($product->post_langs){
                                $id = array_unique($product->post_langs->pluck('post_id')->toArray());
                                $posts = \App\Models\Product::whereIn('id',$id)->get()->load('language');
                                $langs = \App\Models\Lang::whereNotIn('value',$posts->pluck('lang'))->where('value','<>',$product->lang)->get();
                            }else{
                                $langs = \App\Models\Lang::where('value','<>',$product->lang)->get();
                            }

                        @endphp
                        <div class="clearfix">
                            @foreach($langs as $lang)
                                <a href="{{route('admin.products.add.lang',[$lang->value,$product->id])}}" class="btn btn-primary waves-effect width-md waves-light mb-1"><span class="icon-button"><i class="fe-plus"></i> {{$lang->name}}</a>
                            @endforeach

                            @if($product->post_langs)
                                @foreach($posts as $item)
                                    <a href="{{route('admin.products.edit',$item->id)}}" class="btn btn-default waves-effect waves-light mb-1"><span class="icon-button"><i class="fe-edit-2" aria-hidden="true"></i></span> {{$item->language->name}} #{{$item->id}}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="card-box tags">
                        <label>Tags</label>
                        <p class="font-13">* Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>
                        <input class="form-control" name="data[tags]" data-role="tagsinput" value="{!! $product->tags !!}" placeholder="add tags">
                    </div>
                </div>

                <div class="col-lg-12 text-center">
                    <div class="card-box">
                        <a href="{{route('admin.products.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary waves-effect width-md waves-light" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
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
                $('.alias-seo').text(url + $(this).val() + '.html');
            })

        });
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{asset('admin/js/dynamicrows/dynamicrows.js')}}"></script>

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

    <!-- Summernote js -->
{{--    <script src="{{asset('admin/assets/libs/summernote/summernote-bs4.min.js')}}"></script>--}}

{{--    <!-- Init js -->--}}
{{--    <script src="{{asset('admin/assets/js/pages/form-summernote.init.js')}}"></script>--}}
    <script>$(function() {
            $('[data-dynamicrows]').dynamicrows({
                animation: 'fade',
                copyValues: true,
                minrows: 1
            });
        });
    </script>

    <!-- scrollbar init-->
    <script src="{{asset('admin/assets/js/pages/scrollbar.init.js')}}"></script>
@stop

@section('css')
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />

    <!-- Summernote css -->
{{--    <link href="https://coderthemes.com/adminox/layouts/vertical/assets/libs/summernote/summernote-bs4.css" rel="stylesheet" type="text/css" />--}}

@stop
