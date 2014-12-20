<?php 
namespace Anthony\App;
/*
Author: Anthony Rodriguez

--THIS IS AN IMPROVEMENT VERSION OF ProcessUrlRequest--

This class returns an array of values which represent the request sent by the user using the GET METHOD.
The main purpose is to separate or strip the protocol, domain and site path from the URL request sent by the user.

i.e : Http://localhost/site/subpath/controller/parameter1/parameter2

From the previous URL the ProcessUrlRequest class will return :

array(3) { [0]=> string(10) "controller" [1]=> string(10) "parameter1" [2]=> string(10) "parameter2" } 

Useful when implementing Routes in a MVC pattern model. 

NOTE: This class works only when using the Apache RewriteEngine On. RewriteEngine is a module that rewrite the 
URL based on the Conditions specified. For our case, we used the below configuration in a .htaccess file.

.htaccess FILE

Options -Multiviews +FollowSymLinks
RewriteEngine On

RewriteCond %{SCRIPT_NAME} !-d
RewriteCond %{SCRIPT_NAME} !-f

RewriteRule ^(.+)$ ./index.php?get=$1 [QSA,L]  

where ./index.php is our main file for request handling  

*/
class ProcessUrlRequest {
	
	private $errors = [];
	private $parts = [];

	public function __construct($uri){
		$this->parts =  $this->processUriRequest($uri);
	}
	
	/*
	*	@param String $uri
	* 	@return array 
	*/
	public function getRequest(){
		return $this->parts;	
	}
	/*
	* 	@return array 
	*/
	public function getErrors()
	{
		return $this->errors;
	}

	public function splitString($mString){
		return explode('/', $mString);
	}

	public function sanitizeUrl($url){
		 $sanitizedUrl = filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL);
		 return $sanitizedUrl;
	}

	/*  ProcessUriRequest returns the intended request from the URL recursively.
	*	@param String $req	
	* 	@return array 
	*/
	private function processUriRequest($req){
		$urlString = $this->sanitizeUrl($req);
		$urlParts = $this->splitString($urlString);
		return $urlParts;
	}
}

?>