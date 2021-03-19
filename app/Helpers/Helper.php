<?php

use Illuminate\Support\Facades\Auth;
use App\Models\PostLang;

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

if(!function_exists('list_product_category')){
    function list_product_category($data){

        foreach($data as $items){
            if($items->parent_id == 0){
                $status = $items->status == 1 ? "checked" : "";
                $public = $items->public == 1 ? "checked" : "";

                $sort= '<input style="width:120px;" class="form-control" type="text" name="sort" data-id="'.$items->id.'" value="'.$items->sort.'"><span id="change-sort-success_'.$items->id.'" class="change-sort"></span>';

                $display = '<div class="checkbox checkbox-primary checkbox-circle">';
                $display .= '<input id="checkbox_public_'.$items->id.'" id="public"  '.$public.' type="checkbox" name="public">';
                $display .= '<label for="checkbox_public_'.$items->id.'" class="data_public" data-id="'.$items->id.'">Hiển thị</label>';
                $display .= '</div>';
                $display .= '<div class="checkbox checkbox-primary checkbox-circle">';
                $display .= '<input id="checkbox_status_'.$items->id.'" id="status" '.$status.' type="checkbox" name="status">';
                $display .= '<label for="checkbox_status_'.$items->id.'" class="mb-0 data_status" data-id="'.$items->id.'">Nổi bật</label>';
                $display .=  '</div>';

                $action = '<a href="'.route('admin.product_categorys.edit',$items).'" title="Sửa" class="btn btn-purple waves-effect waves-light mr-1">
                                        <span class="icon-button"><i class="fe-edit-2"></i></span></a>';
                $action .= '<a href="'.route('admin.product.category.del',$items->id).' " title="Xóa" onclick="return confirm(\'Bạn chắc chắn muốn xóa!\')" class="btn btn-warning waves-effect waves-light"><span class="icon-button"><i class="fe-x"></i></span> </a>
				';

                echo '<tr><td>';
                echo  '<div class="checkbox checkbox-primary checkbox-circle">';
                echo   '<input id="checkbox_del_'.$items->id.'" class="check_del"  value="'.$items->id.'"  type="checkbox" name="check_del[]">';
                echo   '<label for="checkbox_del_'.$items->id.'"></label></div></td>';
				echo '<td>'. $items->id.'</td>';
				echo '<td class="position-relative">'.$sort.'</td>';
				echo '<td><a href="'.route('alias',$items->alias).'" class="text-secondary" target="_blank">'.$items->name.'</a></td>';
				echo '<td></td>';
				echo '<td>'.$items->updated_at->diffForHumans().'</td>';
				echo '<td>'.$display.'</td>';
				echo '<td>'.$action.'</td>';
				echo '</tr>';

                sub_list_product_category($data, $items->id);
            }
        }

    }
}

if(!function_exists('sub_list_product_category')){
    function sub_list_product_category($data,$parent_id,$user=0,$tab = '&nbsp;&nbsp;&nbsp;&nbsp;'){
        foreach($data as $items){

            if($items->parent_id == $parent_id){

                $status = $items->status == 1 ? "checked" : "";
                $public = $items->public == 1 ? "checked" : "";

                $sort= '<input style="width:120px;" class="form-control" type="text" name="sort" data-id="'.$items->id.'" value="'.$items->sort.'"><span id="change-sort-success_'.$items->id.'" class="change-sort"></span>';
                $display = '<div class="checkbox checkbox-primary checkbox-circle">';
                $display .= '<input id="checkbox_public_'.$items->id.'" id="public"  '.$public.' type="checkbox" name="public">';
                $display .= '<label for="checkbox_public_'.$items->id.'" class="data_public" data-id="'.$items->id.'">Hiển thị</label>';
                $display .= '</div>';
                $display .= '<div class="checkbox checkbox-primary checkbox-circle">';
                $display .= '<input id="checkbox_status_'.$items->id.'" id="status" '.$status.' type="checkbox" name="status">';
                $display .= '<label for="checkbox_status_'.$items->id.'" class="mb-0 data_status" data-id="'.$items->id.'">Nổi bật</label>';
                $display .= '</div>';

                $action = '<a href="'.route('admin.product_categorys.edit',$items).'" title="Sửa" class="btn btn-purple waves-effect waves-light mr-1">';
                $action .= '<span class="icon-button"><i class="fe-edit-2"></i></span></a>';
                $action .= '<a href="'.route('admin.product.category.del',$items->id).' " title="Xóa" onclick="return confirm(\'Bạn chắc chắn muốn xóa!\')" class="btn btn-warning waves-effect waves-light"><span class="icon-button"><i class="fe-x"></i></span> </a>';

                echo '<tr><td>';
                echo '<div class="checkbox checkbox-primary checkbox-circle">';
                echo '<span style="display: none">.</span>';
                echo '<input id="checkbox_del_'.$items->id.'" class="check_del"  value="'.$items->id.'"  type="checkbox" name="check_del[]">';
                echo '<label for="checkbox_del_'.$items->id.'"></label></div></td>';
				echo '<td>'. $items->id.'</td>';
				echo '<td class="position-relative">'.$sort.'</td>';
				echo '<td><a href="'.route('alias',$items->alias).'" class="text-secondary" target="_blank">'.$tab.'<span class="tree-sub"></span>'.$items->name.'</a></td>';
				echo '<td>'.$items->parents->name.'</td>';
				echo '<td>'.$items->updated_at->diffForHumans().'</td>';
				echo '<td>'.$display.'</td>';
				echo '<td>'.$action.'</td>';
				echo '</tr>';

                sub_list_product_category($data,$items->id,$user,$tab."&nbsp;&nbsp;&nbsp;&nbsp;");
            }
        }
    }
}

