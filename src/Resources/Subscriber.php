<?php

namespace BinomeWay\NovaContactTool\Resources;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Resource;

class Subscriber extends Resource
{
    public static $group = 'Contact';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \BinomeWay\NovaContactTool\Models\Subscriber::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'email';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'email', 'name', 'phone', 'ip',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Email')->required()->rules('email', 'unique:contact_subscribers')->sortable(),
            Text::make('Name')->required()->sortable(),
            Text::make('Phone')->nullable()->sortable(),
            Boolean::make('Active')->default(true)->sortable(),
            Code::make('Data')->json()->nullable(),
            Text::make('Ip')->nullable()->sortable(),
            Textarea::make('Agent')->nullable(),
            DateTime::make('Created At')->onlyOnDetail()->readonly(),
            DateTime::make('Updated At')->readonly(),
            HasMany::make('Messages'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
