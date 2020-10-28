<?php

namespace App\Observers;

use App\ShowTemplate;
use Illuminate\Support\Str;

class ShowTemplateObserver
{
    /**
     * Handle the showTemplate "saving" event.
     *
     * @param ShowTemplate $showTemplate
     * @return void
     */
    public function saving(ShowTemplate $showTemplate): void
    {
        $showTemplate->slug = $showTemplate->webName ? Str::slug($showTemplate->webName) : Str::slug($showTemplate->name);
    }
}
