<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['parent_id', 'name', 'slug'];

    public function parent(){
        return $this->belongsTo(Topic::class, 'parent_id');
    }

    public function children(){
        return $this->hasMany(Topic::class, 'parent_id');
    }

    public function documents(){
        return $this->hasMany(Document::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
