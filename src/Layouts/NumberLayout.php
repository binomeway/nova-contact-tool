<?php


namespace BinomeWay\NovaContactTool\Layouts;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class NumberLayout extends Layout
{
    protected $name = 'contact_number';

    public function fields()
    {
        return  [
            Text::make(__('Name'), 'name')->nullable(),
            Text::make(__('Phone Number'), 'value')->required(),
            Boolean::make(__('Is Primary'), 'is_primary')->default(false),
        ];
    }

    public function title()
    {
        return __('Contact Number');
    }
}
