@extends('Layouts.layout')
@section('title') {{setting()->name}} @stop
@section('site_name') {{setting()->name}} @stop
@section('url') {{route('home')}} @stop
@section('description') {!!setting()->description_seo!!} @stop
@section('keywords'){!!setting()->keyword_seo!!}@stop
@section('image') {{asset(setting()->og_image ?? setting()->logo)}} @stop
@section('content')
{{redirect_lang()}}
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
{{asset('/')}}
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
