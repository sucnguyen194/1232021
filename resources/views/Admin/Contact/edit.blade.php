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
                            <li class="breadcrumb-item"><a href="{{route('admin.contacts.index')}}">Liên hệ từ khách hàng</a></li>
                            <li class="breadcrumb-item active">#ID {{$contact->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title"><strong>ĐÃ XEM</strong></h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>
    <div class="container">
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
            <div class="col-lg-12 text-right">
               <a href="{{route('admin.contacts.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
            </div>
        </div>
        <!-- end row -->
    </div>
@stop

