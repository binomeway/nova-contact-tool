<?php


namespace BinomeWay\NovaContactTool\Settings;

use BinomeWay\NovaContactTool\Contracts\SettingsPage;
use BinomeWay\NovaContactTool\Layouts\AddressLayout;
use BinomeWay\NovaContactTool\Layouts\EmailAddressLayout;
use BinomeWay\NovaContactTool\Layouts\NumberLayout;
use BinomeWay\NovaContactTool\Layouts\SocialLayout;
use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Laravel\Nova\Panel;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class ContactSettings implements SettingsPage
{
    public const EMAILS = 'emails';
    public const NUMBERS = 'numbers';
    public const ADDRESSES = 'addresses';
    public const SOCIALS = 'socials';

    public function name(): string
    {
        return __('Contact Information');
    }

    public function options(): array
    {
        return [
            self::SOCIALS,
            self::EMAILS,
            self::NUMBERS,
            self::ADDRESSES,
        ];
    }

    public function casts(): array
    {
        return [
            self::SOCIALS => FlexibleCast::class,
            self::EMAILS => FlexibleCast::class,
            self::NUMBERS => FlexibleCast::class,
            self::ADDRESSES => FlexibleCast::class,
        ];
    }

    public function fields(): array
    {
        return [
            Tabs::make(__('Contact Settings'), [
                $this->emailsPanel(),
                $this->numbersPanel(),
                $this->addressesPanel(),
                $this->socialsPanel(),
            ]),

        ];
    }

    private function emailsPanel(): Panel
    {
        return Panel::make(__('Email Addresses'), [
            Flexible::make(__('Emails List'), self::EMAILS)
                ->addLayout(EmailAddressLayout::class)
                ->button(__('Add Email'))
                ->stacked(),
        ]);
    }

    private function addressesPanel(): Panel
    {
        return Panel::make(__('Addresses'), [
            Flexible::make(__('Addresses List'), self::ADDRESSES)
                ->addLayout(AddressLayout::class)
                ->button(__('Add Address'))
                ->stacked(),
        ]);
    }

    private function socialsPanel(): Panel
    {
        return Panel::make(__('Social Links'), [
            Flexible::make(__('Social Links List'), self::SOCIALS)
                ->addLayout(SocialLayout::class)
                ->button(__('Add Social'))
                ->stacked(),
        ]);
    }

    private function numbersPanel(): Panel
    {
        return Panel::make(__('Phone Numbers'), [
            Flexible::make(__('Numbers List'), self::NUMBERS)
                ->addLayout(NumberLayout::class)
                ->button(__('Add Number'))
                ->stacked(),
        ]);
    }
}
