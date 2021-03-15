@extends('Layouts.layout')
@section('title') {{optional($setting)->name}} @stop
@section('site_name') {{optional($setting)->name}} @stop
@section('url') {{route('home')}} @stop
@section('description') {!!optional($setting)->description_seo!!} @stop
@section('keywords'){!!optional($setting)->keyword_seo!!}@stop
@section('image') {{asset(optional($setting)->og_image ?? optional($setting)->logo)}} @stop
@section('content')
{{redirect_lang()}}
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@auth
    <a href="{{route('user.logout')}}">Đăng xuất</a>
@endauth

<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
