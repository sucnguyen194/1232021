@extends('Layouts.layout')
@section('title') {{$product->title_seo}} @stop
@section('url') {{route('alias',$product->alias)}} @stop
@section('description') {{$product->description_seo}} @stop
@section('keywords') {{$product->keyword_seo}} @stop
@section('site_name') {{$product->title_seo}} @stop
@section('image') {{asset($product->image ?? $setting->og_image)}} @stop
@section('content')
{{redirect_lang($product->alias)}}
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

<div class="container">
    <div class="form-comment" id="comments">
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Bình luận</label>
                <textarea rows="4" class="form-control" name="comment" required></textarea>
                <input type="slug" value="{{$product->alias}}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Gửi</button>
            </div>
        </form>
    </div>
    <div class="list-group-item">
        <div class="item-comment mb-3">
            <div class="item-comment-top mb-3">
                <div class="row">
                    <div class="col-md-1 item-avatar text-center">
                        <img src="/admin/assets/images/users/avatar-1.jpg" alt="" class="rounded-circle img-thumbnail">
                    </div>
                    <div class="col-md-11">
                        <div class="item-name"><strong>Nguyễn Văn A</strong></div>
                        <div class="item-comment">
                            aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaâ
                        </div>
                        <div class="action-comment">
                            <a href="javascript:void(0)" onclick="replyComment(332, 'HelloImHuy')">Trả lời</a> - 1 giờ trước
                        </div>
                    </div>
                </div>
                <div class="box-comment">
                    <form method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Bình luận</label>
                            <textarea rows="4" class="form-control" name="comment" required></textarea>
                            <input type="slug" value="{{$product->alias}}">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Gửi</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="sub-comment mb-3 ml-5">
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-1 item-avatar">
                            <img src="/admin/assets/images/users/avatar-1.jpg" alt="" class="rounded-circle img-thumbnail">
                        </div>
                        <div class="col-md-11">
                            <div class="item-name"><strong>Nguyễn Văn A</strong></div>
                            <div class="item-comment">
                                aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaâ
                            </div>
                            <div class="action-comment">
                                <a href="javascript:void(0)" onclick="replyComment(332, 'HelloImHuy')">Trả lời</a> - 1 giờ trước
                            </div>
                        </div>
                    </div>
                    <div class="box-comment">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Bình luận</label>
                                <textarea rows="4" class="form-control" name="comment" required></textarea>
                                <input type="hidden" name="slug" value="{{$product->alias}}">
                                <input type="hidden" value="parent" name="parent">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Gửi</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="sub-comment mb-3 ml-5">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-1 item-avatar">
                                <img src="/admin/assets/images/users/avatar-1.jpg" alt="" class="rounded-circle img-thumbnail">
                            </div>
                            <div class="col-md-11">
                                <div class="item-name"><strong>Nguyễn Văn A</strong></div>
                                <div class="item-comment">
                                    aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaâ
                                </div>
                                <div class="action-comment">
                                    <a href="javascript:void(0)" onclick="replyComment(332, 'HelloImHuy')">Trả lời</a> - 1 giờ trước
                                </div>
                            </div>
                        </div>
                        <div class="box-comment">
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Bình luận</label>
                                    <input type="hidden" name="slug" value="{{$product->alias}}">
                                    <input type="hidden" value="parent" name="parent">
                                    <textarea rows="4" class="form-control" name="comment" required></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Gửi</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
