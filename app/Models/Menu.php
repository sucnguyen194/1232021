<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = ['id'];

    public function scopePosition($q){
        $q->wherePosition(\Session::get('menu_position'));
    }

    public function scopeLangs($q){
        $q->whereLang(\Session::get('lang'));
    }

    public function scopeSort($q){
        $q->orderby('sort','asc')->orderby('id','asc');
    }

    public function scopeTop($q){
        $q->wherePostion('top');
    }

    public function scopeBottom($q){
        $q->wherePostion('bottom');
    }

    public function scopeLeft($q){
        $q->wherePostion('left');
    }

    public function scopeRight($q){
        $q->wherePostion('right');
    }
    public function scopeParentid($q){
        $q->whereParentId(0);
    }

    public function getParentAttribute(){

        return Menu::whereParentId($this->id)->sort()->get();
    }

    public static function boot(){
        parent::boot();

        self::deleting(function($menu){

            foreach($menu->parent as $item){
                $item->update(['parent_id' => 0]);
            }
        });
    }
}
