<?
class browsers{
	public function browserCheck(){
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') )
		{
		   $browser = 'Safari';
		}
		else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') )
		{
		   if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape') )
		   {
			 $browser = 'Netscape (Gecko/Netscape)';
		   }
		   else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') )
		   {
			 $browser = 'Mozilla Firefox (Gecko/Firefox)';
		   }
		   else
		   {
			 $browser = 'Mozilla (Gecko/Mozilla)';
		   }
		}
		else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') )
		{
		   $browser = 'Internet Explorer (MSIE/Compatible)';
		}
		else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') === true)
		{
		   $browser = 'Opera';
		}
		else
		{
		   $browser = 'Other';
		}
		return($browser);
	}
}
$browsers = new browsers;
$browser=$browsers->browserCheck();

?> 
