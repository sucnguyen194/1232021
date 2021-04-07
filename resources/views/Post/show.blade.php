@extends('Layouts.layout')
@section('title') {!!$news->title_seo!!} @stop
@section('url') {{url($news->alias)}} @stop
@section('site_name') {!!$news->title!!} @stop
@section('description') {!!$news->description_seo!!} @stop
@section('keywords') {!!$news->keyword_seo!!} @stop
@section('image') {{asset($news->image)}} @stop
@section('content')
{{redirect_lang($news->alias)}}

<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->

<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
