<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';

    protected $fillable = [
        'authorId',
        'parentId',
        'title',
        'metaTitle',
        'slug',
        'summary',
        'published',
        'publishedAt',
        'create_at',
        'update_at'
    ];

    public function categories()
    {
        return $this->belongsToMany('Post', 'post_category', 'postId', 'categoryId');
    }

    public function tags()
    {
        return $this->belongsToMany('Tag', 'post_tag', 'postId', 'tagId');
    }
}