if(!function_exists('check_admin_systems')){
    function check_admin_systems($type){

        if(!auth()->check())
            return abort(403);

        if(Auth::user()->lever == \App\Enums\LeverUser::SUPPERADMIN)
            return true;

        if(in_array($type, Auth::user()->systemsModule()->pluck('type')->toArray()))
            return true;

        return abort(404);
    }
}

if(!function_exists('sub_news_category')){
    function sub_option_category($data,$id,$old="",$tab = '&nbsp;&nbsp;&nbsp;&nbsp;'){

        foreach($data->where('parent_id', $id) as $item):
            $select = "";
            if($old == $item->id || old('category') == $item->id || request('category') == $item->id){
                $select = "selected";
            }
            $name = $item->title ?? $item->name;
            echo '<option value="'.$item->id.'"'.$select.'>'.$tab.$name.'</option>';

            sub_option_category($data,$item->id,$old,$tab."&nbsp;&nbsp;&nbsp;&nbsp;");
        endforeach;
    }
}

if(!function_exists('list_news_category')){
    function list_news_category($data){
        foreach($data->where('parent_id',0) as $items){

                $status = $items->status == 1 ? "checked" : "";
                $public = $items->public == 1 ? "checked" : "";

                $sort= '<input style="width:120px;" class="form-control" type="text" name="sort" data-id="'.$items->id.'" value="'.$items->sort.'"><span id="change-sort-success_'.$items->id.'" class="change-sort"></span>';
                $display = '<div class="checkbox checkbox-primary checkbox-circle">';
                $display .= '<input id="checkbox_public_'.$items->id.'" id="public"  '.$public.' type="checkbox" name="public">';
                $display .= '<label for="checkbox_public_'.$items->id.'" class="data_public" data-id="'.$items->id.'">Hiển thị</label>';
                $display .= '</div>';
                $display .= '<div class="checkbox checkbox-primary checkbox-circle">';
                $display .= '<input id="checkbox_status_'.$items->id.'" id="status" '.$status.' type="checkbox" name="status">';
                $display .= '<label for="checkbox_status_'.$items->id.'" class="mb-0 data_status" data-id="'.$items->id.'">Nổi bật</label>';
                $display .= '</div>';

                $action = '<a href="'.route('admin.news_category.edit',$items).'" title="Sửa" class="btn btn-purple waves-effect waves-light">
                                        <span class="icon-button"><i class="fe-edit-2"></i></span></a> ';
                $action .= '<a href="'.route('admin.news.category.del',$items->id).' " title="Xóa" onclick="return confirm(\'Bạn chắc chắn muốn xóa!\')" class="btn btn-warning waves-effect waves-light"><span class="icon-button"><i class="fe-x"></i></span> </a>';
                echo '<tr><td>';
                echo '<div class="checkbox checkbox-primary checkbox-circle">';
                echo '<input id="checkbox_del_'.$items->id.'" class="check_del"  value="'.$items->id.'"  type="checkbox" name="check_del[]">';
                echo '<label for="checkbox_del_'.$items->id.'"></label></div></td>';
				echo '<td>'. $items->id.'</td>';
				echo '<td class="position-relative">'.$sort.'</td>';
				echo '<td><a href="'.route('alias',$items->alias).'" class="text-secondary" target="_blank">'.$items->title.'</a></td>';
				echo '<td></td>';
				echo '<td>'.$items->updated_at->diffForHumans().'</td>';
				echo '<td>'.$display.'</td>';
				echo '<td>'.$action.'</td>';
				echo '</tr>';

                sub_list_news_category($data, $items->id);

        }

    }
}

