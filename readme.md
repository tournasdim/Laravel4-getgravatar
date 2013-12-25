# Laravel4-Getgravatar 
An avatar is a graphical representation of a user . It could be the person’s picture , or a random icon they want to be associated with . All a user has to do is to register an account based on their email address and upload an avatar to associate with that account . On the other side of the coin ,  webmasters that want to let their visitors graphically identify themselves have to implement a REST request to Gravatar's API . The request is just an URL encoded string with the email address of the user . If Gravatar's servers recognize this email address as a registered user , the user's associated Avatar is send back . Webmasters can also configure their system to automatically display an Identicon when a user has no registered Gravatar . 
This library is meant to be used as a Laravel 4.0 / 4.1 package and help PHP developers be concentrated on more important parts of their Laravel project  . 

### Prerequisites : 
It is assumed that you already have a working Laravel 4 project . Basic knowledge with Laravel's concepts are also required . For instance : Route , Controller , Blade or Authentication-adapter shouldn't be "strange" words to you . 


##Installation :
* 1) Update your Laravel's `composer.json` file

```javascript
{	
		"require": {
		"laravel/framework": "4.0.*" , 
		"tournasdim/laravel4-getgravatar": "dev-master" 
		}	
	}
```
* 2) Run a `composer update` command from your project's root  
* 3) Add the Gravatar Service Provider ***and an alias*** to your configuration file `app/config/app.php`:

```javascript
'providers' => array(
		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Workbench\WorkbenchServiceProvider',
		` ` ` ` ` ` ` ` ` ` ` ` ` ` ` ` ` ` ` ` ` ` ` ` `
		'Tournasdim\Getgravatar\GetgravatarServiceProvider'	),
```
```javascript
'aliases' => array(
		'App'             => 'Illuminate\Support\Facades\App',
		'Artisan'         => 'Illuminate\Support\Facades\Artisan',
		'Auth'            => 'Illuminate\Support\Facades\Auth',
		'Blade'           => 'Illuminate\Support\Facades\Blade',		
		'View'            => 'Illuminate\Support\Facades\View',
		````		````		````		````			````		````
		'Gravatar' => 'Tournasdim\Getgravatar\Facades\Getgravatar' , 
```
* 4) Optionally "transfer" the configuration file of this package into ***"app/config/packages"*** directory . This will give you the option to customize basic features of this package . Run  `php artisan config:publish "tournasdim/laravel4-getgravatar"` and open the file ***"app/config/packages/tournasdim/getgravatar/config.php"*** to customize basic functionality (size , customGravUrl , defGrav , authAdapter , maxRating) . Every option is explained  in the configuration file , drop me an issue if you need specific help though . 

##Un-Installing the package :
1. Remove this package's registration from Laravel's `composer.json` file
2. Remove Gravatar Service Provider from config file `app/config/app.php`
3. Remove the alias from the config file `app/config/app.php`
3. If step 4 of the installation process was implemented , manually remove the 
	"tournasdim" directory from `app/config/packages` directory . Instead of manually removing this directory , alternatively use the CLI  : `php artisan gravatar:uninstall `
4. Run a `composer update`  command .	

### Basic usage :

#### The "Gravar" Class accepts three optional parameters . 
* ***$size  :*** Expresed in pixels , if defined then it will overwrite the value defined into the configuration file . 
* ***$randomize :*** This feature is enabled by default ,the Class will randomly select a name from a Pool of accepted names ('mm' , 'identicon' , 'monsterid' , 'wavatar' , 'retro') . If the email send to Gravatar's server is not recognized then this specified Avatar will be returned instead . By setting the value of this option to ***"false"*** , random is turned off , and the value of `defGrav` will be used as parameter (a predefined fallback avatar) .
* ***$email :*** Default is set to null . Logged-in user's email is resolved by using the current Auth's adaptor Interface . Which adapter is currently used by the application is defined into a key ("authAdapter") of the configuration file  . By specifying an email , we actualy force the Class to use this as value into the query string . This feature is handy for non registered users , we will use their specified email (on comment sections) to build our query string . 
* Another important configuration option is made directly into the configuration file  ***"app/config/packages/tournasdim/getgravatar/config.php"*** (supposed that step 4 of the installation process was applied ) . If you'd prefer to use your own default fallback-image (perhaps your logo , a funny face , whatever), then you can easily do so by supplying a custom URL to an image . Just set your prefered Url into the ***"customGravUrl"*** variable of this package's configuration file . If the email send to Gravatar's server is not recognized , an image from the  custom Url will be returned instead . Keep in mind though that the ***"size"*** attribute isn't active anymore because Gravatar's server won't crop our custom image (make sure you have uploaded the right size for the custom fallback image) . 

### Practical examples :
#### Calling an Avatar from a Route
* ***Example 1 :***
```javascript
/*
Defining an Email address  :
1) If recognized by Gravatar's server , its accompanied image is returned , else
2) An alternative Avatar is returned (randomly chosen from a "pool" )  
 */ 
Route::get('/' , function() 
	{

	return Gravatar::get( null , false , 'johndoe@gmail.com' ) ; 

	});
```
* ***Example 2 :***
```javascript
/*
* No Email address defined : For logged-in users , their email address will be send to  Gravatar's API . 
*/ 
Route::get('/' , function() 
	{

	return Gravatar::get() ; 

	});
```
* ***Example 3 :***
```javascript
/*
Disable "random image" , which Avatar is returned depends on :
1) if "customGravUrl" is defined , then return the image from that Url
2) if "customGravUrl" was not defined then use the image specified in "defGrav". 
 */ 
Route::get('/' , function() 
	{

	return Gravatar::get(null , false) ; 

	});
```

* ***Example 4 :***
```php
// Defining an Avatar from a Route and passing it to the View 	
Route::get('/' , function() 
	{

	$gravatar = \Gravatar::get() ; 
	return  View::make('welcome')->with('gravatar' , $gravatar) ; 

	});
```
#### Calling an Avatar from a Controller
* ***Example 1 :***
```php 
<?php namespace Tournasdim\Admin\Controllers ;
	class AdminController extends BaseController {
// No Email specified , Auth's adapter Interface will try to resolve user's Email address . 
		public function showWelcome()
		{
			$gravatar = \Gravatar::get() ; 
			return  View::make('admin.dashboard')
			->with('gravatar' , $gravatar) ; 

		}

	}
?>
	// admin/dashboard.blade.php
		@extends('layouts.admin'))
		@section('header')
		<h3>
        <i class="icon-dashboard"></i>
        Dashboard  {{$gravatar}}
        </h3>
		@stop
