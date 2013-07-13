<?php namespace Tournasdim\Getgravatar\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class UninstallCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'gravatar:uninstall' ;

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Unistalling Gravatar\'s configuration file';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$canceled = true ; 
		$this->line('') ;
		$this->info( "Preparing to delete Gravatar's configuration file ......." );
		$this->line('') ;
		if ($this->confirm('Do you really want to delete Gravatar\'s configuration file ? [yes|no]'))
		{
		
		$this->info('Ok ... as you wish') ; 
		$this->line('') ;		
		\File::deleteDirectory(app_path().'/config/packages/tournasdim')  ;
		$canceled = false ; 
		$this->line('') ;	
		$this->info('Now run a \'composer update\' to complete the process') ;
		
		}
		
		$this->line('') ;	
		if($canceled) $this->info('You have canceled the process') ; 

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}