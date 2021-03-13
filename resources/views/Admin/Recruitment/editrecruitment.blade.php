@extends('admin.layout.layout')
@section('title') Sửa tin tuyển dụng
@stop
@section('content')
<div class="x_panel">
  <div class="x_content">
    <div class="row">
      @include('errors.note')
      <form class="form-horizontal form-label-left" method="post" enctype='multipart/form-data'>
        <div class="col-md-8 col-xs-12">
          <div class="x_panel">
            <div class="x_content">
              <span class="section">Sửa dữ liệu với id: #{{$get_old->id}}</span>
              <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="title">Vị trí tuyển dụng<span class="required">*</span> </label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Nhập tiêu đề bài viết (* required không được bỏ trống)</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <input id="title" class="form-control col-md-7 col-xs-12 input" data-validate-length-range="6" data-validate-words="2" name="title" required="required" type="text" value="{{@$get_old->title}}">
                </div>
              </div>
              <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="alias">Đường dẫn </label>
                <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Mặc định "đường dẫn sẽ được lấy theo tên bài viết" ví dụ: Tên bài viết: "tin tức" -> Đường dẫn: "tin-tuc.html" hoặc có thể tự nhập đường dẫn theo nhu cầu sử dụng.</i>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <input id="alias" class="form-control col-md-7 col-xs-12 output" name="alias" type="text" value="{{@$get_old->alias}}">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 col-xs-12">
                 <div class="item form-group">
                  <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="quantity">Số lượng<span class="required">*</span> </label>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <input id="quantity" class="form-control col-md-7 col-xs-12" data-validate-length-range="6" data-validate-words="2" name="quantity"  required="required" type="text" min="1" value="{{@$get_old->quantity}}">
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-xs-12">
               <div class="item form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="salary">Mức lương<span class="required">*</span> </label>
                <div class="col-md-12 col-sm-12 col-xs-12">

                  <input id="salary" name="salary" class="form-control" value="{{@$get_old->salary}}">
                </div>
              </div>
            </div>
            <div class="col-md-4 col-xs-12">
             <div class="item form-group">
              <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="salary">Thời gian làm việc</label>
              <div class="col-md-12 col-sm-12 col-xs-12">

                <input id="time_work" name="time_work" class="form-control" value="{{@$get_old->time_work}}">
              </div>
            </div>
          </div>
          <div class="col-md-4 col-xs-12">
           <div class="item form-group">
            <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="address">Nơi làm việc </label>
            <div class="col-md-12 col-sm-12 col-xs-12">
              <select name="address" class="form-control">
                <option value="0">Chọn nơi làm việc</option>
                <?php $location = DB::table('address')->select('*')->where([])->orderby('title','asc')->get(); ?>
                @foreach($location as $item)
                <option value="{{$item->id}}" @if(@$get_old->address == $item->id) selected @endif>{{$item->title}}</option>
                @endforeach
              </select>  
            </div>
          </div>
        </div>

        <div class="col-md-4 col-xs-12">
         <div class="item form-group">
          <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="address">Nhà thầu </label>
          <div class="col-md-12 col-sm-12 col-xs-12">
            <select name="partner" class="form-control">
              <option value="0">Chọn nhà thầu</option>
              <?php $partner = DB::table('image')->select('*')->where(['position'=>'Partner'])->orderby('sort','asc')->get(); ?>
              @foreach($partner as $item)
              <option value="{{$item->id}}" @if(@$get_old->partner == $item->id) selected @endif>{{$item->title}}</option>
              @endforeach
            </select>  
          </div>
        </div>
      </div>

      <div class="col-md-4 col-xs-12">
       <div class="item form-group">
        <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="address">Ngành nghề </label>
        <div class="col-md-12 col-sm-12 col-xs-12">
          <select name="jobs" class="form-control">
            <option value="0">Chọn ngành nghề</option>
            <?php $jobs = DB::table('jobs')->select('*')->where([])->orderby('title','asc')->get(); ?>
            @foreach($jobs as $item)
            <option value="{{$item->id}}" @if(@$get_old->jobs == $item->id) selected @endif>{{$item->title}}</option>
            @endforeach
          </select>  
        </div>
      </div>
    </div>
  </div>
  <div class="item form-group" style="display:none">
    <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="diploma">Yêu cầu bằng cấp<span class="required">*</span> </label>
    <div class="col-md-12 col-sm-12 col-xs-12">
      <select id="diploma" name="diploma" class="form-control">
       <option value="0" @if($get_old->diploma == 0) selected @endif>Tất cả</option>
       <option value="1" @if($get_old->diploma == 1) selected @endif>Trung học</option>
       <option value="2" @if($get_old->diploma == 2) selected @endif>Trung cấp</option>
       <option value="3" @if($get_old->diploma == 3) selected @endif>Cao đẳng</option>
       <option value="4" @if($get_old->diploma == 4) selected @endif>Đại học</option>
       <option value="5" @if($get_old->diploma == 5) selected @endif>Trên đại học</option>
     </select>
   </div>
 </div>
 <div class="item form-group" style="display:none">
  <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="experience">Kinh nghiệm<span class="required">*</span> </label>
  <div class="col-md-12 col-sm-12 col-xs-12">
    <select id="experience" name="experience" class="form-control">
      <option value="0" @if($get_old->experience == 0) selected @endif>Tất cả</option>
      <option value="1" @if($get_old->experience == 1) selected @endif>Dưới 1 năm</option>
      <option value="2" @if($get_old->experience == 2) selected @endif>1 năm</option>
      <option value="3" @if($get_old->experience == 3) selected @endif>2 năm</option>
      <option value="4" @if($get_old->experience == 4) selected @endif>5 năm</option>
      <option value="5" @if($get_old->experience == 5) selected @endif>Trên 5 năm</option>
    </select>
  </div>
