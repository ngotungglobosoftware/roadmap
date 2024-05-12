<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // For example, associate the post with a user
        $user = User::factory()->create();
        $title = fake()->sentence();
        $published = fake()->numberBetween(0, 1);
        return [
            "title" => $title,
            "authorId" => $user->id,
            "parentId" => NULL,
            "metaTitle" => NULL,
            "slug" => Str::slug($title),
            "summary" => NULL,
            "published" => $published,
            "createdAt" => now(),
            "updatedAt" => now(),
            "publishedAt" => now(),
            "content" => fake()->text(50)
        ];
    }
    public function withParent($parentId)
    {
        return $this->state(function (array $attributes) use ($parentId) {
            return [
                'parentId' => $parentId,
            ];
        });
    }
    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            // Your custom logic after creating a post
            $category = Category::factory()->create();
            $tag = Tag::factory()->create();
            PostCategory::factory()->create(
                [
                    'postId' => $post->id,
                    'categoryId' => $category->id,
                ]
            );
            PostTag::factory()->create(
                [
                    'postId' => $post->id,
                    'tagId' => $tag->id,
                ]
            );
        });
    }
}
