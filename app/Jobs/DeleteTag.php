<?php

namespace App\Jobs;

use App\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteTag implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected  $tags;
    public function __construct($tags)
    {
        //
        $this->tags = $tags;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        //each tag and delete
        if ($this->tags && count($this->tags) > 0) {
            foreach ($this->tags as $tag) {
                if ($tag && $tag->posts()->count() === 0) {
                    $tag->delete();
                    Log::info('Tag deleted: ' . $tag->title . ' id ' . $tag->id);
                } else {
                    Log::info('Tag not deleted: ' . $tag->title . ' (still in use)' . $tag->id);
                }
            }
        }
    }
}
