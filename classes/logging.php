<?php
class logging{
		
	public function visitorLog(){
		global $ip, $page, $url, $webhost, $server, $server_user, $method, $referer, $protocol, $browser;
		if($page == ""){
			$page = "Home";
		}
    
		if(isset($_GET['section']) && $_GET['section']!='log'){
				$ip = mysqli_real_escape_string ( $this->link , $ip );
				$url = mysqli_real_escape_string ( $this->link , $url);

				$sql = "INSERT INTO blog_log (ip, datum, page, gebruiker, url, browser, webhost, os_server, os_user, referrer, protocol, method) VALUES('".$ip."', now(), '$page', '".$_SESSION['admin_name']."', '".$url."','$browser','$webhost','$server','$server_user','$referer','$protocol','$method')";
				if(!$result = $this->link->query($sql))
					$this->db_message($sql);
		}
	}
	
	public function findCountry(){
		global $ip;
		$ipAddr = $ip;
		//verify the IP address for the
		ip2long($ipAddr)== -1 || ip2long($ipAddr) === false ? trigger_error("Invalid IP", E_USER_ERROR) : "";
		$ipDetail=array(); //initialize a blank array
		
		//get the XML result from hostip.info
		$xml = file_get_contents("http://api.hostip.info/?ip=".$ipAddr);
		
		//get the city name inside the node <gml:name> and </gml:name>
		preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si",$xml,$match);
		
		//assing the city name to the array
		$ipDetail['city']=$match[2]; 
		
		//get the country name inside the node <countryName> and </countryName>
		preg_match("@<countryName>(.*?)</countryName>@si",$xml,$matches);
		
		//assign the country name to the $ipDetail array
		$ipDetail['country']=$matches[1];
		
		//get the country name inside the node <countryName> and </countryName>
		preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si",$xml,$cc_match);
		$ipDetail['country_code']=$cc_match[1]; //assing the country code to array
		
		//return the array containing city, country and country code
		$_SESSION["country"] = $ipDetail['country'];
		
		//return $country;
	}

	
}

$logging = new logging;
$logging->visitorLog();
$logging = NULL;

?> 
