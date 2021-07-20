<?php


namespace BinomeWay\NovaContactTool\Layouts;


use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class SocialLayout extends Layout
{
    protected $name  = 'contact_social';

    public function fields()
    {
        return [
            Text::make(__('Name'), 'name')
                ->required(),

            Text::make(__('Icon'), 'icon')
                ->nullable(),

            Text::make(__('Url'), 'url')
                ->required()
                ->rules('url'),
        ];
    }

    public function title()
    {
        return __('Social Media');
    }
}
