<?php namespace Tournasdim\Getgravatar;

use Illuminate\Support\ServiceProvider;

class GetgravatarServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('tournasdim/laravel4-getgravatar');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
	$this->app['gravatar'] = $this->app->share(function($app)
	{
		return new Getgravatar;
	});


// Shortcut , so devs don't need to add an Alias in config/app.php
    $this->app->booting(function()
    {
     $loader = \Illuminate\Foundation\AliasLoader::getInstance();
    $loader->alias('Gravatar', 'Tournasdim\Getgravatar\Facades\Getgravatar');
    });

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{

		return array('gravatar');

	}

}