<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name', 'description', 'image', 'status', 'added_by', 'updated_by'
    ];
    
    public function product() {
        return $this->hasMany('App\Product');
    }
}
