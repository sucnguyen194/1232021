<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemsModule extends Model
{
    protected $table = 'systems';

    protected $guarded = ['id'];

    public function SystemsUser() {
        return $this->hasMany(UserModuleSystems::class,'type');
    }

    public static function boot(){

        parent::boot();

        static::deleting(function($system){

            $parent = SystemsModule::where('parent_id',$system->id)->get();

            foreach ($parent as $item){
                $item->update([
                    'parent_id' => 0
                ]);
            }

        });

    }
}
