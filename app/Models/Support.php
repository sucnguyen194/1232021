<?php

namespace App\Models;

use App\Enums\SystemsModuleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Support extends Model
{
    protected $guarded = ['id'];

    public function language(){
        return $this->belongsTo(Lang::class,'lang','value');
    }

    public function getRouteAttribute(){
        switch ($this->type){
            case SystemsModuleType::CUSTOMER:
                return route('admin.supports.customers.index');
                break;
            case SystemsModuleType::SUPPORT:
                return route('admin.supports.index');
                break;
        }
    }

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        self::saving(function($support){
            $support->lang = $support->lang ? $support->lang : session()->get('lang');
            $support->user_id = $support->user_id ? $support->user_id : auth()->id();
        });
        self::deleting(function($support){
            if(File::exists($support->image))
                File::delete($support->image);
        });
    }
}
