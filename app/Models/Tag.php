<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $table = 'tags';

    protected $fillable = [
        'title',
        'metaTitle',
        'slug',
        'content'
    ];
    
    public function posts()
    {
        return $this->belongsToMany('Post', 'post_category', 'tagId', 'postId');
    }
}
