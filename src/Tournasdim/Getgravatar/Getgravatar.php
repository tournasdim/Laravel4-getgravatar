<?php namespace Tournasdim\Getgravatar;

use Sentry;
use Config;
use Auth ;
use InvalidArgumentException ; 

/**
* Getgravatar - A lightweight Laravel 4 package for working with gravatars .
*
*
* @category Gravatar
* @package Getgravatar
* @author Tournas Dimitrios
* @license MIT License
* @link https://github.com/tournasdim/getgravatar
*===================================================================
*
* This source file is subject to the MIT license that is bundled
* with this package in the file LICENSE.
*
*/
class Getgravatar
{
	/**
	 * URL constants for the avatar images
	 * 
	 * @var String
	 */
	const HTTP_URL = 'http://www.gravatar.com/avatar/';
	const HTTPS_URL = 'https://secure.gravatar.com/avatar/';

	/**
	 * Configuring the "Rating" .
	 * 
	 * @var String
	 */
	private $maxRating  ;

	/**
	 * Holds the Url of a Custom Avatar (if defined) .
	 * 
	 * @var String
	 */
	private $customGravUrl  ;

	/**
	 * Configuring a fallback Gravatar 
	 * (if user's email isn't registered on Gravata's server) .
	 * 
	 * @var String
	 */ 
	private $defGrav ;

	/**
	 * Configuring the "Size" .
	 * 
	 * @var String
	 */
	private $size  ;

	/**
	 * Holds the email address  .
	 * 
	 * @var String
	 */
	private $email  ; 

	
	/**
	 * Bootstraping the Class
	 * 
	 */
	public function __construct()
	{

	$this->setup() ; 

	}
	
	/**
	 * Getting the Gravatar link , embeded into an "<img>"
	 * Basic usage : Gravatar::get() 
	 * Defining the size : Gravatar::get('55') .This overwrites the config file
	 * Defining a custom fallback Gravatar . Gravatar::get(null , false)
	 * Hard-coding the email . Gravatar::get(null , false , 'mail@gmail.com') .
	 * @param Integer $size  
	 * @param Boolean $randomize 
	 * @param String  $emeil 
	 * @return String
	 */
	public function get($size = null , $randomize = true , $email = null )
	{	

	if(isset($email)) $this->email = $this->validate('email', $email) ; 
	$querystring =  $this->generateQuery($size , $randomize ) ;
	$urlType = \Request::secure() ? static::HTTPS_URL : static::HTTP_URL ; 
	$url = $urlType  . $querystring ; 
	return  '<img src="' . $url . '"" />' ; 
	
	}

	/**
	 * Defing the Auth adapter "on the fly" . No supported yet .
	 * @return void
	 * 
	 */
	public function setAuthAdapter($adapter)
	{

	Config::set('getgravatar::authAdapter.authAdapter' , $adapter) ; 

	}

	/**
	 * Reading all directives from the configuration file .
	 * @return void
	 * 
	 */
	protected function setup()	
	{

 	extract(Config::get('getgravatar::config')) ;
 	$this->maxRating = $this->validate('rating' , strtolower($maxRating)) ;
 	$this->size = $this->validate('int' , (int) $size ) ; 
	$this->defGrav = $this->validate('grav' , strtolower($defGrav)) ;
	$this->customGravUrl = $this->validate('url' , strtolower($customGravUrl)) ;
	$this->authAdapter = $authAdapter ; 
	$this->getEmail() ;


	}

