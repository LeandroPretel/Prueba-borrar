<?php

namespace App\Observers;

use App\Show;
use Exception;
use Illuminate\Support\Str;

class ShowObserver
{
    /**
     * Handle the show "saving" event.
     *
     * @param Show $show
     * @return void
     */
    public function saving(Show $show): void
    {
        // Create slug for the show
        $possibleSlug = $show->webName ? Str::slug($show->webName) : Str::slug($show->name);
        if ($possibleSlug) {
            $existingShow = Show::whereSlug($possibleSlug)->first();
            if ($existingShow) {
                $show->slug = $possibleSlug . '-' . $show->createdAt->year;
            } else {
                $show->slug = $possibleSlug;
            }
        }
    }

    /**
     * Handle the show "created" event.
     *
     * @param Show $show
     * @return void
     */
    public function created(Show $show)
    {
    }

    /**
     * Handle the show "updated" event.
     *
     * @param Show $show
     * @return void
     */
    public function updated(Show $show)
    {
    }

    /**
     * Handle the show "deleted" event.
     *
     * @param Show $show
     * @return void
     * @throws Exception
     */
    public function deleted(Show $show): void
    {
        // Delete related sessions
        foreach ($show->sessions as $session) {
            $session->deletedBy = $show->deletedBy;
            $session->delete();
        }
    }

    /**
     * Handle the show "restored" event.
     *
     * @param Show $show
     * @return void
     */
    public function restored(Show $show)
    {
    }

    /**
     * Handle the show "force deleted" event.
     *
     * @param Show $show
     * @return void
     */
    public function forceDeleted(Show $show)
    {
    }
}
