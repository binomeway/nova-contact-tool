<?php

namespace BinomeWay\NovaContactTool;

use BinomeWay\NovaContactTool\Http\Middleware\Authorize;
use BinomeWay\NovaContactTool\Services\Contact;
use BinomeWay\NovaContactTool\Services\Settings;
use BinomeWay\NovaContactTool\Settings\ContactSettings;
use BinomeWay\NovaContactTool\Settings\MailSettings;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ToolServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('nova-contact-tool')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_nova_contact_tool_table')
            ->hasRoute('web')
            ->hasTranslations()
            //->hasCommand(ContactCommand::class)
        ;
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function packageBooted()
    {
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-contact-tool');

        $this->app->booted(function () {
            $this->routes();
        });

        /*Nova::serving(function (ServingNova $event) {
            // for assets
        });*/

    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/nova-contact-tool')
            ->group(__DIR__ . '/../routes/api.php');
    }

    public function packageRegistered()
    {
        $this->app->singleton(Contact::class);
        $this->app->singleton(Settings::class, fn() => new Settings([
            new ContactSettings(),
            new MailSettings(),
        ]));
    }
}