if(!function_exists('sub_list_news_category')){

    function sub_list_news_category($data,$parent_id,$user=0,$tab = '&nbsp;&nbsp;&nbsp;&nbsp;'){

        foreach($data->where('parent_id', $parent_id) as $items){

                $status = $items->status == 1 ? "checked" : "";
                $public = $items->public == 1 ? "checked" : "";

                $sort= '<input style="width:120px;" class="form-control" type="text" name="sort" data-id="'.$items->id.'" value="'.$items->sort.'"><span id="change-sort-success_'.$items->id.'" class="change-sort"></span>';

                $display = '<div class="checkbox checkbox-primary checkbox-circle">';
                $display .= '<input id="checkbox_public_'.$items->id.'" id="public"  '.$public.' type="checkbox" name="public">';
                $display .= '<label for="checkbox_public_'.$items->id.'" class="data_public" data-id="'.$items->id.'">Hiển thị</label>';
                $display .= '</div>';
                $display .= '<div class="checkbox checkbox-primary checkbox-circle">';
                $display .= '<input id="checkbox_status_'.$items->id.'" id="status" '.$status.' type="checkbox" name="status">';
                $display .= '<label for="checkbox_status_'.$items->id.'" class="mb-0 data_status" data-id="'.$items->id.'">Nổi bật</label>';
                $display .= '</div>';

                $action = '<a href="'.route('admin.news_category.edit',$items).'" title="Sửa" class="btn btn-purple waves-effect waves-light">
                                       <span class="icon-button"><i class="fe-edit-2"></i></span></a> ';
                $action .= '<a href="'.route('admin.news.category.del',$items->id).' " title="Xóa" onclick="return confirm(\'Bạn chắc chắn muốn xóa!\')" class="btn btn-warning waves-effect waves-light"><span class="icon-button"><i class="fe-x"></i></span> </a>';

                echo '<tr><td>';
                echo '<div class="checkbox checkbox-primary checkbox-circle">';
                echo '<input id="checkbox_del_'.$items->id.'" class="check_del"  value="'.$items->id.'"  type="checkbox" name="check_del[]">';
                echo '<label for="checkbox_del_'.$items->id.'"></label></div></td>';
				echo '<td>'. $items->id.'</td>';
				echo '<td class="position-relative">'.$sort.'</td>';
				echo '<td><a href="'.route('alias',$items->alias).'" class="text-secondary" target="_blank">'.$tab.'<span class="tree-sub"></span>'.$items->title.'</a></td>';
				echo '<td>'.$items->parents->title.'</td>';
				echo '<td>'.$items->updated_at->diffForHumans().'</td>';
				echo '<td>'.$display.'</td>';
				echo '<td>'.$action.'</td>';
				echo '</tr>';

                sub_list_news_category($data,$items->id,$user,$tab."&nbsp;&nbsp;&nbsp;&nbsp;");

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

if(!function_exists('check_sub')){
    function check_sub($a,$b){
        if($a == $b)
            return true;
        if(is_array($b) && in_array($a,$b))
            return true;
        return false;
    }
}

if(!function_exists('sub_menu_checkbox')){
    function sub_menu_checkbox($data,$parent,$old="",$tab='&nbsp;&nbsp;&nbsp;&nbsp;'){
        foreach ($data->where('parent_id', $parent) as $key => $value) {
                echo $tab.'<option value="'.$value->id.'" '.selected($value->id,$old->categorys->pluck('category_id')->toArray()).'>'.$tab.$value->title.'</option>';
                sub_menu_checkbox($data,$value->id,$old,$tab.'&nbsp;&nbsp;&nbsp;&nbsp;');
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
    function sub_menu_category_checkbox($data,$parent,$tab='&nbsp;&nbsp;'){
        foreach ($data->where('parent_id', $parent) as $key => $value) {
            $name = $value->title ?? $value->name;
           echo $tab.'<label><span class="tree-sub"></span><a href="javascript:void(0)" class="addmenu text-secondary" title="Thêm ::::::'.$name.':::::: vào menu" data-name="'.$name.'" data-url="'.$value->alias.'" data-image="'.$value->image.'" data-thumb="'.$value->thumb.'"><span class="text-left"><i class="fe-plus pr-1"></i></span>'.$name.'</a></label><br>';

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

    function sub_add_menu($data,$id,$old=null,$tab = '&nbsp;&nbsp;'){
        foreach($data->where('parent_id', $id) as $item):
            $select = null;
            if($old == $item->id){
                $select = "selected";
            }
            echo '<option value="'.$item->id.'"'.$select.'>'.$tab.$item->name.'</option>';
            sub_add_menu($data,$item->id,$old,$tab."&nbsp;&nbsp;");
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
            echo '<a href="'.route('admin.menus.edit',$items).'" title="Sửa" class="btn btn-purple waves-effect waves-light"><i class="fe-edit-2"></i></a> ';
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

        if($postlang->where('post_id', $id)->count() > 0){

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
