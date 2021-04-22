@extends('Admin.Layout.layout')
@section('title')
    Cập nhật nội dung #ID{{$gallery->id}}
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
                            <li class="breadcrumb-item"><a href="{{route('admin.products.galleries.index')}}">Album ảnh</a></li>
                            <li class="breadcrumb-item active">Cập nhật nội dung #ID{{$gallery->id}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Cập nhật nội dung #ID{{$gallery->id}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
    </div>
    <div class="container" id="update-gallery">
        <form method="post" action="{{route('admin.products.update',$gallery)}}" enctype="multipart/form-data">
            <div class="row">
                @csrf
                @method('PATCH')
                <div class="col-lg-8">
                    <div class="card-box option-height-left">
                        <div class="form-group">
                            <label>Tiêu đề <span class="required">*</span></label>
                            <input type="text" class="form-control" value="{{$gallery->name}}" id="title" onkeyup="ChangeToSlug();" name="data[name]" required>
                        </div>

                        <div class="form-group mb-0">
                            <label>Mô tả</label>
                            <textarea class="form-control summernote" id="summernote" name="data[description]">{!! $gallery->description !!}</textarea>
                        </div>
                    </div>
                    <div class="card-box position-relative box-action-image">
                        <label>Hình ảnh</label>
                        <div class="position-absolute font-weight-normal text-primary" id="box-input" style="right:2.2rem;top:1.3rem">
                            <label class="item-input">
                                <input type="file" name="photo[]" id="choiseImage" class="d-none" v-on:change="uploadPhoto(event.target.files)" multiple> Chọn ảnh
                            </label>
                        </div>
                        <p class="font-13">* Định dạng ảnh jpg, jpeg, png, gif</p>
                        <div class="dropzone pl-2 pr-2 pb-1">
                            <div class="dz-message text-center needsclick mb-2" v-if="photos.length == 0" id="remove-label">
                                <label for="choiseImage" class="w-100 mb-0">
                                    <div class="icon-dropzone pt-2">
                                        <i class="h1 text-muted dripicons-cloud-upload"></i>
                                    </div>
                                    <span class="text-muted font-13">Sử dụng nút <strong>Chọn ảnh</strong> để thêm ảnh</span>
                                </label>
                            </div>
                            <ul class="d-inline-block image-holder pl-0 mb-0 w-100 ui-sortable" id="sortable" v-if="photos.length > 0">
                                <li v-for="item in photos" class="box-product-images" v-bind:id="item.id">
                                    <div class="item-image rounded position-relative">
                                        <div class="img-rounded"><img :src="asset + item.image" class="position-image-product"/></div>
                                        <div class="photo-hover-overlay rounded">
                                            <div class="box-hover-overlay">
                                                <a title="Xem hình ảnh" :data-image="asset + item.image" data-toggle="modal" data-target="#viewImage" class="tooltip-hover view-image text-white">
                                                    <i class="far fa-eye"></i>
                                                </a>
                                                <a class="tooltip-hover pl-2 text-white" v-on:click="getAlt(item.id)" data-target="#updateALT" data-toggle="modal" title="Sửa ALT">
                                                    ALT
                                                </a>
                                                <a class="tooltip-hover pl-2 text-white" v-on:click="removePhoto(item.id)" title="Xóa hình ảnh">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                        </div>
                    </div>

                    <div class="card-box">
                        <div class="d-flex mb-2">
                            <label class="font-weight-bold">Tối ưu SEO</label>
                            <a href="javascript:void(0)" onclick="changeSeo()" class="edit-seo">Chỉnh sửa SEO</a>
                        </div>

                        <p class="font-13">Thiết lập các thẻ mô tả giúp khách hàng dễ dàng tìm thấy trang trên công cụ tìm kiếm như Google.</p>
                        <div class="test-seo">
                            <div class="mb-1">
                                <a href="javascript:void(0)" class="title-seo">{{$gallery->title_seo}}</a>
                            </div>
                            <div class="url-seo">
                                <span class="alias-seo" id="alias_seo">{{route('alias',$gallery->alias)}}</span>
                            </div>
                            <div class="description-seo">
                                {{$gallery->description_seo}}
                            </div>
                        </div>

                        <div class="change-seo" id="change-seo">
                            <hr>
                            <div class="form-group">
                                <label>Tiêu đề trang</label>
                                <p class="font-13">* Giới hạn tối đa 70 ký tự</p>
                                <input type="text" maxlength="70" value="{{$gallery->title_seo}}" name="data[title_seo]" class="form-control" id="alloptions" />
                            </div>
                            <div class="form-group">
                                <label>Mô tả trang</label>
                                <p class="font-13">* Giới hạn tối đa 320 ký tự</p>
                                <textarea  class="form-control" rows="3" name="data[description_seo]" maxlength="320" id="alloptions">{{$gallery->description_seo}}</textarea>
                            </div>
                            <div class="form-group mb-0">
                                <label>Từ khóa</label>
                                <p class="font-13">* Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                                <input type="text" name="data[keyword_seo]" value="{{$gallery->keyword_seo}}" class="form-control"  data-role="tagsinput"/>
                            </div>
                            <div class="form-group mb-0">
                                <label>Đường dẫn <span class="required">*</span></label>
                                <div class="d-flex form-control">
                                    <span>{{route('home')}}/</span><input type="text" class="border-0 alias" id="alias" value="{{$gallery->alias}}" name="data[alias]" required><span>.html</span>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-4">
                    <div class="card-box">
                        <label class="font-15 mb-0">Trạng thái</label>
                        <hr>
                        <div class="checkbox">
                            <input id="checkbox_public" {{$gallery->public == 1 ? "checked" : ""}} type="checkbox" name="public">
                            <label for="checkbox_public">Hiển thị</label>
                        </div>

                        <div class="checkbox">
                            <input id="checkbox_status" {{$gallery->status == 1 ? "checked" : ""}} type="checkbox" name="status">
                            <label for="checkbox_status" class="mb-0">Nổi bật</label>
                        </div>
                    </div>
                    <div class="card-box d-none">
                        <label>Tab hình ảnh</label>
                        <select class="form-control" data-toggle="select2" name="category">
                            <option value="0">-----</option>
                        </select>
                    </div>

                    <div class="card-box">
                        <label class="w-100">Ngôn ngữ</label>
                        @foreach($langs as $lang)
                            <a href="{{route('admin.products.galleries.lang',[$lang->value,$gallery->id])}}" class="btn btn-primary waves-effect width-md waves-light mb-1"><span class="icon-button"><i class="fe-plus"></i> {{$lang->name}}</a>
                        @endforeach

                        @if($gallery->postLangsBefore)
                            @foreach($posts as $item)
                                <a href="{{route('admin.products.galleries.edit',$item->id)}}" class="btn btn-purple waves-effect waves-light mb-1"><span class="icon-button"><i class="fe-edit-2" aria-hidden="true"></i></span> {{$item->language->name}} #{{$item->id}}</a>
                            @endforeach
                        @endif

                    </div>

                    <div class="card-box">
                        <div class="form-group mb-0">
                            <label>Tags</label>
                            <p class="font-13">* Từ khóa được phân chia sau dấu phẩy <strong>","</strong></p>

                            <input type="text" name="tags" value="{{$gallery->tags ?? old('tags')}}" class="form-control"  data-role="tagsinput"/>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <a href="{{route('admin.products.galleries.index')}}" class="btn btn-default waves-effect waves-light"><span class="icon-button"><i class="fe-arrow-left"></i></span> Quay lại</a>
                    <button type="submit" class="btn btn-primary waves-effect width-md waves-light float-right" name="send" value="update"><span class="icon-button"><i class="fe-plus"></i></span> Lưu lại</button>
                </div>
            </div>
            <!-- end row -->
        </form>
        <div id="updateALT" class="updateALT modal fade" tabindex="-1" aria-labelledby="myLargeModalLabel" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 600px" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title font-weight-normal">Chỉnh sửa mô tả (ALT) của hình ảnh</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <img v-bind:src="photo.image" class="rounded img-fluid">
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Mô tả hình ảnh (ALT)</label>
                                    <input type="text" v-model="photo.name" placeholder="Hãy nhập alt text của hình ảnh" required class="form-control">
                                </div>
                                <p class="font-13">Nếu hình ảnh không thể hiển thị vì bất kỳ lý do gì,ALT sẽ được hiển thị.ALT nên ngắn gọn nhưng súc tích.</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"> Đóng</button>
                        <button type="submit" class="btn btn-primary waves-effect" v-on:click="updateAlt(photo.id, photo.name)"> Cập nhật</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="viewImage" class="modal fade" tabindex="-1" aria-labelledby="myLargeModalLabel" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <img src="" class="img-fluid showImage">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"> Đóng</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>

