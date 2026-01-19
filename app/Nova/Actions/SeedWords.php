<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class SeedWords extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Seed Words';

    public $standalone = true;

    public function handle(ActionFields $fields, $models)
    {
        Artisan::call('words:seed');

        return Action::message('Words seeded successfully!');
    }

    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
