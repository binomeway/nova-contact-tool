<?php


namespace BinomeWay\NovaContactTool\Services;

use BinomeWay\NovaContactTool\Contracts\SettingsPage;
use BinomeWay\Settings\MailSettings;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;
use OptimistDigital\NovaSettings\NovaSettings;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class Settings extends Collection
{
    use HasFlexible;

    private array $pages = [];
    private array $settings = [];
    private array $casts = [];


    /**
     * Settings constructor.
     * Disable the normal construct and initialise it using the nova settings.
     * @param array $pages
     */
    public function __construct(array $pages = [])
    {
        parent::__construct(
            $this
                ->hydratePages($pages)
                ->resolve()
        );
    }

    private function resolve(): Collection
    {
        foreach ($this->pages as $page) {
            $this->settings = array_merge($this->settings, $page->options());
            $this->casts = array_merge($this->casts, $page->casts());
        }

        return $this->solveFlexibleCasts(nova_get_settings($this->settings));
    }

    /**
     * Returns an array with the correct casted values.
     * We have to do this because NovaSettings doesn't fully support FlexibleCast.
     * @param array $settings
     * @return Collection
     */
    private function solveFlexibleCasts(array $settings): Collection
    {
        return collect($settings)->map(function ($setting, $name) {
            return $this->isFlexible($name)
                // Cast the value to flexible. We have to do this because NovaSettings casts it only when it saves it to the database and not when retrieving it.
                ? $this->toFlexible($setting)
                // use the default casting done by the NovaSettings.
                : $setting;
        });
    }

    /**
     * Determines if the setting is in the casts array and is set to FlexibleCast.
     *
     * @param $settingName
     * @return bool
     */
    #[Pure] private function isFlexible($settingName): bool
    {
        return array_key_exists($settingName, $this->casts) && $this->casts[$settingName] === FlexibleCast::class;
    }

    private function hydratePages(array $pages)
    {
        foreach ($pages as $page) {
            if ($page instanceof SettingsPage) {
                $this->pages[] = $page;
            } else {
                // Should thrown an exception

                // TODO: Temporary Fix when using Livewire
                // FIXME: Must debug further to find out when and what causes to add empty arrays when using autoload in Livewire mount
            }
        }

        return $this;
    }

    public function boot()
    {
        // Collect Settings
        foreach ($this->pages as $page) {
            if ($page instanceof SettingsPage) {
                NovaSettings::addSettingsFields($page->fields(), $page->casts(), $page->name());
            }
        }
    }

    /**
     * Returns the primary value from a flexible setting
     *
     * @param string $flexibleSettingKey The flexible setting that holds the layouts
     * @param string $attributeName The attribute value to be retrieved. Default 'value'
     * @param string $primaryKey The key that determines if the layout is primary. Default 'is_primary'
     * @return mixed|null
     * @throws \Exception
     */
    public function primary(string $flexibleSettingKey, string $attributeName = 'value', $primaryKey = 'is_primary'): mixed
    {
        if (!$this->isFlexible($flexibleSettingKey)) {
            throw new \Exception("Your are trying to access the setting '{$flexibleSettingKey}' which is not casted as flexible.");
        }

        $setting = $this->get($flexibleSettingKey);

        if (is_null($setting)) {
            return null;
        }

        $value = $setting->filter(function ($item) use ($primaryKey) {
            return $item[$primaryKey];
        });

        if ($value->isEmpty()) {
            $value = $setting;
        }

        return optional($value->first())->{$attributeName};
    }


    public function isSavingSubscribers(): bool
    {
        return (bool)nova_get_setting(MailSettings::SAVE_SUBSCRIBERS, config('nova-contact-tool.save_subscribers'));
    }

    public function isSavingMessages(): bool
    {
        return (bool)nova_get_setting(MailSettings::SAVE_MESSAGES, config('nova-contact-tool.save_messages'));
    }

    public function getFromAddress(): string
    {
        return nova_get_setting(MailSettings::DEFAULT_FROM, config('mail.from.address'));
    }

    public function getPriority(): int
    {
        return (int)nova_get_setting(MailSettings::PRIORITY, config('nova-contact-tool.priority', 3));
    }

    public function getDefaultSubject(): string
    {
        return nova_get_setting(MailSettings::DEFAULT_SUBJECT, config('nova-contact-tool.default_subject'));
    }

    /**
     * Find the users where to send the contact message.
     *
     * @return string
     */
    public function getDefaultRecipient(): string
    {
        return nova_get_setting(MailSettings::DEFAULT_TO, config('nova-contact-tool.default_to'));
    }

}
