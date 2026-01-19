<?php

namespace App\Nova\Dashboards;

use App\Models\Language;
use App\Models\Word;
use Laravel\Nova\Dashboards\Main as Dashboard;
use Laravel\Nova\Metrics\Value;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(): array
    {
        return [
            (new class extends Value
            {
                public $name = 'Total Languages';

                public function calculate($request)
                {
                    return $this->result(Language::count());
                }
            }),

            (new class extends Value
            {
                public $name = 'Total Words';

                public function calculate($request)
                {
                    return $this->result(Word::count());
                }
            }),

            (new class extends Value
            {
                public $name = 'Average Word Length';

                public function calculate($request)
                {
                    return $this->result(round(Word::avg('length') ?? 0, 1))->suffix('chars');
                }
            }),
        ];
    }
}
