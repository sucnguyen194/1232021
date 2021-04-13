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
        <!-- end page title -->
        <div class="row">

            <!-- Right Sidebar -->
            <div class="col-lg-12">
                <div class="card-box">
                    <div class="mt-0">
                        <div class="media mb-4">
                            <img class="d-flex mr-3 rounded-circle avatar-sm" src="{{$contact->avatar}}" alt="Generic placeholder image">
                            <div class="media-body">
                                <span class="float-right">{{$contact->created_at->diffForHumans()}}</span>
                                <h5 class="m-0">{{$contact->name}}</h5>
                                <small class="text-muted">From: {{$contact->email}}</small><br>
                                <small class="text-muted">Phone: {{$contact->phone}}</small>
                            </div>
                        </div>

                        <p>{!! nl2br($contact->note) !!}</p>
                        <hr/>

                        <h6> <i class="fa fa-paperclip mb-2"></i> File đính kèm <span>(0)</span> </h6>

{{--                        <div class="row">--}}
{{--                            <div class="col-xl-2 col-md-4">--}}
{{--                                <a href="#"> <img src="assets/images/attached-files/img-1.jpg" alt="attachment" class="img-thumbnail img-fluid"> </a>--}}
{{--                            </div>--}}
{{--                            <div class="col-xl-2 col-md-4">--}}
{{--                                <a href="#"> <img src="assets/images/attached-files/img-2.jpg" alt="attachment" class="img-thumbnail img-fluid"> </a>--}}
{{--                            </div>--}}
{{--                            <div class="col-xl-2 col-md-4">--}}
{{--                                <a href="#"> <img src="assets/images/attached-files/img-3.png" alt="attachment" class="img-thumbnail img-fluid"> </a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div> <!-- card-box -->
                    <hr>
                    <div class="ml-3">
                        @foreach($replys as $item)
                        <div class="media mb-4">
                            <img class="d-flex mr-3 rounded-circle avatar-sm" src="{{$item->user->gravatar}}" alt="Generic placeholder image">
                            <div class="media-body">
                                <span class="float-right">{{$item->created_at->diffForHumans()}}</span>
                                <h5 class="m-0">{{$item->user->name ?? $item->user->account}}</h5>
                                <small class="text-muted">To: {{$item->user->email}}</small><br>
                                <small class="text-muted">Phone: {{$item->user->phone}}</small>
                            </div>
                        </div>
                        <p>{!! $item->note !!}</p>
                        <hr/>
                        @endforeach
                    </div>
                    @if($contact->email)
                    <form method="post" action="{{route('admin.contacts.update',$contact)}}">
                        @csrf
                        @method('PUT')
                        <div class="media mb-0 mt-5">
                            <img class="d-flex mr-3 rounded-circle avatar-sm" src="{{auth()->user()->gravatar}}" alt="Generic placeholder image">
                            <div class="media-body">
                                <div class="card-box reply-box">
                                    <p>* Nội dung email không được bỏ trống!</p>
                                    <textarea class="form-control" id="note" name="data[note]" required>{!! old('data.note') !!}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <a href="{{route('admin.contacts.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                            @if(setting()->email)
                            <button type="submit" class="btn btn-primary waves-effect waves-light"><span class="icon-button"><i class="fe-edit-1"></i></span> Trả lời</button>
                            @else
                                <a href="{{route('admin.settings')}}" class="btn btn-primary waves-effect waves-light"><span class="icon-button"><i class="fe-edit-1"></i></span> Email nhận thông tin trống!</a>
                            @endif
                        </div>
                    </form>
                    @else
                        <div class="text-right">
                            <a href="{{route('admin.contacts.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                        </div>
                    @endif
                    <div class="clearfix"></div>
                </div>

            </div> <!-- end Col -->

        </div><!-- End row -->
    </div>
    <script>
        CKEDITOR.replace('note',{
            height: 250
        })
    </script>
@stop