</div>
<div class="item form-group" style="display:none">
  <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="profile">Yêu cầu hồ sơ</label>
  <div class="col-md-9 col-sm-9 col-xs-12">
    <textarea id="profile" name="profile" class="form-control">{{@$get_old->profile}}</textarea>
  </div>
</div>
<div class="item form-group" style="display:none">
  <label for="password" class="control-label col-md-12 col-xs-12 text__alignleft">Mô tả</label>
  <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Mô tả về bài viết (Không giới hạn ký tự)</i>
  </div>
  <div class="col-md-12 col-sm-12 col-xs-12">
    <textarea name="description" id="description" rows="7" class="form-control">{{@$get_old->description}}</textarea>
  </div>
</div>
<div class="item form-group" style="display:none">
  <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="benefit">Quyền lợi được hưởng</label>
  <div class="col-md-9 col-sm-9 col-xs-12">
    <textarea id="benefit" name="benefit" class="form-control">{{@$get_old->benefit}}</textarea>
    <script type="text/javascript">CKEDITOR.replace('benefit')</script>
  </div>
</div>
<div class="item form-group">
  <label for="password" class="control-label col-md-12 col-xs-12 text__alignleft">Chi tiết công việc</label>
  <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Chi tiết về bài viết (Không giới hạn ký tự)</i>
  </div>
  <div class="col-md-12 col-sm-12 col-xs-12">
    <textarea name="requirement" id="requirement" class="form-control">{{@$get_old->requirement}}</textarea>
  </div>
</div>
<script type="text/javascript">
  CKEDITOR.replace( 'requirement' );
</script>
<div class="item form-group">
  @include('admin.seo.seo')
</div>
<div class="item form-group">
  <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="description_seo">Title Seo </label>
  <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Title SEO  - Mặc định nếu để trống sẽ được lấy theo tên bài viết.</i>
  </div>
  <div class="col-md-12 col-sm-12 col-xs-12">
    <input type="text" id="title_seo" name="title_seo" class="form-control col-md-7 col-xs-12" value="{{@$get_old->title_seo}}">
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="description_seo">Description Seo </label>
  <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Description SEO  - Mặc định nếu để trống sẽ được lấy theo tên bài viết.</i>
  </div>
  <div class="col-md-12 col-sm-12 col-xs-12">
    <input type="text" id="description_seo" name="description_seo" class="form-control col-md-7 col-xs-12" value="{{@$get_old->description_seo}}">
  </div>
</div>
<div class="item form-group">
  <label class="control-label col-md-12 col-sm-12 col-xs-12 text__alignleft" for="keyword_seo">Keyword Seo </label>
  <div class="col-md-12 col-sm-12 col-xs-12 form-group"><i>* Keyword SEO  - Mặc định nếu để trống sẽ được lấy theo tên bài viết.</i>
  </div>
  <div class="col-md-12 col-sm-12 col-xs-12">
    <input type="text" id="keyword_seo" name="keyword_seo" class="form-control col-md-7 col-xs-12" value="{{@$get_old->keyword_seo}}">
  </div>
