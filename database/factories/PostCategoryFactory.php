<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = PostCategory::class;

    public function definition()
    {
        return [
            // Define the fields for the pivot table as needed
        ];
    }
}
