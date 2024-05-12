<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'category';

    protected $fillable = [
        'parentId',
        'title',
        'metaTitle',
        'slug',
        'content'
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_category', 'categoryId', 'postId');
    }
}