</div>
<div class="clearfix"></div>
<div class="ln_solid"></div>
<div class="form-group">
  <div class="text-center">
    <a href="{{url('admin/list-recruitment')}}" class="btn btn-primary"><span class="icon-button"><i class="fa fa-undo" aria-hidden="true"></i></span> Quay lại</a>
    <button id="submit" name="submit" type="submit" class="btn btn-success"/><span class="icon-button"><i class="fa fa-refresh"></i> Cập nhật</button>
    </div>
  </div>
</div>
</div>
</div>

<div class="col-md-4 col-xs-12">
  <div class="x_panel">
    <div class="x_content">
      <span class="section">Tin nổi bật</span>
      <div class="item">
        <select name="home" class="form-control">
          <option value="1" @if($get_old->home == 1) selected @endif>Active</option>
          <option value="0" @if($get_old->home == 0) selected @endif>No Active</option>
        </select>
      </div>
    </div>
  </div>
  <div class="x_panel" style="display: none">
    <div class="x_content">
      <span class="section">Tin HOT</span>
      <div class="item">
        <select name="hot" class="form-control">
          <option value="1" @if($get_old->hot == 1) selected @endif>Active</option>
          <option value="0" @if($get_old->hot == 0) selected @endif>No Active</option>
        </select>
      </div>
    </div>
  </div>
  <div class="x_panel" style="display:none">
    <div class="x_content">
      <span class="section">Tin nổi bật</span>
      <div class="item">
        <select name="focus" class="form-control">
          <option value="1" @if($get_old->focus == 1) selected @endif>Active</option>
          <option value="0" @if($get_old->focus == 0) selected @endif>No Active</option>
        </select>
      </div>
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
      <a href="{{url('admin')}}/edit-recruitment/{{Helper::get_post_lang($get_old->id,$type,$item->value)}}" class="btn btn-warning"><span class="icon-button"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> {{$item->name}}</a>
      @else
      <a href="{{url('admin')}}/add-recruitment/{{$item->value}}/{{$get_old->id}}" class="btn btn-primary"><span class="icon-button"><i class="fa fa-plus-circle"></i></span> {{$item->name}}</a>
      @endif
      @endforeach
    </div>
  </div>
</div>
<div class="x_panel">
  <div class="x_content">
    <span class="section">Hạn nộp hồ sơ</span>
    <div class='input-group date' id='myDatepicker4'>
      <input type='text' id="time_out" name="time_out" class="form-control col-md-7 col-xs-12" readonly="readonly"  required value="{{$get_old->time_out}}"/>
      <span class="input-group-addon">
       <span class="glyphicon glyphicon-calendar"></span>
     </span>
   </div>
 </div>
</div>
<div class="x_panel">
  <div class="x_content">
    <span class="section">Danh mục</span>
    <div class="item category_check">
     <?php foreach ($cate_recruitment as $key => $value): ?>
      <?php if ($value->parent_id == 0 && $value->id>1): ?>
        <label>
          <input type="checkbox" name="category_id[]" value="{{$value->id}}" class="flat" @if(Helper::check_recruitment_category($get_old->id,$value->id)) checked @endif> <span style="font-weight: 700"> {{$value->title}} </span></label><br>
          {{Helper::sub_recruitment_checkbox($cate_recruitment,$value->id,$get_old->id)}}
        <?php endif ?>
      <?php endforeach ?>
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
      <div class="show-img" id="image-holder">@if(file_exists($get_old->thumb)) <img src="{{url($get_old->thumb)}}" class="img-thumbnail"> @endif</div>
    </div>
    <div class="box-position @if($get_old->image == "")show-box @endif">
      <label><input type="checkbox" name="unlink" class="input-checked" id="unlink-image"> Xóa ảnh</label>
    </div>
  </div>
</div>

<div class="hidden-lg hidden-sm hidden-md">
  <div class="ln_solid"></div>
  <div class="text-center">
    <a href="{{url('admin/list-recruitment')}}" class="btn btn-primary"><span class="icon-button"><i class="fa fa-undo" aria-hidden="true"></i></span> Quay lại</a>
    <button id="submit" name="submit" type="submit" class="btn btn-success"/><span class="icon-button"><i class="fa fa-refresh"></i> Cập nhật</button>
    </div>
  </div>
</div>
{{csrf_field()}}
</form>
</div>
</div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    CKEDITOR.instances['content'].on( 'change', function( event ) {
      alert( e.getData() );
    });
  })
</script>
@stop