<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteCategory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected  $categories;
    public function __construct($categories)
    {
        //
        $this->categories = $categories;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        //each category and delete
        if ($this->categories && count($this->categories) > 0) {
            foreach ($this->categories as $category) {
                if ($category && $category->posts()->count() === 0) {
                    $category->delete();
                    Log::info('Category deleted: ' . $category->title . ' id ' . $category->id);
                } else {
                    Log::info('Category not deleted: ' . $category->title . ' (still in use)' . $category->id);
                }
            }
        }
    }
}
