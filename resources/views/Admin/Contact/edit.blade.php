@extends('Admin.Layout.layout')
@section('title')
    Liên hệ từ khách hàng #{{$contact->id}}
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
                            <li class="breadcrumb-item"><a href="{{route('admin.contact.index')}}">Liên hệ từ khách hàng</a></li>
                            <li class="breadcrumb-item active">#ID {{$contact->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title"><strong class="text-success">ĐÃ XEM</strong></h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

            <div class="row">
                <div class="col-lg-6">
                    <div class="card-box">
                        <blockquote class="blockquote mb-0">
                            <footer class="blockquote-footer mb-1"><cite title="Họ & tên" class="font-weight-bold">Họ & tên</cite></footer>
                            <p class="mb-0">{{$contact->name}}</p>
                        </blockquote>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-box">
                        <blockquote class="blockquote mb-0">
                            <footer class="blockquote-footer mb-1"><cite title="Giới tính" class="font-weight-bold">Giới tính</cite></footer>
                            <p class="mb-0">{{$contact->gender}}</p>
                        </blockquote>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-box">
                        <blockquote class="blockquote mb-0">
                            <footer class="blockquote-footer mb-1"><cite title="Email" class="font-weight-bold">Email</cite></footer>
                            <p class="mb-0">{{$contact->email}}</p>
                        </blockquote>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card-box">
                        <blockquote class="blockquote mb-0">
                            <footer class="blockquote-footer mb-1"><cite title="Số điện thoại" class="font-weight-bold">Số điện thoại</cite></footer>
                            <p class="mb-0">{{$contact->phone}}</p>
                        </blockquote>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card-box">
                        <blockquote class="blockquote mb-0">
                            <footer class="blockquote-footer mb-1"><cite title="Địa chỉ" class="font-weight-bold">Địa chỉ</cite></footer>
                            <p class="mb-0">{!! $contact->address !!}</p>
                        </blockquote>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card-box">
                        <blockquote class="blockquote mb-0">
                            <footer class="blockquote-footer mb-1"><cite title="Lời nhắn" class="font-weight-bold">Lời nhắn</cite></footer>
                            <p class="mb-0">{!! $contact->note !!}</p>
                        </blockquote>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-box">
                        <blockquote class="blockquote mb-0">
                            <footer class="blockquote-footer mb-1"><cite title="Thời gian gửi tin nhắn" class="font-weight-bold">Thời gian gửi tin nhắn</cite></footer>
                            <p class="mb-0">{{$contact->created_at->diffForHumans()}}</p>
                        </blockquote>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-box">
                        <blockquote class="blockquote mb-0">
                            <footer class="blockquote-footer mb-1"><cite title="Thời gian duyệt tin nhắn" class="font-weight-bold">Thời gian duyệt tin nhắn</cite></footer>
                            <p class="mb-0">{{$contact->updated_at->diffForHumans()}}</p>
                        </blockquote>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-box">
                        <blockquote class="blockquote mb-0">
                            <footer class="blockquote-footer mb-1"><cite title="Người duyệt" class="font-weight-bold">Người duyệt</cite></footer>
                            <p class="mb-0">{{$contact->user->name ?? $contact->user->email}}</p>
                        </blockquote>
                    </div>
                </div>
                <div class="col-lg-12 text-center">
                    <div class="card-box">
                        <a href="{{route('admin.contact.index')}}" class="btn btn-purple waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    </div>
                </div>
            </div>
            <!-- end row -->

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

    <!-- Summernote js -->
    <script src="{{asset('admin/assets/libs/summernote/summernote-bs4.min.js')}}"></script>

    <!-- Init js -->
    <script src="{{asset('admin/assets/js/pages/form-summernote.init.js')}}"></script>
@stop

@section('css')
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />

    <!-- Summernote css -->
    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />



@stop
