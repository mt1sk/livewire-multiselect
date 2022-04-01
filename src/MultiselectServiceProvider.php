<?php

namespace LivewireMultiselect;

use LivewireMultiselect\Components\Select;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class MultiselectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishes();
        $this->registerViews();

        Livewire::component('multiselect', Select::class);
    }

    protected function registerPublishes()
    {
        $this->publishes(
            [__DIR__ . '/views' => $this->app->resourcePath('views/vendor/multiselect')],
            ['multiselect', 'multiselect:views']
        );
    }

    /**
     * @return void
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'multiselect');
    }
}
