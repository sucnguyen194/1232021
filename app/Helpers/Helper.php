<?php

use App\Models\Photo;
use App\Models\Tags;
use Illuminate\Support\Facades\Auth;
use App\Models\PostLang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if(!function_exists('send_mail')){
    function send_email($template, $data){
        $setting = setting();
       return Mail::send($template,$data,function($msg) use ($setting){
            $msg->from(env('MAIL_USERNAME'),'Thông báo');
            $msg->to($setting->email)->subject($setting->name);
        });
    }
}
if (!function_exists('setting')){
    function setting(){
        return  session()->get('setting');
    }
}

if(!function_exists('create_tags')){
    function create_tags($data, $type = null){
        $type = $data->type ?? $type;
        $tag = null;
        foreach(explode(',', $data->tags) as $items){
            $alias = Str::slug($items, '-');
            if(!Tags::whereAlias($alias)->whereType($type)->whereTypeId($data->id)->count()){
                $tag = new Tags();
                $tag->name = $items;
                $tag->alias = $alias;
                $tag->type = $type;
                $tag->type_id = $data->id;
                $tag->lang = $data->lang;
                $tag->save();
            }
        }
        return $tag;
    }
}
if (!function_exists('upload_multiple_image')){
    function upload_multiple_image($data, $check, $files , $width = null , $height = null){
        $sort = 0;

        if($data->photos){
            $sort = $data->photos()->count();
        }
        $count = count($files);
        for ($i = 0; $i < $count; $i++) {
            $file = $files[$i];
            $name = $file->getClientOriginalName();
            if(in_array($name, $check)){
                $file->store('photo');
                $image = "storage/".$file->hashName('photo');
                $thumb = null;
                if($width && $height){
                    $path = $file->hashName('photo/thumb');
                    $resizeThumb = Image::make($file);
                    $resizeThumb->fit(375, 375, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    Storage::put($path, (string) $resizeThumb->encode());
                    $thumb = "storage/".$path;
                }
                if($data->photos){
                    if($check[0] == $name){
                        $data->update([
                            'image' => $image,
                            'thumb' => $thumb
                        ]);
                    }
                }
                Photo::create([
                    'name' => $data->name,
                    'image' => $image,
                    'thumb' => $thumb,
                    'type_id' => $data->id,
                    'type' => $data->type,
                    'user_id' => \Auth::id(),
                    'public' => 1,
                    'sort' => $i+$sort,
                    'lang' => $data->lang
                ]);
            }
        }

        return $data;
    }
}

if(!function_exists('upload_file_image')){
    function upload_file_image($data,$file, $width = null , $height = null, $colum = null){
        $file->store('photo');
        $colum = is_null($colum) ? \App\Enums\Upload::image : $colum;
        $data->$colum = "storage/".$file->hashName('photo');
        if($width && $height){
            $path = $file->hashName('photo/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());
            $data->thumb = "storage/".$path;
        }
        return $data;
    }
}

if (! function_exists('nav_active')) {

    function nav_active($segment, $class='active')
    {
        $segment = str_replace(['index','create'],['*'],$segment);

        return request()->routeIs($segment) ? $class : '';
    }
}

if (! function_exists('date_range')) {

    function date_range($format_in = 'd/m/Y')
    {
        if (!request()->date)
            return null;

        $parts = explode('-', str_replace(' ', '', request()->date));
        if (!$parts){
            return null;
        }

        $range['from'] = \Carbon\Carbon::createFromFormat($format_in, $parts[0]);
        $range['to'] = \Carbon\Carbon::createFromFormat($format_in, $parts[1]);
        return $range;
    }
}

if(!function_exists('check_admin_systems')){
    function check_admin_systems($type){

        if(!auth()->check())
            return abort(403);

        if(Auth::user()->lever == \App\Enums\LeverUser::SUPPERADMIN)
            return true;
        $id = \App\Models\System::whereType($type)->firstOrFail()->id;
        if(\auth()->user()->systems()->whereId($id)->exists())
            return true;

        return abort(404);
    }
}

if (! function_exists('flash')) {

    function flash($message= null,$type=1,$url=null,$target='_self')
    {
        $types=[
            0=>'error',
            1=>'success',
            2=>'info',
            3=>'warning',
        ];
        $m['type']=$types[$type];
        $m['message']=$message;
        $m['url']=$url;
        $m['target']=$target;

        session()->flash('message',$m);

        if($url)
            return redirect($url);

        return back()->withInput();
    }
}

if(!function_exists('list_categories')){
    function list_categories($data){
        foreach($data->where('parent_id',0) as $items){

                $status = $items->status == 1 ? "checked" : "";
                $public = $items->public == 1 ? "checked" : "";

                $sort= '<input style="width:120px;" class="form-control" type="text" name="sort" data-id="'.$items->id.'" value="'.$items->sort.'"><span id="change-sort-success_'.$items->id.'" class="change-sort"></span>';
                $display = '<div class="checkbox">';
                $display .= '<input id="checkbox_public_'.$items->id.'" id="public"  '.$public.' type="checkbox" name="public">';
                $display .= '<label for="checkbox_public_'.$items->id.'" class="data_public" data-id="'.$items->id.'">Hiển thị</label>';
                $display .= '</div>';
                $display .= '<div class="checkbox">';
                $display .= '<input id="checkbox_status_'.$items->id.'" id="status" '.$status.' type="checkbox" name="status">';
                $display .= '<label for="checkbox_status_'.$items->id.'" class="mb-0 data_status" data-id="'.$items->id.'">Nổi bật</label>';
                $display .= '</div>';

                $action = '<a href="'.$items->url.'" title="Sửa" class="btn btn-primary waves-effect waves-light">
                                        <span class="icon-button"><i class="fe-edit-2"></i></span></a> ';
                $action .= '<a href="'.route('admin.categories.remove',$items->id).' " title="Xóa" onclick="return confirm(\'Bạn chắc chắn muốn xóa!\')" class="btn btn-warning waves-effect waves-light"><span class="icon-button"><i class="fe-x"></i></span> </a>';
                echo '<tr><td>';
                echo '<div class="checkbox">';
                echo '<input id="checkbox_del_'.$items->id.'" class="check_del"  value="'.$items->id.'"  type="checkbox" name="check_del[]">';
                echo '<label for="checkbox_del_'.$items->id.'"></label></div></td>';
                echo '<td>'.$items->id.'</td>';
				echo '<td class="position-relative">'.$sort.'</td>';
				echo '<td><a href="'.route('alias',$items->alias).'" target="_blank">'.$items->name.'</a></td>';
				echo '<td></td>';
				echo '<td>'.$items->updated_at->diffForHumans().'</td>';
				echo '<td>'.$display.'</td>';
				echo '<td>'.$action.'</td>';
				echo '</tr>';

                sub_list_categories($data, $items->id);

        }

    }
}

if(!function_exists('sub_list_news_category')){

    function sub_list_categories($data,$parent_id,$user=0,$tab = '&nbsp;&nbsp;&nbsp;&nbsp;'){

        foreach($data->where('parent_id', $parent_id) as $items){

                $status = $items->status == 1 ? "checked" : "";
                $public = $items->public == 1 ? "checked" : "";

                $sort= '<input style="width:120px;" class="form-control" type="text" name="sort" data-id="'.$items->id.'" value="'.$items->sort.'"><span id="change-sort-success_'.$items->id.'" class="change-sort"></span>';

                $display = '<div class="checkbox">';
                $display .= '<input id="checkbox_public_'.$items->id.'" id="public"  '.$public.' type="checkbox" name="public">';
                $display .= '<label for="checkbox_public_'.$items->id.'" class="data_public" data-id="'.$items->id.'">Hiển thị</label>';
                $display .= '</div>';
                $display .= '<div class="checkbox">';
                $display .= '<input id="checkbox_status_'.$items->id.'" id="status" '.$status.' type="checkbox" name="status">';
                $display .= '<label for="checkbox_status_'.$items->id.'" class="mb-0 data_status" data-id="'.$items->id.'">Nổi bật</label>';
                $display .= '</div>';

                $action = '<a href="'.$items->url.'" title="Sửa" class="btn btn-primary waves-effect waves-light">
                                       <span class="icon-button"><i class="fe-edit-2"></i></span></a> ';
                $action .= '<a href="'.route('admin.categories.remove',$items->id).' " title="Xóa" onclick="return confirm(\'Bạn chắc chắn muốn xóa!\')" class="btn btn-warning waves-effect waves-light"><span class="icon-button"><i class="fe-x"></i></span> </a>';

                echo '<tr><td>';
                echo '<div class="checkbox">';
                echo '<input id="checkbox_del_'.$items->id.'" class="check_del"  value="'.$items->id.'"  type="checkbox" name="check_del[]">';
                echo '<label for="checkbox_del_'.$items->id.'"></label></div></td>';
                echo '<td>'.$items->id.'</td>';
				echo '<td class="position-relative">'.$sort.'</td>';
				echo '<td><a href="'.route('alias',$items->alias).'" target="_blank">'.$tab.'<span class="tree-sub"></span>'.$items->name.'</a></td>';
                echo '<td><a href="'.route('alias',$items->parent->alias).'" target="_blank">'.$items->parent->name.'</a></td>';
				echo '<td>'.$items->updated_at->diffForHumans().'</td>';
				echo '<td>'.$display.'</td>';
				echo '<td>'.$action.'</td>';
				echo '</tr>';

            sub_list_categories($data,$items->id,$user,$tab."&nbsp;&nbsp;&nbsp;&nbsp;");

        }
    }
}

if(!function_exists('selected')){
    function selected($a,$b){
        if($a == $b)
            return "selected";
        if(is_array($b) && in_array($a,$b))
            return "selected";
        return "";
    }
}

if(!function_exists('checked')){
    function checked($a,$b){
        if($a == $b)
            return "checked";
        if(is_array($b) && in_array($a,$b))
            return "checked";
        return "";
    }
}

if(!function_exists('sub_option_category')){
    function sub_option_category($data,$parent,$old = null){
        foreach ($data->where('parent_id', $parent) as $key => $value) {
            $name = $value->name ?? $value->title;
            $selected = null;

            if(optional($old)->categories){
                $selected = selected($value->id,$old->categories->pluck('id')->toArray());
            }elseif ($old == $value->id){
                $selected = 'selected';
            }

            echo '<option value="'.$value->id.'" '.$selected.'>'.$name.'</option>';
            sub_option_category($data,$value->id,$old);
        }
    }
}

if(!function_exists('str_limit')){
    function str_limit($content, $limit=50){
        return strip_tags(\Illuminate\Support\Str::limit($content, $limit));
    }
}

if(!function_exists('redirect_lang')){
    function redirect_lang($alias = 'HOME'){
        echo '<input type="hidden" class="vue-alias" value="'.$alias.'">';
    }
}

//Scan file
if(!function_exists('scan_full_dir')){
    function scan_full_dir($dir,$child = false){
        $icon = ['/','_'];
        $dir_content_list = scandir($dir);
        foreach($dir_content_list as $k=>$value){
            $path = $dir.$icon[0].$value;
            $arr = [
                '.',
                '..',
                'Admin',
                'Auth',
                'Console',
                'Events',
                'Commands',
                'Services',
                'Handlers',
                'Exceptions',
                'Providers',
                'Middleware',
                'Requests',
                'Kernel.php',
                'route.php',
                'fonts',
                'font',
                'font-awesome',
            ];
            if(in_array($value,$arr))  {continue;}
            $explode = explode('.',$value);
            $replace = str_replace(array('/','.'),array('_',''), $dir);
            $ext = 'php';
            $event = null;
            $image = "https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-folder.png?377a0415c8e86b629f04f2de969b6dc7";

            if(in_array('html',$explode) && $child){
                $ext = "html";
                $image ="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-webscript.png?b2aff14c14b05cccbb316c37d48fb591" ;
                $event = "id='show-file'";
            }
            if(in_array('css',$explode) && $child){
                $ext = "css";
                $image ="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-webscript.png?b2aff14c14b05cccbb316c37d48fb591";
                $event = "id='show-file'";
            }
            if(in_array('scss',$explode) && $child){
                $ext = "css";
                $image ="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-webscript.png?b2aff14c14b05cccbb316c37d48fb591";
                $event = "id='show-file'";
            }
            if(in_array('php',$explode) && $child){
                $ext = "php";
                $image ="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-webscript.png?b2aff14c14b05cccbb316c37d48fb591";
                $event = "id='show-file'";
            }
            if(in_array('js',$explode) && $child){
                $ext = "js";
                $image ="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-webscript.png?b2aff14c14b05cccbb316c37d48fb591";
                $event = "id='show-file'";
            }
            if(in_array('jpg',$explode) && $child){
                $ext = "image";
                $image ="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-image.png?1327e17a096bce2f49ad2f66f4abdaf6";
            }
            if(in_array('jpeg',$explode) && $child){
                $ext = "image";
                $image ="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-image.png?1327e17a096bce2f49ad2f66f4abdaf6";
            }
            if(in_array('png',$explode) && $child){
                $ext = "image";
                $image ="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-image.png?1327e17a096bce2f49ad2f66f4abdaf6";
            }
            if(in_array('svg',$explode) && $child){
                $ext = "image";
                $image ="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-image.png?1327e17a096bce2f49ad2f66f4abdaf6";
            }
            if(in_array('gif',$explode) && $child){
                $ext = "image";
                $image ="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-image.png?1327e17a096bce2f49ad2f66f4abdaf6";
            }
            if(in_array('ico',$explode) && $child){
                $ext = "image";
                $image ="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-image.png?1327e17a096bce2f49ad2f66f4abdaf6";
            }

            if(in_array('htaccess',$explode) && $child){
                $ext = "htaccess";
                $image ="https://s2d142.cloudnetwork.vn:8443/cp/theme/icons/16/plesk/file-private.png?b3e618929415e17caa82f8d04d2aa689";
            }

            // check if we have file
            if(is_file($path)) {
                echo '<li class="file-name text-primary" '.$event.' data-path="'.$path.'" data-ext="'.$ext.'"><i class="icon-img"><img src="'.$image.'"></i> '.$value.'</li>';
                continue;
            }
            // check if we have directory
            if (is_dir($path)) {
                if(!$child){
                    echo '<li class="folder-name"><a href="javascript:void(0)" id="open-folder" class="open-folder text-primary" data-path="'.$replace.$icon[1].$value.$icon[1].$k.'"><i class="icon-img"><img src="'.$image.'"></i> '.$value.'</a>';
                    echo '<ul class="parent-folder" id="'.$replace.$icon[1].$value.$icon[1].$k.'">';
                    scan_full_dir($dir.$icon[0].$value,$value);
                    echo '</ul>';
                    echo '</li>';
                }else{
                    echo '<li class="folder-sub"><a href="javascript:void(0)" id="open-sub-folder" class="open-sub-folder text-primary" data-path="'.$replace.$icon[1].$value.$icon[1].$k.'"><i class="icon-img"><img src="'.$image.'"></i> '.$value.'</a>';
                    echo '<ul class="parent-folder" id="'.$replace.$icon[1].$value.$icon[1].$k.'">';
                    scan_full_dir($dir.$icon[0].$value,$value);
                    echo '</ul>';
                    echo '</li>';
                }
            }
        }
    }
}

//Menu
if(!function_exists('sub_menu_category_checkbox')){
    function sub_menu_category_checkbox($data,$parent,$tab='<span class="ml-2"></span>'){
        foreach ($data->where('parent_id', $parent) as $key => $value) {
            $name = $value->title ?? $value->name;
           echo $tab.'<label><span class="tree-sub"></span><a href="javascript:void(0)" class="addmenu text-secondary" title="Thêm ::::::'.$name.':::::: vào menu" data-name="'.$name.'" data-url="'.$value->alias.'" data-image="'.$value->image.'" data-thumb="'.$value->thumb.'"><span class="text-left"><i class="fe-plus pr-1"></i></span>'.$name.'</a></label><br>';
            sub_menu_category_checkbox($data, $value->id,$tab.'<span class="ml-2"></span>');
        }
    }
}

if(!function_exists('menu_update_position')){
    function menu_update_position($menu,$parent_id=0){
        foreach($menu as $key => $items){
            $update = \App\Models\Menu::find($items->id);
            $update->update(['sort' => $key,'parent_id' => $parent_id]);

            if(isset($items->children)){
                menu_update_position($items->children,$items->id);
            }
        }
    }
}

if(!function_exists('sub_add_menu')){

    function sub_add_menu($data,$id,$old=null){
        foreach($data->where('parent_id', $id) as $item):
            $select = null;
            if($old == $item->id){
                $select = "selected";
            }
            echo '<option value="'.$item->id.'"'.$select.'>'.$item->name.'</option>';

            sub_add_menu($data, $item->id);
        endforeach;
    }
}

if(!function_exists('admin_menu_sub')){
    function admin_menu_sub($data,$parent_id){

        foreach($data->where('parent_id', $parent_id) as $items){

            $icon = '<i class="fa fa-star" aria-hidden="true"></i>';

            echo '<li class="dd-item" data-id="'.$items->id.'">';

            echo '<div class="dd-handle"><span class="pr-1">'.$icon.'</span> '.$items->name.'</div>';

            echo '<div class="menu_action">';
            echo '<a href="'.route('admin.menus.edit',$items).'" title="Sửa" class="btn btn-primary waves-effect waves-light"><i class="fe-edit-2"></i></a> ';
            echo '<form method="post" action="'.route('admin.menus.destroy',$items).'" class="d-inline-block">';
            echo '<input type="hidden" name="_method" value="DELETE">';
            echo '<input type="hidden" name="_token" value="'.csrf_token().'">';
            echo '<button type="submit" onclick="return confirm(\'Bạn chắc chắn muốn xóa?\')" class="btn btn-warning waves-effect waves-light"><i class="fe-x"></i></button>';
            echo '</form>';
            echo '</div>';

            echo '<ol class="dd-list">';
            admin_menu_sub($data,$items->id);;
            echo '</ol>';
            echo '</li>';

        }
    }
}

//Add post lang
if(!function_exists('add_post_lang')){

    function add_post_lang($id,$data,$data_old,$type,$lang){
        $postlang = PostLang::get();

        if($postlang->where('post_id', $id)->count()){

            foreach($postlang->where('post_id', $id) as $post){
                PostLang::create([
                    'post_id' => $data->id,
                    'post_lang_id' => $post->post_id,
                    'type' => $type,
                    'lang' => $post->lang
                ]);
                PostLang::create([
                    'post_id' => $post->post_id,
                    'post_lang_id' => $data->id,
                    'type' => $type,
                    'lang' => $data->lang
                ]);
            }
            foreach($postlang->where('post_lang_id', $id) as $posts){
                PostLang::create([
                    'post_id' => $data->id,
                    'post_lang_id' => $posts->post_id,
                    'type' => $type,
                    'lang' => $posts->lang
                ]);

                PostLang::create([
                    'post_id' => $posts->post_id,
                    'post_lang_id' => $data->id,
                    'type' => $type,
                    'lang' => $data->lang
                ]);
            }

        }else{
            PostLang::create([
                'post_id' => $data->id,
                'post_lang_id' => $id,
                'type' => $type,
                'lang' => $data_old->lang
            ]);

            PostLang::create([
                'post_id' =>  $id,
                'post_lang_id' =>$data->id,
                'type' => $type,
                'lang' => $lang
            ]);
        }
    }
}
