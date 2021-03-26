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


    </div>
<div class="container">
    <a href="{{route('admin.site.setting')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
    <button type="submit" onclick="document.querySelector('#form-update').submit()" class="btn btn-primary waves-effect waves-light float-right"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
    <!-- Clickable Wizard -->
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('admin.site.setting')}}" method="post" id="form-update" enctype="multipart/form-data">
                <div id="wizard-clickable" >
                    @csrf
                    <fieldset title="1">
                        <legend>Thông tin Website</legend>
                        <div class="row mt-1">
                            <div class="col-md-8">
                                <div class="card-box">
                                    <div class="form-group">
                                        <label for="name">Tiêu đề website <span class="required">*</span></label>
                                        <input type="text" class="form-control" name="name" id="name" value="{{$setting->name ?? old('name')}}" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="company">Tên đơn vị</label>
                                        <input type="text" class="form-control" name="company" id="company" value="{{$setting->company ?? old('company')}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="slogan">Slogan</label>
                                        <input type="text" class="form-control" name="slogan" id="slogan" value="{{$setting->slogan ?? old('slogan')}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="path">Website</label>
                                        <input type="text" class="form-control" placeholder="www.company.com" name="path" id="path" value="{{$setting->path ?? old('path')}}">
                                    </div>
                                </div>

                                <div class="card-box">
                                    <label for="footer">Nội dung chân trang</label>
                                    <textarea class="form-control summernote" id="summernote" name="footer">{!!$setting->footer ?? old('footer') !!}</textarea>
                                </div>

                                <div class="card-box">
                                    <div class="d-flex mb-2">
                                        <label class="font-weight-bold">Tối ưu SEO</label>
                                        <a href="javascript:void(0)" onclick="changeSeo()" class="edit-seo">Chỉnh sửa SEO</a>
                                    </div>

                                    <p class="font-13">Thiết lập các thẻ mô tả giúp khách hàng dễ dàng tìm thấy trang trên công cụ tìm kiếm như Google.</p>
                                    <div class="test-seo">
                                        <div class="mb-1">
                                            <a href="javascript:void(0)" class="title-seo">{{$setting->name}}</a>
                                        </div>
                                        <div class="url-seo">
                                            <span class="alias-seo" id="alias_seo">{{route('home')}}</span>
                                        </div>
                                        <div class="description-seo">
                                            {{$setting->description_seo}}
                                        </div>
                                    </div>

                                    <div class="change-seo" id="change-seo">
                                        <hr>
                                        <div class="form-group">
                                            <label>Mô tả trang</label>
                                            <p class="font-13">* Giới hạn tối đa 320 ký tự</p>
                                            <textarea  class="form-control" rows="3" name="description_seo" maxlength="320" id="alloptions">{{$setting->description_seo ?? old('description_seo')}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Từ khóa</label>
                                            <p class="font-13">* Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                                            <input type="text" name="keyword_seo" value="{{$setting->keyword_seo ?? old('keyword_seo')}}" class="form-control"  data-role="tagsinput"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card-box position-relative box-action-image">
                                    <label for="logo-holder">Logo</label>
                                    <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif</p>
                                    <input type="file" name="logo" class="filestyle" id="fileUpload" data-btnClass="btn-primary">
                                    <div class="text-center mt-2 image-holder" id="image-holder">
                                        @if(file_exists($setting->logo)) <img src="{{asset($setting->logo)}}" class="img-responsive img-thumbnail" alt="logo"> @endif
                                    </div>
                                    <div class="box-position btn btn-default waves-effect waves-light text-left {{!file_exists($setting->logo) ? "show-box" : ""  }}">
                                        <div class="checkbox checkbox-unlink-logo">
                                            <input id="checkbox_logo" class="unlink-image" type="checkbox" name="unlink_logo">
                                            <label for="checkbox_logo" class="mb-0">Xóa ảnh</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-box position-relative box-action-image">
                                    <label for="logo-holder">og:image</label>
                                    <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif</p>

                                    <input type="file" name="og_image" class="filestyle" id="fileUploadOgImage" data-btnClass="btn-primary">
                                    <div class="text-center mt-2 image-holder" id="image-holder">
                                        @if(file_exists($setting->og_image)) <img src="{{asset($setting->og_image)}}" class="img-responsive img-thumbnail" alt="og:image"> @endif
                                    </div>
                                    <div class="box-position btn btn-default waves-effect waves-light text-left {{!file_exists($setting->og_image) ? "show-box" : ""  }}">
                                        <div class="checkbox checkbox-unlink-og">
                                            <input id="checkbox_og" class="unlink-image" type="checkbox" name="unlink_og">
                                            <label for="checkbox_og" class="mb-0">Xóa ảnh</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-box position-relative box-action-image">
                                    <label for="favicon-holder">Favicon</label>
                                    <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif / Tỷ lệ 1:1 / Kích thước gợi ý 50x50 (px)</p>

                                    <input type="file" name="favicon" class="filestyle" id="faviconUpload" data-btnClass="btn-primary">
                                    <div class="text-center mt-2 image-holder" id="image-holder">
                                        @if(file_exists($setting->favicon)) <img src="{{asset($setting->favicon)}}" class="img-responsive img-thumbnail" alt="favicon"> @endif
                                    </div>
                                    <div class="box-position btn btn-default waves-effect waves-light text-left {{!file_exists($setting->favicon) ? "show-box" : ""  }} ">
                                        <div class="checkbox checkbox-unlink-favicon">
                                            <input id="checkbox_favicon" class="unlink-image" type="checkbox" name="unlink_favicon">
                                            <label for="checkbox_favicon" class="mb-0">Xóa ảnh</label>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <div class="card-box position-relative box-action-image">
                                    <label for="watermark-holder">Watermark</label>
                                    <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif</p>

                                    <input type="file" name="watermark" class="filestyle" id="watermarkUpload" data-btnClass="btn-primary">
                                    <div class="text-center mt-2 image-holder" id="image-holder">
                                        @if(file_exists($setting->watermark)) <img src="{{asset($setting->watermark)}}" class="img-responsive img-thumbnail" alt="watermark"> @endif
                                    </div>
                                    <div class="box-position btn btn-default waves-effect waves-light text-left {{!file_exists($setting->watermark) ? "show-box" : "" }}">
                                        <div class="checkbox checkbox-unlink-watermark">
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
                        <div class="card-box mt-1">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">Email <small class="required">(* Địa chỉ nhận email từ khách hàng)</small></label>
                                        <input type="text" class="form-control" id="email" value="{{$setting->email ?? old('email')}}" name="email">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label for="phone">Điện thoại</label>
                                        <input type="text" class="form-control" id="phone" value="{{$setting->phone ?? old('phone')}}" name="phone">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hotline">Hotline</label>
                                        <input type="text" class="form-control" value="{{$setting->hotline ?? old('hotline')}}" id="hotline" name="hotline">
                                    </div>

                                    <div class="form-group mb-0">
                                        <label for="fax">Fax</label>
                                        <input type="text" class="form-control" value="{{$setting->fax ?? old('fax')}}" id="fax" name="fax">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="numbercall">Action Call</label>
                                        <input type="text" class="form-control" value="{{$setting->numbercall ?? old('numbercall')}}" id="numbercall" name="numbercall">
                                    </div>

                                    <div class="form-group mb-0">
                                        <label for="time_open">Thời gian mở cửa</label>
                                        <input type="text" class="form-control" value="{{$setting->time_open ?? old('time_open')}}" id="time_open" name="time_open">
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{$setting->address ?? old('address')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="map">iFrame Google Map</label>
                                <textarea name="map" id="map" cols="30" rows="5" class="form-control">{!! $setting->map ?? old('map') !!}</textarea>
                            </div>
                            <div class="form-group mb-0">
                                <label for="contact">Chi tiết liên hệ</label>
                                <textarea class="form-control summernote" id="summercontact" name="contact">{!! $setting->contact ?? old('contact') !!}</textarea>
                            </div>
                        </div>

                    </fieldset>
                    <fieldset title="3">
                        <legend>Mã bổ xung (Marketing...)</legend>
                        <div class="card-box mt-1">
                            <div class="row ">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label for="remarketing_header">Mã bổ xung phía trước &lt;/head&gt; </label>
                                        <textarea class="form-control" rows="12" name="remarketing_header">{!! $setting->remarketing_header ?? old('remarketing_header') !!}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label for="remarketing_footer">Mã bổ xung phía trước &lt;/body&gt; </label>
                                        <textarea class="form-control" rows="12" name="remarketing_footer">{!! $setting->remarketing_footer ?? old('remarketing_footer') !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                    <fieldset title="4">
                        <legend>Liên kết</legend>
                        <div class="card-box  mt-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="facebook">Facebook</label>
                                        <input type="text" class="form-control" value="{{$setting->facebook ?? old('facebook')}}" id="facebook" name="facebook">
                                    </div>
                                    <div class="form-group">
                                        <label for="google">Google+</label>
                                        <input type="text" class="form-control" value="{{$setting->google ?? old('google')}}" id="google" name="google">
                                    </div>
                                    <div class="form-group">
                                        <label for="messenger">Messenger</label>
                                        <input type="text" class="form-control" value="{{$setting->messenger ?? old('messenger')}}" id="messenger" name="messenger">
                                    </div>
                                    <div class="form-group">
                                        <label for="youtube">Youtube</label>
                                        <input type="text" class="form-control" value="{{$setting->youtube ?? old('youtube')}}" id="youtube" name="youtube">
                                    </div>
                                    <div class="form-group">
                                        <label for="zalo">Zalo</label>
                                        <input type="text" class="form-control" value="{{$setting->zalo ?? old('zalo')}}" id="zalo" name="zalo">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="skype">Skype</label>
                                        <input type="text" class="form-control" value="{{$setting->skype ?? old('skype')}}" id="skype" name="skype">
                                    </div>
                                    <div class="form-group">
                                        <label for="twitter">Twitter</label>
                                        <input type="text" class="form-control" value="{{$setting->twitter ?? old('twitter')}}" id="twitter" name="twitter">
                                    </div>
                                    <div class="form-group">
                                        <label for="instagram">Instagram</label>
                                        <input type="text" class="form-control" value="{{$setting->ins ?? old('instagram')}}"  id="instagram" name="instagram">
                                    </div>
                                    <div class="form-group">
                                        <label for="linkedin">Linkedin</label>
                                        <input type="text" class="form-control" value="{{$setting->lin ?? old('linkedin')}}"  id="linkedin" name="linkedin">
                                    </div>
                                    <div class="form-group">
                                        <label for="pinterest">Pinterest</label>
                                        <input type="text" class="form-control" value="{{$setting->pin ?? old('pinterest')}}"  id="pinterest" name="pinterest">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset title="5">
                        <legend>API</legend>
                        <div class="row mt-1">
                            <div class="col-lg-6">
                                <div class="card-box">
                                    <div class="form-group">
                                        <label for="re_captcha_key">ReCaptcha Key <a href="https://www.google.com/recaptcha/admin/create" target="_blank">(Google reCAPTCHA)</a> </label>
                                        <input type="text" class="form-control" value="{{$setting->re_captcha_key ?? old('re_captcha_key')}}" id="re_captcha_key" name="re_captcha_key">
                                    </div>
                                    <div class="form-group">
                                        <label for="re_captcha_secret">ReCaptcha Secret</label>
                                        <input type="text" class="form-control" value="{{$setting->re_captcha_secret ?? old('re_captcha_secret')}}" id="re_captcha_secret" name="re_captcha_secret">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card-box">
                                    <div class="form-group">
                                        <label for="facebook_app_ip">Facebook App ip <a href="https://developers.facebook.com/apps/" target="_blank">(FACEBOOK for Developers)</a> </label>
                                        <input type="text" class="form-control" value="{{$setting->facebook_app_ip ?? old('facebook_app_ip')}}" id="facebook_app_ip" name="facebook_app_ip">
                                    </div>
                                    <div class="form-group">
                                        <label for="facebook_app_secret">Facebook App secret</label>
                                        <input type="text" class="form-control" value="{{$setting->facebook_app_secret ?? old('facebook_app_secret')}}" id="facebook_app_secret" name="facebook_app_secret">
                                    </div>
                                </div>

                            </div>
                        </div>


                    </fieldset>
                    <button type="submit" class="btn btn-primary stepy-finish"><span class="icon-button"><i class="fe-send"></i></span> Lưu lại</button>
                </div>

                <div class="mt-3">
                    <a href="{{route('admin.site.setting')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    <button type="submit" class="btn btn-primary waves-effect waves-light float-right"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                </div>
            </form>
        </div>
    </div>
    <!-- End row -->
</div>
        @stop

        @section('javascript')
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    CKEDITOR.replace( 'summercontact' ,{
                        height:250
                    });
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
