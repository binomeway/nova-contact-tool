<?php


namespace BinomeWay\NovaContactTool\Layouts;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class EmailAddressLayout extends Layout
{
    protected $name = 'contact_email';


    public function title()
    {
        return __('Contact Email');
    }

    public function fields()
    {
       return [
           Text::make('Email Address', 'value')->rules('email', 'required'),
           Boolean::make('Is Primary')->default(false),
       ];
    }
}
