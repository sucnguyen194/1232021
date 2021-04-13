@extends('Layouts.layout')
@section('title') {{$post->title_seo}} @stop
@section('url') {{route('alias',$post->alias)}} @stop
@section('site_name') {{$post->title_seo}} @stop
@section('description') {{$post->description_seo}} @stop
@section('keywords') {{$post->keyword_seo}} @stop
@section('image') {{asset($post->image)}} @stop
@section('lang') {{redirect_lang($post->alias)}} @stop
@section('content')

<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->

<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
