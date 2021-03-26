@extends('Admin.Layout.layout')
@section('title')
Trình biên tập mã nguồn
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
                        <li class="breadcrumb-item active">Trình biên tập mã nguồn</li>
                    </ol>
                </div>
                <h4 class="page-title">Trình biên tập mã nguồn</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-md-4">
            <div class="card-box">
                <h4 class="header-title mb-3">Mã nguồn</h4>
                <div id="">
                    <div class="list-group">
                        <ul class="pl-0">
                            <li class="folder-name">
                                <a href="javascript:void(0)" id="open-folder" class="open-folder text-primary" data-path="folder_public"><i class="icon-img"><img src="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-folder.png?377a0415c8e86b629f04f2de969b6dc7"> </i> public</a>
                                <ul class="parent-folder" id="folder_public">
                                    {{scan_full_dir('assets')}}
                                </ul>
                            </li>
                            {{scan_full_dir('../resources')}}
                        </ul>
                    </div>
                </div>
            </div>
        </div><!-- end col -->

        <div class="col-md-8">
                <form id="put-content-file" class="loading-file" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="card-box">
                        <h4 class="header-title mb-3">Trình biên tập mã nguồn: <span class="path_file"></span></h4>
                        <div class="autohide-scroll view-source bg-listSource">
                            <div id="editor" class="views-source"></div>
                            <input class="hidden" name="dir" id="dir-file">
                            <textarea class="hidden" name="put_file"></textarea>

                        </div>
                    </div>
                    <div class="card-box text-center">
                        <a href="{{route('admin.source.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        <button type="submit" class="btn btn-primary waves-effect width-md waves-light" id="submit" name="send" value="save"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                    </div>
            </form>

        </div>
    </div>
    <!-- end row -->
</div> <!-- end container-fluid -->
<div id="toast-container" class="toast-top-right"></div>
  @stop
@section('css')
    <style type="text/css">
        .file-name {
            word-wrap: break-word;
        }
        .hidden {
            display: none;
        }
        #editor, .view-source {
            height: 600px!important;
        }
    </style>
@stop
@section('javascript')
<script src="{{asset('admin/sources/ace.js')}}" type="text/javascript"></script>
<!-- scrollbar init-->
<script src="https://coderthemes.com/adminox/layouts/vertical/assets/js/pages/scrollbar.init.js"></script>
    <script>
        $(document).on('click','#open-folder',function(){
            var path = $(this).attr('data-path');
            var result = $('#'+path);
            if(result.css('display') == 'block'){
                result.slideUp();
            }else{
                result.slideDown();
            }
        })

        $(document).on('click','#open-sub-folder',function(){
            var path = $(this).attr('data-path');
            var result = $('#'+path);
            if(result.css('display') == 'block'){
                result.slideUp();
            }else{
                result.slideDown();
            }

        })
        $(document).on("click", "#show-file", function () {
            var white_list = ['html', 'ctp', 'txt', 'xml', 'css', 'js','php'];
            var ext = $(this).attr('data-ext');
            var path = $(this).attr('data-path');
            if (path != 'undefined' && ext != 'undefined' && $.inArray(ext, white_list) > -1) {
                loadContentFile(path, ext)
            }
        });

        function loadContentFile(path, ext) {

            if (typeof ace != "undefined" && typeof require != "undefined") {

                var editor = ace.edit("editor");
                var url = '{{route('admin.ajax.load.source')}}';
                // editor.setOptions({
                //   enableBasicAutocompletion: true
                // });
                if(typeof ext != "undefined" && ext.length > 0){
                    switch(ext) {
                        case 'css':
                            editor.session.setMode("ace/mode/css");
                            break;
                        case 'js':
                            editor.session.setMode("ace/mode/javascript");
                            break;
                        case 'php':
                            editor.session.setMode("ace/mode/php");
                            break;
                        default:
                            editor.session.setMode("ace/mode/html");
                            break;
                    }
                }

                editor.getSession().setUseWrapMode(true);
                editor.setBehavioursEnabled(true);
                editor.renderer.setOption('showLineNumbers', true);
                editor.setTheme("ace/theme/monokai");

                $.ajax({
                    async: true,
                    url: url,
                    type: 'get',
                    data: {
                        path: path
                    },
                    dataType: 'html',
                    success: function (response) {
                        $('#dir-file').val(path);
                        $('span.path_file').text(path);
                        editor.setValue(response);
                    },
                    error: function (response) {

                    }
                });
            }

        }
    </script>


    <script type="text/javascript">
        var editor = ace.edit("editor");
        var textarea = $('textarea[name="put_file"]');
        editor.setTheme("ace/theme/monokai");
        editor.getSession().setUseWrapMode(true);
        editor.setBehavioursEnabled(true);
        editor.renderer.setOption('showLineNumbers', true);
        editor.session.setMode("ace/mode/php");
        editor.getSession().on("change", function () {
            textarea.val(editor.getSession().getValue());
        });
        textarea.val(editor.getSession().getValue());
        $('#submit').click(function(e){
            e.preventDefault();
            data = $('form#put-content-file').serialize();
            var url =  '{{route('admin.ajax.push.source')}}';
            var _token = $('input[name="_token"]').val();
            var dir = $('#dir-file').val();
            //console.log(dir);
            $.ajax({
                type: "post",
                cache:false,
                url: url,
                data: data,
                success: function (response) {
                    $('#toast-container').append('<div class="toast toast-success" id="toast-'+response+'" aria-live="polite" style="display: block;"><div class="toast-message">Lưu file thành công</div></div>');
                    $('.toast').each(function(){
                        setTimeout(function(){
                            $('.toast').fadeOut();
                        },7000)
                    })
                    console.log(response);
                },
                error: function (response) {
                    $('#toast-container').append('<div class="toast toast-error" id="toast-'+response+'" aria-live="polite" style="display: block;"><div class="toast-message">Không tìm thấy đường dẫn file</div></div>');
                    $('.toast').each(function(){
                        setTimeout(function(){
                            $('.toast').fadeOut();
                        },7000)
                    })
                }
            });
        })

    </script>
@stop
