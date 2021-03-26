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
                            <li class="breadcrumb-item"><a href="{{route('admin.action.module.index',$module->table)}}">{{$module->name}}</a></li>
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
        <form method="post" action="{{route('admin.action.module.add',$module->table)}}" enctype="multipart/form-data">
            <div class="row">
                @csrf

                <div class="{{$module->type ? "col-lg-8" : "col-lg-12"}}">
                    @foreach(json_decode($module->fields) as $items)
                        @switch($items->display_type)
                            @case(0)
                            <div class="card-box">
                                <label>{{$items->display_name}} <span class="required">*</span></label>
                                <input type="text" class="form-control" value="{{old($items->name)}}" id="{{$items->name}}" name="{{$items->name}}" required>
                            </div>
                            @break

                            @case(2)
                            <div class="card-box">
                                <label>{{$items->display_name}} </label>
                                <input type="number" class="form-control" value="{{old($items->name ?? 0)}}" min="0" id="{{$items->name}}" name="{{$items->name}}">
                            </div>
                            @break

                            @case(6)
                            <div class="card-box">
                                <label>{{$items->display_name}} </label>
                                <textarea class="form-control summernote" id="summernote" name="{{$items->name}}">{!! old($items->name) !!}</textarea>
                            </div>
                            @break
                            @default
                        @endswitch

                    @endforeach
                </div>
                <div class="{{$module->type ? "col-lg-4" : "col-lg-12"}}">
                    <div class="row">
                        @foreach(json_decode($module->fields) as $items)

                            <div class="{{$module->type ? "col-lg-12" : "col-lg-4"}}">
                                @switch($items->display_type)
                                    @case(1)
                                    <div class="card-box">
                                        <label class="mb-0">Trạng thái</label>
                                        <hr>
                                        <div class="checkbox">
                                            <input id="checkbox_{{$items->name}}" type="checkbox" name="{{$items->name}}" checked>
                                            <label for="checkbox_{{$items->name}}" class="mb-0">{{$items->name}}</label>
                                        </div>
                                    </div>
                                    @break

                                    @case(3)
                                    <div class="card-box">
                                        <label class="mb-0">Trạng thái</label>
                                        <hr>
                                        <div class="checkbox">
                                            <input id="checkbox_{{$items->name}}" type="checkbox" name="{{$items->name}}" checked>
                                            <label for="checkbox_{{$items->name}}" class="mb-0">{{$items->name}}</label>
                                        </div>
                                    </div>
                                    @break

                                    @case(4)
                                    <div class="card-box">
                                        <label>{{$items->display_name}} </label>
                                        <select class="form-control" data-toggle="select2" name="{{$items->name}}">
                                            @foreach($items->option as $key => $val)
                                                <option value="{{$val}}"> {{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @break

                                    @case(5)
                                    <div class="card-box box-action-image position-relative">
                                        <label>{{$items->display_name}} </label>
                                        <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif</p>

                                        <input type="file" name="{{$items->name}}" class="filestyle" id="fileUpload-{{$items->name}}" data-btnClass="btn-primary">
                                        <div class="text-center mt-2 image-holder" id="image-holder-{{$items->name}}">

                                        </div>
                                        <div class="box-position btn btn-default waves-effect waves-light text-left hidden-box show-box-{{$items->name}}">

                                            <div class="checkbox checkbox-unlink-{{$items->name}}">
                                                <input id="checkbox_{{$items->name}}" class="unlink-{{$items->name}}" type="checkbox" name="unlink_{{$items->name}}">
                                                <label for="checkbox_{{$items->name}}" data-name="{{$items->name}}" class="mb-0">Xóa ảnh</label>
                                            </div>

                                        </div>
                                    </div>
                                    @break
                                    @default
                                @endswitch
                            </div>

                        @endforeach
                    </div>
                </div>

                <div class="col-lg-12">
                    <a href="{{route('admin.action.module.index',$module->table)}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    <button type="submit" class="btn btn-primary waves-effect width-md waves-light float-right" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                </div>
            </div>
            <!-- end row -->
        </form>
    </div>
<style>
    .box-action-image img {
        max-height: 150px;
    }
    .hidden-box {
        display: none;
    }
</style>
@stop
<script>

</script>

@section('javascript')

    <script>
        @foreach(json_decode($module->fields) as $items)
           @if($items->display_type == 5)
        $(document).on('change','#fileUpload-{{$items->name}}',function(){

            $('.unlink-{{$items->name}}').prop('checked',false);
            //Get count of selected files
            var countFiles = $(this)[0].files.length;
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#image-holder-{{$items->name}}");
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg" || extn == "ico" || extn == "webp" || "jfif") {
                if (typeof (FileReader) != "undefined") {
                    //loop for each file selected for uploaded.
                    for (var i = 0; i < countFiles; i++) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('<img src="'+e.target.result+'" class="img-responsive img-thumbnail" />').appendTo(image_holder);
                        }
                        $('.show-box-{{$items->name}}').show();
                        image_holder.show();
                        reader.readAsDataURL($(this)[0].files[i]);
                    }
                }else{
                    alert("This browser does not support FileReader.");
                }
            }else{
                alert("Please select only images");
            }
        });

        $(document).on('click','.checkbox-unlink-{{$items->name}} label',function(){
            if($('.unlink-{{$items->name}}').is(':checked')){
                $('#image-holder-{{$items->name}}').find('img').css('opacity','1');
            }else{
                $('#image-holder-{{$items->name}}').find('img').css('opacity','0.2');
            }
        })
        @endif
    @endforeach
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

    <!-- Plugins js -->
    <script src="{{asset('admin/assets/libs/katex/katex.min.js')}}"></script>

    <script src="{{asset('admin/assets/libs/quill/quill.min.js')}}"></script>
    <!-- Init js-->
    <script src="{{asset('admin/assets/js/pages/form-quilljs.init.js')}}"></script>
@stop

@section('css')

    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />

    <!-- Summernote css -->
{{--    <link href="{{asset('admin/assets/libs/summernote/summernote-bs4.css')}}" rel="stylesheet" type="text/css" />--}}

    <!-- Plugins css -->
    <link href="{{asset('admin/assets/libs/quill/quill.core.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/quill/quill.bubble.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/quill/quill.snow.css')}}" rel="stylesheet" type="text/css" />
@stop
