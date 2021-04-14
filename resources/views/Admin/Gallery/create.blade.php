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
                            <li class="breadcrumb-item"><a href="{{route('admin.products.galleries.index')}}">Album ảnh</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Thêm mới</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>
    <div class="container">
        <form method="post" action="{{route('admin.products.store')}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                <div class="col-lg-8">
                    <div class="card-box option-height-left">
                        <div class="form-group">
                            <label>Tiêu đề <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{old('data.name')}}" id="title" onkeyup="ChangeToSlug();" name="data[name]" required>
                        </div>

                        <div class="form-group mb-0">
                            <label>Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="data[description]">{!! old('data.description') !!}</textarea>
                        </div>
                    </div>
                    <div class="card-box position-relative box-action-image">
                        <label>Hình ảnh</label>
                        <div class="position-absolute font-weight-normal text-primary" id="box-input" style="right:2.2rem;top:1.3rem">
                            <label class="item-input">
                                <input type="file" name="photo[]" class="d-none" id="fileUploadMultiple" multiple> Chọn ảnh
                            </label>
                        </div>
                        <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif</p>
                        <div class="dropzone pl-2 pr-2 pb-1">
                            <div class="dz-message text-center needsclick mb-2" id="remove-label">
                                <label for="fileUploadMultiple" class="w-100 mb-0">
                                    <div class="icon-dropzone pt-2">
                                        <i class="h1 text-muted dripicons-cloud-upload"></i>
                                    </div>
                                    <span class="text-muted font-13">Sử dụng nút <strong>Chọn ảnh</strong> để thêm ảnh</span>
                                </label>
                            </div>
                            <ul class="show-box image-holder pl-0 mb-0 w-100" id="sortable">

                            </ul>
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
                                <a href="javascript:void(0)" class="title-seo"></a>
                            </div>
                            <div class="url-seo">
                                <span class="alias-seo" id="alias_seo">{{route('home')}}</span>
                            </div>
                            <div class="description-seo">

                            </div>
                        </div>

                        <div class="change-seo" id="change-seo">
                            <hr>
                            <div class="form-group">
                                <label>Tiêu đề trang</label>
                                <p class="font-13">* Giới hạn tối đa 70 ký tự</p>
                                <input type="text" maxlength="70" value="{{old('data.title_seo')}}" name="data[title_seo]" class="form-control" id="alloptions" />
                            </div>
                            <div class="form-group">
                                <label>Mô tả trang</label>
                                <p class="font-13">* Giới hạn tối đa 320 ký tự</p>
                                <textarea  class="form-control" rows="3" name="data[description_seo]" maxlength="320" id="alloptions">{{old('data.description_seo')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Từ khóa</label>
                                <p class="font-13">* Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                                <input type="text" name="data[keyword_seo]" value="{{old('data.keyword_seo')}}" class="form-control"  data-role="tagsinput"/>
                            </div>
                            <div class="form-group mb-0">
                                <label>Đường dẫn <span class="required">*</span></label>
                                <div class="d-flex form-control">
                                    <span>{{route('home')}}/</span><input type="text" class="border-0 alias" id="alias" value="{{old('data.alias')}}" name="data[alias]" required>
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
                            <input id="checkbox_public" checked type="checkbox" name="data[public]" value="1">
                            <label for="checkbox_public">Hiển thị</label>
                        </div>

                        <div class="checkbox">
                            <input id="checkbox_status" type="checkbox" name="data[status]" value="1">
                            <label for="checkbox_status" class="mb-0">Nổi bật</label>
                        </div>
                    </div>
                    <div class="card-box d-none">
                        <label>Tab hình ảnh</label>
                        <select class="form-control" data-toggle="select2" name="">
                            <option value="0">-----</option>
                        </select>
                    </div>
                    <div class="card-box">
                        <div class="form-group mb-0">
                            <label>Tags</label>
                            <p class="font-13">* Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                            <input type="text" name="data[tags]" value="{{old('data.tags')}}" class="form-control"  data-role="tagsinput"/>
                        </div>
                    </div>

                </div>

                <div class="col-lg-12">
                    <input type="hidden" name="data[type]" value="{{\App\Enums\SystemsModuleType::GALLERY}}">
                    <a href="{{route('admin.products.galleries.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    <button type="submit" class="btn btn-primary waves-effect width-md waves-light float-right" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                </div>
            </div>
            <!-- end row -->
        </form>
    </div>
    <div id="viewImage" class="modal fade" tabindex="-1" aria-labelledby="myLargeModalLabel" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <img src="" class="img-fluid showImage">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"> Đóng</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <script>
        $(document).on('click','.view-image',function(){
            let image = $(this).attr('data-image');
            $('.showImage').attr('src', image);
        })
        CKEDITOR.replace('summernote',{
            height:150,
        })
    </script>
@stop

@section('javascript')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js">

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
    <script>
        $( "#sortable" ).sortable();
    </script>

    <script src="{{asset('admin/assets/libs/tooltipster/tooltipster.bundle.min.js')}}"></script>
@stop

@section('css')
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />
    <link href="/admin/assets/libs/tooltipster/tooltipster.bundle.min.css" rel="stylesheet" type="text/css" >
    <!-- Summernote css -->
{{--    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />--}}
@stop
