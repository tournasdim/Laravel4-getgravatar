<?php return array(
/*
|--------------------------------------------
|
| Default Gravatar Settings
|  
|--------------------------------------------
*/

/*
|-----------------------------------------------------------------------------
|
|  Basic usage : The Class accepts three optional parameters 
|  Gravatar::get($size = null , $randomize = true , $email = null)
|  $size : if null , then use value defined by the "size" key . 
|  $randomize: If false , then return a Grav defined in the "defGrav" key  .
|  $email : Set manually the Email , this option is usefull for visitors that 
|			write comments on Blogs / Forums ("non-registered users") . 
|			For registered users though , their Email address is automatically 
|			resolved  (through the Auth's adaptor Interface) . 	
|			The "authAdapter" key defines which Authentication adaptor is used 
|			in current Laravel application .
| 			
|-----------------------------------------------------------------------------
*/

/*
 * Define the default size . It can be overwriten though , 
 * >>>>>   Gravatar::get() <<<< returns this default size
 * >>>>>   Gravatar::get('22') << Overwrites the default value
 */
'size' =>  '40' ,


/*
 * If prefered to use your own custom image for non logged-in visitors
 * (perhaps your logo , a funny face or whatever) , 
 * then you can easily do so by supplying the URL to an image on your server . 
 * Gravatar will automatically serve up that custom image if there is no image 
 * associated with the requested email .
 * There are a few pre-requesities for this functionality though , 
 * (image-link shouldn't be defined by a query-string) 
 * read more on : https://el.gravatar.com/site/implement/images/ . 
 * An example :  'http://yourserver/img/dumy.jpeg' . 
 * Notice : Set the URL string  into single-quotes .
 * 
 */
'customGravUrl' => 'false' , 


/*
 * If curent user's email hasn't been registered on Gravater's API
 * a random or predefined Gravar will be returned instead .
 * Gravatar::get($size = null , $randomize = true ) 
 * If $randomize is set to "false" , then define an image from Gravatar's pool
 *  'mm' , 'identicon' , 'monsterid' , 'wavatar' , 'retro'
 *  Notice : This directive in only active if "customGravUrl" is set to false 
 */
'defGrav' =>  'retro' ,


/*
 * The email of a logged-in user is retrieved through the interface of
 * your app's Authentication adapter . 
 * Define which Authentication adapter is used in your current project
 * For now , three adapters are supported 'auth' , 'sentry' and "confide" 
 */
'authAdapter' => 'sentry' , 


/*
*Gravatar's API allows users to self-rate their images so that they 
* can indicate if an image is appropriate for a certain audience . 
* The following ratings are accepted :
* >> g : suitable for display on all websites with any audience type.
* >> r: may contain such things as violence, nudity, or hard drug use.
* >> x: may contain hardcore sexual imagery or extremely violence.
* >> pg: may contain rude gestures , swear words, or mild violence.
 */
'maxRating' => 'g' , 



	);
