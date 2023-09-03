<?php

namespace App\Jobs;

use App\Models\Image;
use Illuminate\Bus\Queueable;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetPrompt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Image $image
    ) {
        //
    }


    public function handle(): void
    {
        // Getting the prompt from the api using keyword
        $result = OpenAI::completions()
            ->create([
                'model' => 'text-davinci-003',
                'prompt' => "Write a prompt in single line to generate an Image about: {$this->image->keywords}." .
                    " Ensure no serial numbers, formatting, or extraneous text is added.",
            ]);

        $prompt = data_get($result, 'choices.0.text');

        // Updating the record in the database
        $this->image->prompt = trim($prompt);
        $this->image->status = 'working';
        $this->image->progress = 50;
        $this->image->save();
    }
}
