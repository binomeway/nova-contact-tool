<?php

namespace BinomeWay\NovaContactTool;

use BinomeWay\NovaContactTool\Resources\{Message, Subscriber};
use BinomeWay\NovaContactTool\Services\Settings;
use Laravel\Nova\{Nova, Tool};

class NovaContactTool extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        //Nova::script('nova-contact-tool', __DIR__.'/../dist/js/tool.js');
        //Nova::style('nova-contact-tool', __DIR__.'/../dist/css/tool.css');

        Nova::resources([
            Message::class,
            Subscriber::class,
        ]);


        app(Settings::class)->boot();

    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('nova-contact-tool::navigation');
    }
}
