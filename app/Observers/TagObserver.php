<?php

namespace App\Observers;

use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TagObserver
{
    /**
     * Handle the Tag "created" event.
     */
    public function created(Tag $tag): void
    {
        //
        $this->updateCacheTag();
    }

    /**
     * Handle the Tag "updated" event.
     */
    public function updated(Tag $tag): void
    {
        //
        $this->updateCacheTag();
    }

    /**
     * Handle the Tag "deleted" event.
     */
    public function deleted(Tag $tag): void
    {
        //
        $this->updateCacheTag();
    }

    /**
     * Handle the Tag "restored" event.
     */
    public function restored(Tag $tag): void
    {
        //
        $this->updateCacheTag();
    }

    /**
     * Handle the Tag "force deleted" event.
     */
    public function forceDeleted(Tag $tag): void
    {
        //
        $this->updateCacheTag();
    }
    // update cache list tag
    public function updateCacheTag(): void
    {
        //
        Log::info('updateCacheTag');
        Cache::forget("tags");
        Cache::rememberForever('tags',  function () {
            return Tag::query()->orderBy('created_at', 'asc')->paginate(10, ['*'], 'page', 1);
        });
    }
}