```
* ***Example 2 :***
```php 
<?php namespace Tournasdim\Admin\Controllers ;
	class AdminController extends BaseController {
// Email is specified , this will be used into the query-string 
		public function showWelcome()
		{
			$gravatar = \Gravatar::get(null , false , 'johndoe@gmail.com') ; 
			return  View::make('admin.dashboard')
			->with('gravatar' , $gravatar) ; 

		}

	}
?>
	// admin/dashboard.blade.php
		@extends('layouts.admin'))
		@section('header')
		<h3>
        <i class="icon-dashboard"></i>
        Dashboard  {{$gravatar}}
        </h3>
		@stop
```
#### Calling an Avatar from Controller's constructor
* ***Example 1 :*** 
```php 
<?php namespace Tournasdim\Admin\Controllers ;
	class BaseController extends Controller {
	
    protected $gravatar ; 

    public function __construct() 
    {
 
       $this->gravatar = \Gravatar::get() ; 
    }
?>
// A controller that extends the base controller 
	<?php namespace Tournasdim\Admin\Controllers ;
	class AdminController extends BaseController {
	    public function index()
		{
		return View::make('admin.dashboard')
		->with('gravatar' , $this->gravatar);
		}
		
		public function profiler()
		{
		return View::make('admin.profiler')
		->with('gravatar' , $this->gravatar);
		}
	}
?>
		
		// views/admin/dashboard.blade.php
		@extends('layouts.admin'))
		@section('header')
		<h3>
        <i class="icon-dashboard"></i>
        Dashboard  {{$gravatar}}
        </h3>
		@stop
```


### Where to get help : 
Open a new issue on this repository and I'll try to help . Please limit your question(s) only to issues related to this project . Laravel's forums / IRC are more suited places to get general information about the Framework . Reports about bugs are welcome , but also , I would be glad to hear about your experience and suggestions of this repository . 


### Special thanks to :
Of course the creator of the Laravel Framework (Taylor Otwell) and all active members of Laravel's forum / IRC channel .  

### Things to do : 
* Based on user's feedback , improvements will be applied accordingly .  
* Users may optionally enter a variety of profile information to associate with their Gravatar account. This information is openly-accessible by Gravatar's API . Probably , a future release of this package will implement this functionality .
* Completing the Unit testing Class 

### Tools used during development : 

* An headless Linux box (CentOs 6) as web-server (Apache 2 , PHP5.4)
* Sublime Text 2 (Free version)
* Phing for automated deployment to GitHub and for FTP to the Linux box
* Git-Bash for versioning of the code
* Windows 7 with WAMP stack (Apache2.2.2 , PHP5.4.3)



## License : 
>
> Getgravatar 
> 
> [My Blog](http://tournasdimitrios1.wordpress.com)
>  
>  @copyright Tournas Dimitiros 2013
>
> 
> This program is free software: you can redistribute it and/or modify
> it under the terms of the GNU General Public License as published by
> the Free Software Foundation, either version 3 of the License, or
>(at your option) any later version.
> 
>This program is distributed in the hope that it will be useful,
>but WITHOUT ANY WARRANTY; without even the implied warranty of
>MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
>GNU General Public License for more details.
> 
>You should have received a copy of the GNU General Public License
>along with this program.  If not, see <http://www.gnu.org/licenses/>.
>  
>@author Tournas Dimitrios <tournasdimitrios@gmail.com>
>@version V1.0.0
>
>
>

