<?php

/*
	Network_IO

	Provides outward communications functionality (primarily for
	the client-side code), mostly through static functions.
*/
class Network_IO {

	/*
		::makeServerRequest()
		Makes a CURL request to the specified host and
		returns the result.
		@params
			$host - the host to send the request to
			$data - array(), the data to send to the server.
		@returns
			mixed - whatever the server returns
	*/
	public static function makeServerRequest($host, $data) {

		$url = self::getUrlFromHost($host);

		$str = array();
		foreach($data as $key => $value){
				$str[] = $key . '=' . urlencode($value);
		}
		$postFieldStr = implode('&', $str);		
				
		set_time_limit(60);
		$curlSession = curl_init();
		
		curl_setopt($curlSession, CURLOPT_URL, $url);
		curl_setopt($curlSession, CURLOPT_HEADER, 0);
		curl_setopt($curlSession, CURLOPT_POST, 1);
		curl_setopt($curlSession, CURLOPT_POSTFIELDS, $postFieldStr);
		
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curlSession, CURLOPT_TIMEOUT,30);
		
		curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, FALSE);		
	
		$response = curl_exec($curlSession);
		
		curl_close($curlSession);
		
		return $response;
	
	}

	/*
		::isActiveUrl($host)
		Determines if the specified host is alive - taken from http://www.secondversion.com/blog/php-check-if-a-url-is-valid-exists/
		@params
			$host - the host to test
		@returns
			true/false based on success or failure
	*/
	public static function isActiveUrl($host)
	{
		$url = self::getUrlFromHost($host);
		
		if (!($url = @parse_url($url)))
		{
			return false;
		}

		$url['port'] = (!isset($url['port'])) ? 80 : (int)$url['port'];
		$url['path'] = (!empty($url['path'])) ? $url['path'] : '/';
		$url['path'] .= (isset($url['query'])) ? "?$url[query]" : '';

		if (isset($url['host']))
		{
			if (PHP_VERSION >= 5)
			{
				$headers = @implode('', @get_headers("$url[scheme]://$url[host]:$url[port]$url[path]"));
			}
			else
			{
				if (!($fp = @fsockopen($url['host'], $url['port'], $errno, $errstr, 10)))
				{
					return false;
				}
				fputs($fp, "HEAD $url[path] HTTP/1.1\r\nHost: $url[host]\r\n\r\n");
				$headers = fread($fp, 4096);
				fclose($fp);
			}
			return (bool)preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers);
		}
		
		return false;
	}
	
	/*
		::getUrlFromHost($host)
		Returns the full request URL from the specified host.
		@params
			$host - the host to get the URL for.
		@returns
			string - the full URL.
	*/
	public static function getUrlFromHost($host) {		
		$url = "http://{$host}/extensions/database_integration_manager/server/index.php";
		return $url;
	}
	
}

?>