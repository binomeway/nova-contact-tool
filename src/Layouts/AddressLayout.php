<?php


namespace BinomeWay\NovaContactTool\Layouts;


use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class AddressLayout extends Layout
{
    protected $name = 'contact_address';

    public function fields()
    {
        return [
            Text::make(__('Name'), 'name')->nullable(),
            Textarea::make(__('Address'), 'value')->required(),
            Boolean::make(__('Is Primary'), 'is_primary')->default(false),
        ];
    }

    public function title()
    {
        return __('Address');
    }
}
