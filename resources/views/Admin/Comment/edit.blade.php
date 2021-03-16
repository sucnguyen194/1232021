@extends('Admin.Layout.layout')
@section('title')
    Bình luận bài viết #{{$model->id}}
@stop
@section('content')

    <div class="container-fluid" id="app-comments">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Bảng điều khiển</a></li>
                            <li class="breadcrumb-item"><a href="{{route('admin.comments.index')}}">Danh sách bình luận</a></li>
                            <li class="breadcrumb-item active">Bình luận bài viết</li>
                            <li class="breadcrumb-item">#{{$model->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Bình luận bài viết <a href="{{route('alias',$model->alias)}}" target="_blank">#{{$model->name ?? $model->title}}</a></h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="form-comment" id="comments">
            <form method="post" action="{{route('comments.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="font-weight-bold">Bình luận</label>
                    <textarea rows="4" class="form-control" name="comment" required></textarea>
                    <input type="hidden" name="slug" value="{{$model->alias}}" readonly>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><span class="icon-button"><i class="pe-7s-paper-plane"></i> </span> Gửi</button>
                </div>
            </form>
        </div>
        <div class="list-group-item">
            @foreach($comments->where('parent_id',0) as $comment)
                <div class="item-comment mb-3">
                    <div class="item-comment-top mb-3">
                        <div class="row">
                            <div class="col-md-1 item-avatar text-center">
                                @if($comment->admin_id)
                                    <img src="/admin/assets/images/users/avatar-1.jpg" alt="" class="rounded-circle img-thumbnail">
                                @else
                                    <img src="/admin/assets/images/users/avatar-3.jpg" alt="" class="rounded-circle img-thumbnail">
                                @endif
                            </div>
                            <div class="col-md-11">
                                <div class="item-name">
                                    <span class="status-comment font-15">
                                       <i class="{{$comment->status == 1 ? "pe-7s-like2 text-primary" : "pe-7s-info text-danger"}} font-weight-bold"></i>
                                    </span>
                                    @if($comment->admin_id)
                                        <strong>{{$comment->admin->email}}</strong> <span class="qtv">QTV</span>
                                    @else
                                        <strong>{{$comment->user->name ?? 'No'.$comment->id}}</strong>
                                    @endif
                                </div>
                                <div class="item-comment mt-1">
                                    @if($comment->hidden == 0)  {!! $comment->note !!} @else <em>Bình luận đã bị ẩn</em> @endif
                                </div>
                                <div class="action-comment mt-1">
                                    @if($comment->admin_id)
                                        <a href="javascript:void(0)" class="font-weight-bold" onclick="openComment({{$comment->id}}, '{{$comment->admin->email}}')">Trả lời</a> -
                                    @else
                                        <a href="javascript:void(0)" class="font-weight-bold" onclick="openComment({{$comment->id}}, '{{$comment->user->name ?? 'No'.$comment->id}}')">Trả lời</a> -
                                    @endif
                                    <form method="post" action="{{route('admin.comments.update',$comment)}}" class="d-inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="border-0 p-0 bg-transparent text-primary font-weight-bold">{{$comment->hidden == 0 ? "Ẩn bình luận" : "Bỏ ẩn"}}</button>
                                    </form>
                                        - {{$comment->created_at->diffForHumans()}}
                                </div>
                            </div>
                        </div>
                        <div class="box-comment mt-2" target="{{$comment->id}}">
                            <form method="post" action="{{route('comments.store')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="font-weight-bold">Bình luận</label>
                                    <textarea rows="4" class="form-control" name="comment" required></textarea>
                                    <input type="hidden" name="slug" value="{{$model->alias}}">
                                    <input type="hidden" name="parent" value="{{$comment->id}}">
                                    <input type="hidden" name="reply" value="{{$comment->id}}">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><span class="icon-button"><i class="pe-7s-paper-plane"></i> </span> Gửi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @foreach($comments->where('parent_id',$comment->id) as $sub)
                        <div class="sub-comment mb-3 ml-5">
                            <div class="item-comment-top mb-3">
                                <div class="row">
                                    <div class="col-md-1 item-avatar">
                                        @if($sub->admin_id)
                                        <img src="/admin/assets/images/users/avatar-1.jpg" alt="" class="rounded-circle img-thumbnail">
                                        @else
                                        <img src="/admin/assets/images/users/avatar-3.jpg" alt="" class="rounded-circle img-thumbnail">
                                        @endif
                                    </div>
                                    <div class="col-md-11">
                                        <div class="item-name">
                                            <span class="status-comment font-15">
                                               <i class="{{$sub->status == 1 ? "pe-7s-like2 text-primary" : "pe-7s-info text-danger"}} font-weight-bold"></i>
                                            </span>
                                            @if($sub->admin_id)
                                                <strong>{{$sub->admin->email}}</strong> <span class="qtv">QTV</span>
                                            @else
                                                <strong>{{$sub->user->name ?? 'No'.$sub->id}}</strong>
                                            @endif
                                        </div>
                                        <div class="item-comment mt-1">
                                            @if($sub->hidden == 0)  {!! $sub->note !!} @else <em>Bình luận đã bị ẩn</em> @endif
                                        </div>
                                        <div class="action-comment mt-1">
                                            @if($sub->admin_id)
                                                <a href="javascript:void(0)" class="font-weight-bold" onclick="openComment({{$sub->id}}, '{{$sub->admin->email}}')">Trả lời</a> - @else
                                                <a href="javascript:void(0)" class="font-weight-bold" onclick="openComment({{$sub->id}}, '{{$sub->user->name ?? 'No'.$sub->id}}')">Trả lời</a> - @endif
                                            <form method="post" action="{{route('admin.comments.update',$sub)}}" class="d-inline-block">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="border-0 p-0 bg-transparent text-primary font-weight-bold">{{$sub->hidden == 0 ? "Ẩn bình luận" : "Bỏ ẩn"}}</button>
                                            </form>
                                            - {{$sub->created_at->diffForHumans()}}
                                        </div>
                                    </div>
                                </div>
                                <div class="box-comment mt-2" target="{{$sub->id}}">
                                    <form method="post" action="{{route('comments.store')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label class="font-weight-bold">Bình luận</label>
                                            <textarea rows="4" class="form-control" name="comment" required></textarea>
                                            <input type="hidden" name="slug" value="{{$model->alias}}">
                                            <input type="hidden" value="{{$sub->id}}" name="parent">
                                            <input type="hidden" name="reply" value="{{$sub->id}}">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary"><span class="icon-button"><i class="pe-7s-paper-plane"></i> </span> Gửi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @foreach($comments->where('parent_id',$sub->id) as $sub_sub)
                                <div class="sub-comment mb-3 ml-5">
                                    <div class="item-comment-top mb-3">
                                        <div class="row">
                                            <div class="col-md-1 item-avatar">
                                                @if($sub_sub->admin_id)
                                                    <img src="/admin/assets/images/users/avatar-1.jpg" alt="" class="rounded-circle img-thumbnail">
                                                @else
                                                    <img src="/admin/assets/images/users/avatar-3.jpg" alt="" class="rounded-circle img-thumbnail">
                                                @endif
                                            </div>
                                            <div class="col-md-11">
                                                <div class="item-name">
                                                    <span class="status-comment font-15">
                                                       <i class="{{$sub_sub->status == 1 ? "pe-7s-like2 text-primary" : "pe-7s-info text-danger"}} font-weight-bold"></i>
                                                    </span>
                                                    @if($sub_sub->admin_id)
                                                        <strong>{{$sub_sub->admin->email}}</strong> <span class="qtv">QTV</span>
                                                    @else
                                                        <strong>{{$sub_sub->user->name ?? 'No'.$sub_sub->id}}</strong>
                                                    @endif
                                                </div>
                                                <div class="item-comment mt-1">
                                                    @if($sub_sub->hidden == 0)  {!! $sub_sub->note !!} @else <em>Bình luận đã bị ẩn</em> @endif
                                                </div>
                                                <div class="action-comment mt-1">
                                                    @if($sub_sub->admin_id)
                                                        <a href="javascript:void(0)" class="font-weight-bold" onclick="openComment({{$sub_sub->id}}, '{{$sub_sub->admin->email}}')">Trả lời</a> -
                                                    @else
                                                        <a href="javascript:void(0)" class="font-weight-bold" onclick="openComment({{$sub_sub->id}}, '{{$sub_sub->user->name ?? 'No'.$sub_sub->id}}')">Trả lời</a> -
                                                    @endif
                                                    <form method="post" action="{{route('admin.comments.update',$sub_sub)}}" class="d-inline-block">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="border-0 p-0 bg-transparent text-primary font-weight-bold">{{$sub_sub->hidden == 0 ? "Ẩn bình luận" : "Bỏ ẩn"}}</button>
                                                    </form>
                                                    - {{$sub_sub->created_at->diffForHumans()}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box-comment mt-2" target="{{$sub_sub->id}}">
                                            <form method="post" action="{{route('comments.store')}}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Bình luận</label>
                                                    <input type="hidden" name="slug" value="{{$model->alias}}">
                                                    <input type="hidden" value="{{$sub->id}}" name="parent">
                                                    <input type="hidden" name="reply" value="{{$sub_sub->id}}">
                                                    <textarea rows="4" class="form-control" name="comment" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary"><span class="icon-button"><i class="pe-7s-paper-plane"></i> </span> Gửi</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    <script>
        $('.box-comment').hide();
        function openComment(id, name){
            $('.box-comment').hide();
            let box = $('.box-comment[target="'+id+'"]');
            box.show();
            box.find('textarea').val('@'+name+': ');
        }
    </script>
@stop

@section('javascript')
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
