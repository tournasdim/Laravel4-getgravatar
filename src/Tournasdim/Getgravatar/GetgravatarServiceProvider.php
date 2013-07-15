<?php namespace Tournasdim\Getgravatar;

use Illuminate\Support\ServiceProvider;

class GetgravatarServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false ;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{

	$this->package('tournasdim/laravel4-getgravatar');
	$this->registerCommand();   

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

	}

    /**
     * Register the artisan commands.
     *
     * @return void
     */
    protected function registerCommand()
    {

   	$this->app['command.gravatar'] = $this->app->share(function($app)
        {
  			return new Commands\UninstallCommand;
        });
   	
   	$this->commands('command.gravatar') ; 
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