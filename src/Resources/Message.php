<?php

namespace BinomeWay\NovaContactTool\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Resource;

class Message extends Resource
{
    public static $group = 'Contact';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \BinomeWay\NovaContactTool\Models\Message::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'subject';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'subject', 'content','to', 'from',
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
            Text::make('Subject')->required()->sortable(),
            Text::make('To')->required()->sortable(),
            Text::make('From')->required()->sortable(),
            Text::make('Summary', function () {
                return Str::limit($this->content, 25);
            })->onlyOnIndex(),
            BelongsTo::make('Subscriber')->nullable()->sortable(),
            Textarea::make('Content')->required()->stacked(),
            Text::make('Mailable')->nullable()->hideFromIndex(),
            KeyValue::make('Meta')->nullable(),
            DateTime::make('Sent At', 'created_at')
                ->sortable()
                ->readonly(),
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
