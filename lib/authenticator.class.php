<?php

require_once(dirname(__FILE__) "/configuration.class.php");

/*
	DIM_Authenticator
	
	Deals with all the authentication required by the DIM_Server
*/
class DIM_Authenticator {
	
	/*
		->__construct()
	*/
	public __construct() {
	
	}

	/*
		->userAuthenticates($email, $authKey)
		Checks with the configuration to determine whether or
		not authentication is valid.
		@params
			$email - the user email
			$authKey - the user authentication key
		@returns
			true/false based on whether authentication was succesful
	*/
	public function userAuthenticates($email, $authKey) {
		$cfg = DIM_Configuration::getConfiguration();
		if($cfg && is_array($cfg["server"]["users"]) {
			foreach($cfg["server"]["users"] as $u) {
				if($email == $u['email'] && $authKey == $u['auth-key']) {
					return true;
				}			
			}
			// we haven't matched yet
			return false;
		}
		else {
			return false;
		}
	}
}

?>