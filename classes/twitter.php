<?php
/**
 * Twitter User Timeline Package
 *
 * @package    Twitter User Timeline
 * @version    1.0
 * @author     PHP Genie Development Team & http://erisds.co.uk/
 * @license    MIT License
 * @link       http://www.phpgenie.co.uk
 * @telephone  0845 689 0022
 * @email	   wish@phpgenie.co.uk
 */
 
class Twitter {

    public function buildBaseString($baseURI, $method, $params)
	{
	    $r = array(); 
	    ksort($params); 
	    foreach($params as $key=>$value){
	        $r[] = "$key=" . rawurlencode($value); 
	    }            
	
	    return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r)); //return complete base string
	}
	
	public function buildAuthorizationHeader($oauth)
	{
	    $r = 'Authorization: OAuth ';
	    $values = array();
	    foreach($oauth as $key=>$value)
	        $values[] = "$key=\"" . rawurlencode($value) . "\""; 
	
	    $r .= implode(', ', $values); 
	    return $r; 
	}
	
	public function fetchTweets($handle, $limit = 5) {
	
		$url = "http://api.twitter.com/1/statuses/user_timeline.json?screen_name=".$handle."&count=".$limit;
		
		$oauth_access_token =  \Config::get('twitter.oauth_consumer_key');
		$oauth_access_token_secret = \Config::get('twitter.oauth_token');;
		$consumer_key = \Config::get('twitter.consumer_key');;
		$consumer_secret = \Config::get('twitter.consumer_secret');;
		
		$oauth = array( 'oauth_consumer_key' => $consumer_key,
		                'oauth_nonce' => time(),
		                'oauth_signature_method' => 'HMAC-SHA1',
		                'oauth_token' => $oauth_access_token,
		                'oauth_timestamp' => time(),
		                'oauth_version' => '1.0');
		
		$base_info = Twitter::buildBaseString($url, 'GET', $oauth);
		$composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
		$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
		$oauth['oauth_signature'] = $oauth_signature;
		
		$header = array(Twitter::buildAuthorizationHeader($oauth), 'Expect:');
		$options = array( CURLOPT_HTTPHEADER => $header,
		                  CURLOPT_HEADER => false,
		                  CURLOPT_URL => $url,
		                  CURLOPT_RETURNTRANSFER => true,
		                  CURLOPT_SSL_VERIFYPEER => false);
		
		$feed = curl_init();
		curl_setopt_array($feed, $options);
		$json = curl_exec($feed);
		curl_close($feed);
		
		$twitter_data = json_decode($json, true);
		
		return $twitter_data;
	
	}
}