## Laravel Assignment

This is a simple assignment to use Filamentphp and openai api to generate image from the user given keyword.

### Install

-   Clone this repo `git clone https://github.com/sahapranta/laravel_assignment` or download as zip file

```bash
composer install

cp .env.example .env
php artisan key:generate

php artisan migrate
php artisan make:filament-user
php artisan serve

# for the background job processing...
php artisan queue:work
```

-   Set the `OPENAI_API_KEY` at `.env`

-   Visit: [http://localhost:8000/admin](http://localhost:8000/admin)

### Process flow

1. Add the Keyword of the Image
2. There is an Observer `ImageObserver.php` that listen for the created event
3. On created dispatch using Bus chaining two Jobs `GetPrompt` & `GenerateImage`
4. Get Prompt Job will get the prompt from OpenAi API based on the keywords added by User in step 1, then it will save into database

5. Generate Image will call OpenAi Api based on the given prompt to create an image.

6. After getting the api response I am storing the image to Storage and saving the path to database

7. In the ImageResource I have added custom table field that fetch the latest data at every 10sec using wire:poll if the progress is not 100%
