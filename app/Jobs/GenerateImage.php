<?php

namespace App\Jobs;

use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Filament\Notifications\Notification;

class GenerateImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct(
        public Image $image
    ) {
        //
    }


    public function handle(): void
    {
        // Generating the image from OpenAI
        $result = OpenAI::images()
            ->create([
                'prompt' => $this->image->prompt,
                'n' => 1,
                'size' => '256x256',
                'response_format' => 'url',
            ]);

        // Getting the Image as URL
        $url = $result?->data[0]?->url;

        // downloading the image from the url
        $image = file_get_contents($url);
        $path = 'images/' . Str::random(10) . '.png';

        // storing the image as File
        Storage::disk('public')->put($path, $image);

        // updating the image path & progress to database
        $this->image->path = $path;
        $this->image->status = 'done';
        $this->image->progress = 100;
        $this->image->save();

        // Notify the the User
        $this->image->user->notify(
            Notification::make()
                ->title('Your image for ' . $this->image->keywords . ' has been generated!')
                ->toDatabase()
        );
    }
}
