@extends('Layouts.layout')
@section('title') {{trans('lang.contact')}} @stop
@section('url') {{route('contact.index')}} @stop
@section('site_name') {{trans('lang.contact')}} @stop
@section('description') {{setting()->description_seo}} @stop
@section('keywords') {{setting()->keyword_seo}} @stop
@section('image') {{setting()->og_image ?? setting()->logo}} @stop
@section('content')
{{redirect_lang(\App\Enums\AliasType::CONTACT)}}
<script src='https://www.google.com/recaptcha/api.js'></script>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->

<section class="w3-display-container w3-white w3-center" style="padding-top:80px;">
    <h1 class="w3-tẽ w3-xxlarge">Liên Hệ</h1>
    <ul class="breadcrumb" style="margin:0px">
        <li><a href="{{route('home')}}" title="Trang Chủ">Trang Chủ</a></li>
        <li>Liên Hệ</li>
    </ul>
</section>
<section class="w3-display-container w3-padding-32 w3-light-grey">
    <h3 class="w3-center w3-xxlarge">Hãy Liên Hệ Chúng Tôi</h3>
    <p class="w3-center w3-large">Các vấn đề liên quan đến đến dịch vụ</p>
    <div class="w3-row-padding w3-margin-top">
        <div class="w3-col l3 m6 s12">
            <div class="w3-round-large w3-padding-16 w3-margin-top w3-grey w3-hover-grey">
                <div class="w3-light-grey w3-padding-left w3-center"> <i class="fa fa-address-card-o w3-xxlarge w3-text-green"></i> <strong class="w3-xlarge">ĐỊA CHỈ</strong> </div>
                <div class="w3-center">
                    <p class="w3-padding"> {!! setting()->address !!}</p>
                </div>
            </div>
        </div>
        <div class="w3-col l3 m6 s12">
            <div class="w3-round-large w3-padding-16 w3-margin-top w3-grey w3-hover-grey">
                <div class="w3-light-grey w3-padding-left w3-center"> <i class="fa fa-phone w3-xxlarge w3-text-green"></i> <strong class="w3-xlarge">SỐ ĐIỆN THOẠI</strong> </div>
                <div class="w3-center">
                    <p class="w3-padding w3-xxlarge">{!! setting()->hotline !!}</p>
                </div>
            </div>
        </div>
        <div class="w3-col l3 m6 s12">
            <div class="w3-round-large w3-padding-16 w3-margin-top w3-grey w3-hover-grey">
                <div class="w3-light-grey w3-padding-left w3-center"> <i class="fa fa-envelope-o w3-xxlarge w3-text-green"></i> <strong class="w3-xlarge">EMAIL</strong> </div>
                <div class="w3-center">
                    <p class="w3-padding-16 w3-large">{!! setting()->email !!}</p>
                </div>
            </div>
        </div>
        <div class="w3-col l3 m6 s12">
            <div class="w3-round-large w3-padding-16 w3-margin-top w3-grey w3-hover-grey">
                <div class="w3-light-grey w3-padding-left w3-center"> <i class="fa fa-truck w3-xxlarge w3-text-green"></i> <strong class="w3-xlarge">HỖ TRỢ</strong> </div>
                <div class="w3-center">
                    <p class="w3-padding-16 w3-xlarge">Miễn Phí</p>
                </div>
            </div>
        </div>
    </div>
    <div class="w3-row-padding w3-padding-32">
        <div class="w3-col l6 m6 s12">
            <div class="w3-card-4 w3-animate-zoom">
                <div class="w3-center">
                    <h3>FORM LIÊN HỆ</h3>
                </div>
                <div class="w3-panel w3-green" style="margin-top:0px;margin-bottom: 2px;">
                    <p id="messages"></p>
                </div>
                <form class="w3-container" action="{{route('send.contact')}}" method="POST">
                    @csrf
                    <div class="w3-section">
                        <label> <b>Tên: </b> </label>
                        <input class="w3-input w3-border w3-round" autocomplete="true" autofocus value="{{old('data.name')}}" type="text" placeholder="Nhập tên" name="data[name]" id="name" required />
                        <label> <b>Phone: </b> </label>
                        <input class="w3-input w3-border w3-round" autocomplete="true" type="text" value="{{old('data.phone')}}" placeholder="Nhập phone" name="data[phone]" required />
                        <label> <b>Email: </b> </label>
                        <input class="w3-input w3-border w3-round" autocomplete="true" type="text" value="{{old('data.email')}}" placeholder="Nhập email" name="data[email]"/>
                        <label> <b>Nội dung: </b> </label>
                        <textarea name="data[note]" class="w3-input w3-border w3-round" rows="5">{{old('data.note')}}</textarea>

                        <div class="form-group">
                            <label for="captcha">Captcha</label>
                            <div class="g-recaptcha" id="feedback-recaptcha" data-sitekey="{{setting()->re_captcha_key}}"></div>
                        </div>
                    </div>
                    <button class="w3-button w3-block w3-green w3-section w3-padding w3-round" type="submit">Gửi Cho
                        Chúng Tôi </button>
                </form>
            </div>
        </div>

        <div class="w3-col l6 m6 s12">
            {!! setting()->map !!}
        </div>
    </div>
</section>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->

@stop
