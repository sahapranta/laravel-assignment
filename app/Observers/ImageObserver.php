<?php

namespace App\Observers;

use App\Models\Image;
use App\Jobs\GetPrompt;
use App\Jobs\GenerateImage;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class ImageObserver
{
    /**
     * Dispatch the jobs for getting prompt and
     * Generating the image on created event
     */
    public function created(Image $image): void
    {
        Bus::chain([
            new GetPrompt($image),
            new GenerateImage($image),
        ])
            ->catch(fn () => $image->update(['status' => 'failed']))
            ->dispatch();
    }

    /**
     * Delete the associated image from storage
     */
    public function deleted(Image $image): void
    {
        $disk = Storage::disk('public');

        if ($disk->exists($image->path)) {
            $disk->delete($image->path);
        }
    }
}
