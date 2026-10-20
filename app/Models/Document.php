<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['topic_id', 'title', 'slug', 'content_blocks'];

    protected $casts = [
        'content_blocks' => 'array',
    ];

    public function topic(){
        return $this->belongsTo(Topic::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