@stop

@section('javascript')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{asset('admin/js/dynamicrows/dynamicrows.js')}}"></script>

    <script src="{{asset('admin/assets/libs/switchery/switchery.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}"></script>
    <script src="https://coderthemes.com/adminox/layouts/vertical/assets/libs/select2/select2.min.js"></script>
    <script src="{{asset('admin/assets/libs/autocomplete/jquery.autocomplete.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{asset('admin/assets/libs/bootstrap-filestyle2/bootstrap-filestyle.min.js')}}"></script>

    <!-- Init js-->
    <script src="{{asset('admin/assets/js/pages/form-advanced.init.js')}}"></script>
    <script src="/admin/assets/libs/tooltipster/tooltipster.bundle.min.js"></script>
    <script>
        $(document).on('click','.view-image',function(){
            let image = $(this).attr('data-image');
            $('.showImage').attr('src', image);
        })
    </script>

    <script>
        const asset = "{{asset('/')}}";
        var app = new Vue({
            el:'#update-gallery',
            mounted:function(){
                var vm = this;
                var temp = [];
                $('#sortable').sortable({
                    update: function(event, ui)
                    {
                        $('.box-product-images').each(function(i) {
                            var id = $(this).attr('id');
                            temp.push(id);
                        });
                        vm.updatePosition(JSON.stringify(temp));
                        return temp = [];
                    },
                });
            },
            data: {
                id: {{$gallery->id}},
                type: '{{\App\Enums\MediaType::GALLERY}}',
                photos: @json($photos),
                photo: {
                    id: null,
                    image: null,
                    name: null,
                },
            },
            methods:{
                removePhoto:function(id){
                    if(confirm('Xóa hình ảnh?')){
                        fetch('{{route('admin.ajax.remove.photo',':id')}}'.replace(':id',id)).then(function(res){
                            return res.json().then(function(data){
                                 app.photos = data;
                                flash({'message': 'Xóa hình ảnh thành công', 'type': 'success'});
                            })
                        })
                    }
                },
                uploadPhoto:function(files){
                    this.files = files;
                    var formData = new FormData();
                    for( var i = 0; i < this.files.length; i++ ){
                        var file = this.files[i];
                        formData.append('files[' + i + ']', file);
                    }
                    axios.post( '{{route('admin.ajax.upload.photo',[':id',':type'])}}'.replace(':id',this.id).replace(':type',this.type),formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(function(res){
                        app.photos = res.data;
                        flash({'message': 'Upload hình ảnh thành công', 'type': 'success'});
                        //console.log('SUCCESS!!');
                    })
                        .catch(function(){
                            console.log('FAILURE!!');
                        });

                },
                updatePosition:function(json){
                    fetch('{{route('admin.ajax.set.position.photo',[':json'])}}'.replace(':json', json)).then(function(response){
                        return response.json().then(function(data){

                        })
                    })
                },
                updateAlt:function(id, alt){
                    fetch('{{route('admin.ajax.set.alt',[':id',':alt'])}}'.replace(':id', id).replace(':alt',alt)).then(function(response){
                        return response.json().then(function(data){
                            $('.updateALT').modal('hide');
                            flash({'message': 'Cập nhật ALT thành công!', 'type': 'success'});
                        })
                    })
                },
                getAlt:function(id){
                    fetch('{{route('admin.ajax.get.alt',':id')}}'.replace(':id',id)).then(function(response){
                        return response.json().then(function(data){
                            app.photo.image = data.image;
                            app.photo.name = data.name;
                            app.photo.id = data.id;
                        })
                    })
                },
            }
        })
    </script>
    <script>
        CKEDITOR.replace('summernote',{
            height:150,
        })
    </script>
@stop

@section('css')
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link href="{{asset('admin/assets/libs/switchery/switchery.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.css')}}" rel="stylesheet" type="text/css" />

    <link href="/admin/assets/libs/tooltipster/tooltipster.bundle.min.css" rel="stylesheet" type="text/css" >
@stop