	/**
	 * Retrieving the email of current logged-in user . 
	 * The "authAdapter" directive into config.php defines which Authentication
	 *  adapter is use in current app .
	 * Currently , this package supports three adapters (Auth , Sentry and Confide)
	 * As a fallback measure , a third option can be used while calling the Class
	 * Gravatar::get( null , false , 'johnemail@gmail.com')
	 * @return void
	 * 
	 */
	protected function getEmail() 
	{

	 $authAdapter = Config::get('getgravatar::authAdapter') ;
	 switch (strtolower($authAdapter))
		{
	case 'sentry' :
	if(Sentry::check()) $this->email = Sentry::getUser()->email ;
  	break ;
  	case 'confide' :
  	if(Confide::user()) $this->email = Confide::User()->email ; 
  	break ; 
	case 'auth' :
  	if(Auth::check()) 	$this->email = Auth::User()->email ; 
  	break;
	default :
	$this->email = null ; 
  
		}
	}

	
	/**
	 * Reading all directives from the configuration file .
	 * @param Integer $zize The size of the requested Gravatar image
	 * @param Boolean $randomize . Should we request a random image . 
	 * @return String 
	 * 
	 */
	protected function generateQuery($size , $randomize )
	{

	$size = $size ? : $this->size ;
	$rating = $this->maxRating ; 
	$defaultGrav = $randomize ? $this->getRandomGrav() : $this->defGrav ; 
	$email =  $this->email ? : null ;
	//$forceDefauld = $email ? true : false ; // Not suported
	$data = array(  'd' => $defaultGrav ,
				     's' =>  $size ,
				     'r' => $rating ,						 
						) ;
	$setCustomGrav = (substr_compare($this->customGravUrl ,"http://",0,7)) === 0 ? true : false ;
	if($setCustomGrav) $data['d'] = $this->customGravUrl ;
	$queryString =  '?'. http_build_query(array_filter($data)) ;
	$url = $this->hash($email) ; 
	$url .= $queryString ; 
	return $url  ; 		
		
	}	
	
	/**
	 * Generating a random Gravatar-name . 
	 * @return String
	 * 
	 */
	protected function getRandomGrav()
	{

	$gravs = array ( 'mm' , 'identicon' , 'monsterid' , 'wavatar' , 'retro') ; 
	return $gravs[array_rand($gravs)] ;
	
	}

	/**
	 * MD5 hashing of the email . 
	 * This hashing algorithm is recoginzed by Gravatar's API
	 * @return  String 
	 * 
	 */
	protected function hash($email)
	{
		
	return  md5( strtolower( trim( $email ) ) ) ;

	}

	/**
	 * Validating values retrieved from the configuration file or given as
	 *  arguments to the Class . 
	 * 
	 * @return  mixed 
	 * 
	 */
	protected function validate($type , $value)
	{

		$validRatings = array('g' => 1 , 'pg' => 1 , 'r' => 1 , 'x' => 1 ) ;
		$validGravs = array( 'mm' => 1 , 'identicon' => 1 , 
			'monsterid' => 1 , 'wavatar' => 1 , 'retro' => 1 ) ; 

	 	switch ($type)
	 	{

	 		case 'rating' :
	 		if(!isset($validRatings[$value]))
			{
			throw new InvalidArgumentException(sprintf('Invalid rating "%s" specified, only "g", "r", "x", or "pg" are allowed to be used.', $value ));
			}
			return $value ;


			case 'int' :
			if(!is_int($value) && !ctype_digit($value))
			{
			throw new InvalidArgumentException('Avatar size specified must be an integer');
			}
			if($value > 512 || $value < 0)
			{
			throw new InvalidArgumentException('Avatar size must be within 0 pixels and 512 pixels');
			}
			return $value ;


			case 'grav' :
			if(!isset($validGravs[$value]))
			{
			throw new InvalidArgumentException(sprintf('Invalid Grav "%s" specified , only  "mm" , "identicon" , "monsterid" , "wavatar" , "retro" are allowed to be used.', $value));
			}
			return $value ; 


			case 'url' :
			if($value === 'false') return $value ; 
			if(!filter_var($value , FILTER_VALIDATE_URL))
			{
			throw new InvalidArgumentException('The specified URL is not a valid URL');
			}
			return $value ;

			case 'email' :
			if(!filter_var($value , FILTER_VALIDATE_EMAIL))
			{
			throw new InvalidArgumentException('The specified email is not valid ') ;
			} 
	 		return $value ; 

 		}	
	}

}