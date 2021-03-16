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
<script src="https://unpkg.com/file-upload-with-preview@4.1.0/dist/file-upload-with-preview.min.js"></script>
<link
    rel="stylesheet"
    type="text/css"
    href="https://unpkg.com/file-upload-with-preview@4.1.0/dist/file-upload-with-preview.min.css"
/>
<div class="custom-file-container" data-upload-id="myUniqueUploadId">
    <label
    >Upload File
        <a
            href="javascript:void(0)"
            class="custom-file-container__image-clear"
            title="Clear Image"
        >&times;</a
        ></label
    >
    <label class="custom-file-container__custom-file">
        <input
            type="file"
            class="custom-file-container__custom-file__custom-file-input"
            accept="*"
            multiple
            aria-label="Choose File"
        />
        <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
        <span
            class="custom-file-container__custom-file__custom-file-control"
        ></span>
    </label>
    <div class="custom-file-container__image-preview"></div>
</div>
<script>
    var upload = new FileUploadWithPreview("myUniqueUploadId");

    window.addEventListener("fileUploadWithPreview:imagesAdded", function (e) {
        // e.detail.uploadId
        // e.detail.cachedFileArray
        // e.detail.addedFilesCount
        // Use e.detail.uploadId to match up to your specific input
        if (e.detail.uploadId === "mySecondImage") {
            console.log(e.detail.cachedFileArray);
            console.log(e.detail.addedFilesCount);
        }
    });
</script>
<!-------------------------->
<!-----------SOURCSE----------->
<!-------------------------->
@stop
