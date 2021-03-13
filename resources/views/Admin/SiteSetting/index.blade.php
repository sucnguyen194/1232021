@extends('Admin.Layout.layout')
@section('title')
    Cấu hình hệ thống
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
                            <li class="breadcrumb-item active">Cấu hình hệ thống</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cấu hình hệ thống</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Clickable Wizard -->
        <div class="row">
            <div class="col-md-12">
                <div class="card-box">

                    <form id="wizard-clickable" action="{{route('admin.site.setting')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <fieldset title="1">
                            <legend>Thông tin Website</legend>
                            <div class="row mt-3">
                                <div class="col-md-8">
                                    <div class="card-box">
                                        <div class="form-group">
                                            <label for="name" class="font-weight-bold">Tiêu đề website <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name" value="{{$setting->name ?? old('name')}}" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="company" class="font-weight-bold">Tên đơn vị</label>
                                            <input type="text" class="form-control" name="company" id="company" value="{{$setting->company ?? old('company')}}">
                                        </div>


                                        <div class="form-group">
                                            <label for="slogan" class="font-weight-bold">Slogan</label>
                                            <input type="text" class="form-control" name="slogan" id="slogan" value="{{$setting->slogan ?? old('slogan')}}">
                                        </div>

                                        <div class="form-group">
                                            <label for="path" class="font-weight-bold">Đường dẫn</label>
                                            <input type="text" class="form-control" placeholder="www.company.com" name="path" id="path" value="{{$setting->path ?? old('path')}}">
                                        </div>
                                    </div>
                                    <div class="card-box">
                                        <div class="test-seo">
                                            <div class="url-seo font-weight-bold mb-1">
                                                <span class="alias-seo" id="alias_seo">{{route('home')}}</span>
                                            </div>
                                            <div class="mb-1">
                                                <a href="javascript:void(0)" class="title-seo font-weight-bold font-15">{{$setting->title_seo ?? $setting->name}}</a>
                                            </div>
                                            <div class="description-seo">
                                                {{$setting->description_seo ?? $setting->name}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-box">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Title seo</label>
                                            <p>* Ghi chú: Giới hạn tối đa 65 ký tự</p>

                                            <input type="text" maxlength="70" value="{{$setting->title_seo ?? old('title_seo')}}" name="title_seo" class="form-control" id="alloptions" />
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Description seo</label>
                                            <p>* Ghi chú: Giới hạn tối đa 158 ký tự</p>
                                            <input class="form-control" maxlength="158" value="{{$setting->description_seo ?? old('description_seo')}}" name="description_seo" id="alloptions">
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold">Keyword seo</label>
                                            <p>* Ghi chú: Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                                            <input type="text" name="keyword_seo" value="{{$setting->keyword_seo ?? old('keyword_seo')}}" class="form-control"  data-role="tagsinput"/>
                                        </div>
                                    </div>
                                    <div class="card-box">
                                        <div class="form-group mb-0">
                                            <label for="footer" class="font-weight-bold">Nội dung chân trang</label>
                                            <textarea class="form-control summernote" id="summernote" name="footer">{!!$setting->footer ?? old('footer') !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <div class="card-box position-relative box-action-image">
                                        <label for="logo-holder" class="font-weight-bold">Logo</label>
                                        <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>

                                        <input type="file" name="logo" class="filestyle" id="fileUpload" data-btnClass="btn-primary">
                                        <div class="text-center mt-2 image-holder" id="image-holder">
                                            @if(file_exists($setting->logo)) <img src="{{asset($setting->logo)}}" class="img-responsive img-thumbnail" alt="logo"> @endif
                                        </div>
                                        <div class="box-position btn btn-purple waves-effect waves-light text-left {{!file_exists($setting->logo) ? "show-box" : ""  }}">
                                            <div class="checkbox checkbox-warning checkbox-circle checkbox-unlink-logo">
                                                <input id="checkbox_logo" class="unlink-image" type="checkbox" name="unlink_logo">
                                                <label for="checkbox_logo" class="mb-0">Xóa ảnh</label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-box position-relative box-action-image">
                                        <label for="logo-holder" class="font-weight-bold">og:image</label>
                                        <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>

                                        <input type="file" name="og_image" class="filestyle" id="fileUploadOgImage" data-btnClass="btn-primary">
                                        <div class="text-center mt-2 image-holder" id="image-holder">
                                            @if(file_exists($setting->og_image)) <img src="{{asset($setting->og_image)}}" class="img-responsive img-thumbnail" alt="og:image"> @endif
                                        </div>
                                        <div class="box-position btn btn-purple waves-effect waves-light text-left {{!file_exists($setting->og_image) ? "show-box" : ""  }}">
                                            <div class="checkbox checkbox-warning checkbox-circle checkbox-unlink-og">
                                                <input id="checkbox_og" class="unlink-image" type="checkbox" name="unlink_og">
                                                <label for="checkbox_og" class="mb-0">Xóa ảnh</label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card-box position-relative box-action-image">
                                        <label for="favicon-holder" class="font-weight-bold">Favicon</label>
                                        <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif / Tỷ lệ 1:1 / Kích thước gợi ý 50x50 (px)</p>

                                        <input type="file" name="favicon" class="filestyle" id="faviconUpload" data-btnClass="btn-primary">
                                        <div class="text-center mt-2 image-holder" id="image-holder">
                                            @if(file_exists($setting->favicon)) <img src="{{asset($setting->favicon)}}" class="img-responsive img-thumbnail" alt="favicon"> @endif
                                        </div>
                                        <div class="box-position btn btn-purple waves-effect waves-light text-left {{!file_exists($setting->favicon) ? "show-box" : ""  }} ">
                                            <div class="checkbox checkbox-warning checkbox-circle checkbox-unlink-favicon">
                                                <input id="checkbox_favicon" class="unlink-image" type="checkbox" name="unlink_favicon">
                                                <label for="checkbox_favicon" class="mb-0">Xóa ảnh</label>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="card-box position-relative box-action-image">
                                        <label for="watermark-holder" class="font-weight-bold">Watermark</label>
                                        <p>* Ghi chú: Định dạng ảnh jpg, jpeg, png, gif</p>

                                        <input type="file" name="watermark" class="filestyle" id="watermarkUpload" data-btnClass="btn-primary">
                                        <div class="text-center mt-2 image-holder" id="image-holder">
                                            @if(file_exists($setting->watermark)) <img src="{{asset($setting->watermark)}}" class="img-responsive img-thumbnail" alt="watermark"> @endif
                                        </div>
                                        <div class="box-position btn btn-purple waves-effect waves-light text-left {{!file_exists($setting->watermark) ? "show-box" : "" }}">
                                            <div class="checkbox checkbox-warning checkbox-circle checkbox-unlink-watermark">
                                                <input id="checkbox_watermark" class="unlink-image" type="checkbox" name="unlink_watermark">
                                                <label for="checkbox_watermark" class="mb-0">Xóa ảnh</label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset title="2">
                            <legend>Thông tin liên hệ</legend>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="card-box">
                                        <div class="form-group">
                                            <label for="email" class="font-weight-bold">Email <small class="required">(* Địa chỉ nhận email từ khách hàng)</small></label>
                                            <input type="text" class="form-control" id="email" value="{{$setting->email ?? old('email')}}" name="email">
                                        </div>
                                        <div class="form-group mb-0">
                                            <label for="phone" class="font-weight-bold">Điện thoại</label>
                                            <input type="text" class="form-control" id="phone" value="{{$setting->phone ?? old('phone')}}" name="phone">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-box">
                                        <div class="form-group">
                                            <label for="hotline" class="font-weight-bold">Hotline</label>
                                            <input type="text" class="form-control" value="{{$setting->hotline ?? old('hotline')}}" id="hotline" name="hotline">
                                        </div>

                                        <div class="form-group mb-0">
                                            <label for="fax" class="font-weight-bold">Fax</label>
                                            <input type="text" class="form-control" value="{{$setting->fax ?? old('fax')}}" id="fax" name="fax">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card-box">
                                        <div class="form-group">
                                            <label for="numbercall" class="font-weight-bold">Action Call</label>
                                            <input type="text" class="form-control" value="{{$setting->numbercall ?? old('numbercall')}}" id="numbercall" name="numbercall">
                                        </div>

                                        <div class="form-group mb-0">
                                            <label for="time_open" class="font-weight-bold">Thời gian mở cửa</label>
                                            <input type="text" class="form-control" value="{{$setting->time_open ?? old('time_open')}}" id="time_open" name="time_open">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card-box">
                                        <div class="form-group mb-0">
                                            <label for="address" class="font-weight-bold">Địa chỉ</label>
                                            <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{$setting->address ?? old('address')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card-box">
                                        <div class="form-group mb-0">
                                            <label for="map" class="font-weight-bold">iFrame Google Map</label>
                                            <textarea name="map" id="map" cols="30" rows="5" class="form-control">{!! $setting->map ?? old('map') !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card-box">
                                        <div class="form-group mb-0">
                                            <label for="contact" class="font-weight-bold">Chi tiết liên hệ</label>
                                            <textarea class="form-control summernote" id="summercontact" name="contact">{!! $setting->contact ?? old('contact') !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset title="3">
                            <legend>Mã bổ xung (Google Analytics, API Facebook,...)</legend>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="card-box">
                                        <div class="form-group mb-0">
                                            <label for="remarketing_header" class="font-weight-bold">Mã bổ xung phía trước &lt;/head&gt; </label>
                                            <textarea class="form-control" rows="12" name="remarketing_header">{!! $setting->remarketing_header ?? old('remarketing_header') !!}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card-box">
                                        <div class="form-group mb-0">
                                            <label for="remarketing_footer" class="font-weight-bold">Mã bổ xung phía trước &lt;/body&gt; </label>
                                            <textarea class="form-control" rows="12" name="remarketing_footer">{!! $setting->remarketing_footer ?? old('remarketing_footer') !!}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset title="4">
                            <legend>Liên kết</legend>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="card-box">
                                        <div class="form-group">
                                            <label for="facebook" class="font-weight-bold">Facebook</label>
                                            <input type="text" class="form-control" value="{{$setting->facebook ?? old('facebook')}}" id="facebook" name="facebook">
                                        </div>
                                        <div class="form-group">
                                            <label for="google" class="font-weight-bold">Google+</label>
                                            <input type="text" class="form-control" value="{{$setting->google ?? old('google')}}" id="google" name="google">
                                        </div>
                                        <div class="form-group">
                                            <label for="messenger" class="font-weight-bold">Messenger</label>
                                            <input type="text" class="form-control" value="{{$setting->messenger ?? old('messenger')}}" id="messenger" name="messenger">
                                        </div>
                                        <div class="form-group">
                                            <label for="youtube" class="font-weight-bold">Youtube</label>
                                            <input type="text" class="form-control" value="{{$setting->youtube ?? old('youtube')}}" id="youtube" name="youtube">
                                        </div>
                                        <div class="form-group">
                                            <label for="zalo" class="font-weight-bold">Zalo</label>
                                            <input type="text" class="form-control" value="{{$setting->zalo ?? old('zalo')}}" id="zalo" name="zalo">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card-box">
                                        <div class="form-group">
                                            <label for="skype" class="font-weight-bold">Skype</label>
                                            <input type="text" class="form-control" value="{{$setting->skype ?? old('skype')}}" id="skype" name="skype">
                                        </div>
                                        <div class="form-group">
                                            <label for="twitter" class="font-weight-bold">Twitter</label>
                                            <input type="text" class="form-control" value="{{$setting->twitter ?? old('twitter')}}" id="twitter" name="twitter">
                                        </div>
                                        <div class="form-group">
                                            <label for="instagram" class="font-weight-bold">Instagram</label>
                                            <input type="text" class="form-control" value="{{$setting->ins ?? old('instagram')}}"  id="instagram" name="instagram">
                                        </div>
                                        <div class="form-group">
                                            <label for="linkedin" class="font-weight-bold">Linkedin</label>
                                            <input type="text" class="form-control" value="{{$setting->lin ?? old('linkedin')}}"  id="linkedin" name="linkedin">
                                        </div>
                                        <div class="form-group">
                                            <label for="pinterest" class="font-weight-bold">Pinterest</label>
                                            <input type="text" class="form-control" value="{{$setting->pin ?? old('pinterest')}}"  id="pinterest" name="pinterest">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <button type="submit" class="btn btn-primary stepy-finish"><span class="icon-button"><i class="fe-send"></i></span> Lưu lại</button>

                        <div class="card-box mt-3 text-center">
                            <a href="{{route('admin.site.setting')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                            <button type="submit" class="btn btn-primary"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- End row -->
    </div

        @stop

        @section('javascript')
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    CKEDITOR.replace( 'summercontact' ,{
                        height:250
                    });
                    // var alias = $('.alias');
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
                    {{--alias.on('keyup change',function(){--}}
                    {{--    var url = "{{route('home')}}/";--}}
                    {{--    $('.alias-seo').text(url + $(this).val() + '.html');--}}
                    {{--})--}}

                });
            </script>

    <script src="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/libs/select2/select2.min.js"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <!-- Init js-->
    <script src="{{asset('admin/assets/js/pages/form-advanced.init.js')}}"></script>
    <!--Form Wizard-->
    <script src="{{asset('admin/assets/libs/stepy/jquery.stepy.js')}}"></script>

    <!-- Validation init js-->
    <script src="{{asset('admin/assets/js/pages/wizard.init.js')}}"></script>

{{--    <!-- Summernote js -->--}}
{{--    <script src="{{asset('admin/assets/libs/summernote/summernote-bs4.min.js')}}"></script>--}}

{{--    <!-- Init js -->--}}
{{--    <script src="{{asset('admin/assets/js/pages/form-summernote.init.js')}}"></script>--}}

    <script src="{{asset('admin/assets/libs/bootstrap-filestyle2/bootstrap-filestyle.min.js')}}"></script>
@stop

@section('css')
    <style>
        .box-action-image img {
            max-height: 150px;
        }
    </style>
    <!-- Summernote css -->
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
{{--    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />--}}


@stop
