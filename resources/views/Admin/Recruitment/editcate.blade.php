@extends('admin.layout.layout')
@section('title')
Sửa Danh mục
@stop
@section('content')
<div class="x_panel">
  <div class="x_content">
    <div class="row">
      <form class="form-horizontal form-label-left tform" method="post" enctype="multipart/form-data">
        <div class="col-md-8 col-xs-12">
          <div class="x_panel">
            <div class="x_content">
              <span class="section">Sửa dữ liệu với id: #{{$get_old->id}}</span>
              <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="name">Tên danh mục<span class="required">*</span> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Nhập tên danh mục (* required không được bỏ trống)</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <input id="name" class="form-control col-md-7 col-xs-12 input" data-validate-length-range="6" data-validate-words="2" name="title" required="required" type="text" value="{{$get_old->title}}">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="category">Danh mục cha </label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Gồm các danh mục sản phẩm đã được thêm trước vào. Chọn danh mục làm tập con của các danh mục đã được thêm trước.</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <select id="parent_id" name="parent_id" class="form-control">
                    <option value="0">Chọn danh mục</option>
                    @foreach($cate_recruitment as $items)
                    @if($items->parent_id == 0 && $items->id>1 && $items->id != $get_old->id)
                    <option value="{{$items->id}}" @if($get_old->parent_id == $items->id) selected @endif>{{$items->title}}</option>
                    {{Helper::sub_add_cate($cate_recruitment,$items->id,$get_old->parent_id)}}
                    @endif
                    @endforeach
                  </select>
                </div>
              </div>
              
              <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="alias">Đường dẫn </label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Mặc định "đường dẫn sẽ được lấy theo tên danh mục" ví dụ: Tên danh mục: "tin tức" -> Đường dẫn: "tin-tuc.html" hoặc có thể tự nhập đường dẫn theo nhu cầu sử dụng.</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <input id="alias" class="form-control col-md-7 col-xs-12 output" name="alias" type="text" value="{{$get_old->alias}}">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="alias">Tiêu đề mô tả</label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Dạng text</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <input id="alias" class="form-control col-md-7 col-xs-1" name="text" type="text" value="{{$get_old->text}}">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="alias">Mô tả ngắn</label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Dạng text</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <input id="alias" class="form-control col-md-7 col-xs-12" name="slogan" type="text" value="{{$get_old->slogan}}">
                </div>
              </div>
              <div class="item form-group">
                <label for="password" class="control-label col-md-12 col-xs-12 text__alignleft">Mô tả</label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Mô tả về bài viết (Không giới hạn ký tự)</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <textarea name="description" id="description" rows="7" class="form-control">{{@$get_old->description}}</textarea>
                </div>
                <script type="text/javascript">CKEDITOR.replace('description')</script>
              </div>
              <div class="item form-group">
                @include('admin.seo.seo')
              </div>
              <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="description_seo">Title Seo </label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Title SEO  - Mặc định nếu để trống sẽ được lấy theo tên danh mục.</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <input type="text" id="title_seo" name="title_seo" class="form-control col-md-7 col-xs-12" value="{{$get_old->title_seo}}">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="description_seo">Description Seo </label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Description SEO  - Mặc định nếu để trống sẽ được lấy theo tên danh mục.</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <input type="text" id="description_seo" name="description_seo" class="form-control col-md-7 col-xs-12" value="{{$get_old->description_seo}}">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="keyword_seo">Keyword Seo </label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Keyword SEO  - Mặc định nếu để trống sẽ được lấy theo tên danh mục.</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <input type="text" id="keyword_seo" name="keyword_seo" class="form-control col-md-7 col-xs-12" value="{{$get_old->keyword_seo}}">
                </div>
              </div>
              <div class="clear"></div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="text-center">
                 <a href="{{url('admin/list-recruitment-category')}}" class="btn btn-primary"><span class="icon-button"><i class="fa fa-undo" aria-hidden="true"></i></span> Quay lại</a>
                 <button id="submit" name="submit" type="submit" class="btn btn-success"/><span class="icon-button"><i class="fa fa-refresh"></i> Cập nhật</button>
                 </div>
               </div>
             </div>
           </div>

         </div>
         <div class="col-md-4 col-xs-12">
          <div class="x_panel">
            <div class="x_content">
              <span class="section">Danh mục nổi bật</span>
              <div class="item">
                <select name="hot" class="form-control">
                  <option value="0" @if($get_old->hot == 0) selected @endif>No Active</option>
                  <option value="1" @if($get_old->hot == 1) selected @endif>Active</option>
                </select>
              </div>
            </div>
          </div>
          <div class="x_panel">
            <div class="x_content">
              <span class="section">Ngôn ngữ</span>
              <div class="item">
                <?php $getLang = DB::table('lang')->select('*')->where('value','<>',$get_old->lang)->get();
                $type = 'page';
                ?>
                @foreach($getLang as $item)
                @if(Helper::check_post_lang($get_old->id,$type,$item->value))
                <a href="{{url('admin')}}/edit-cate-recruitment/{{Helper::get_post_lang($get_old->id,$type,$item->value)}}" class="btn btn-warning"><span class="icon-button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> {{$item->name}}</a>
                @else
                <a href="{{url('admin')}}/add-cate-recruitment/{{$item->value}}/{{$get_old->id}}" class="btn btn-primary"><span class="icon-button"><i class="fa fa-plus-circle"></i></span> {{$item->name}}</a>
                @endif
                @endforeach
              </div>
            </div>
          </div>
          <div class="x_panel">
            <div class="x_content">
              <span class="section">Ảnh mô tả</span>
              <div class="item form-group">
                <input type="file" name="image" id="fileUpload" class="inputfile inputfile-6" data-multiple-caption="{count} files selected"/>
                <label for="file-7"><span><i>(* jpg, jpeg, png, gif)</i></span><strong class="btn btn-default"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> Upload image</strong></label>
                <div class="loading" style="display: none;"><img src="{{url('public/images/loading.gif')}}" style="width:100%;"></div>
                @if($get_old->thumb !="")<div class="show-img" id="image-holder"><img src="{{url($get_old->thumb)}}" class="img-thumbnail"></div> @else <div class="show-img" id="image-holder"></div> @endif
              </div>
              <div class="box-position @if($get_old->image == "")show-box @endif"">
                <div class="item">
                  <label><input type="checkbox" name="unlink" class="input-checked" id="unlink-image"> Xóa ảnh</label>
                </div>
              </div>
              <!-- end image -->
            </div>
          </div>
          <div class="hidden-lg hidden-sm hidden-md">
            <div class="ln_solid"></div>
            <div class="text-center">
             <a href="{{url('admin/list-recruitment-category')}}" class="btn btn-primary"><span class="icon-button"><i class="fa fa-undo" aria-hidden="true"></i></span> Quay lại</a>
             <button id="submit" name="submit" type="submit" class="btn btn-success"/><span class="icon-button"><i class="fa fa-refresh"></i> Cập nhật</button>
             </div>
           </div>
         </div>
         {{csrf_field()}}
       </form>
     </div>
   </div>
 </div>
 @stop

