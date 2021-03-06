<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $guarded = ['id'];

    public function users(){
        return $this->belongsToMany(User::class);
    }

    protected static function bootTraits()
    {
        parent::bootTraits(); // TODO: Change the autogenerated stub

        static::deleting(function($system){

            $parent = System::where('parent_id',$system->id)->get();

            foreach ($parent as $item){
                $item->update([
                    'parent_id' => 0
                ]);
            }

        });
    }
}
