<?php


namespace BinomeWay\NovaContactTool\Contracts;


interface SettingsPage
{

    public function name(): string;
    public function options(): array;
    public function casts(): array;
    public function fields() : array;
}
