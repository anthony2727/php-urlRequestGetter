<?php 
namespace Anthony\App;
/*
Author: Anthony Rodriguez

This function returns an array with the request value sent by the user.
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

RewriteRule ^.*$ ./index.php  

where ./index.php is our main file for request handling  

*/
class ProcessUrlRequest {
	
	private $rootDirectoryElements;
	private $errors = [];
	const ROOT_DIR = __DIR__;
	
	public function __construct(){
		$this->rootDirectoryElements = $this->splitString('/',self::ROOT_DIR);
	}

	/*
	*	@param String $uri
	* 	@return array 
	*/
	public function getRequest($uri){
		$request = $this->splitString('/', $uri);
		return $this->processUriRequest($this->rootDirectoryElements, $request);
	}
	/*
	* @param $character
	* @param $mString
	* @return array 
	*/
	private function splitString($character,$mString){
		return  explode($character,$mString); // Repeated
	}
	/*
	* 	@return array 
	*/
	public function getErrors()
	{
		return $this->errors;
	}

	/*  ProcessUriRequest returns the intended request from the URL recursively.
	*	@param String $dir
	*	@param String $req	
	* 	@return array 
	*/
	private function processUriRequest($dir,$req){
		if(!count($dir)){
			return $req;
		}
		if($dir[0] == $req[0]){
			array_shift($req);
		}
		array_shift($dir);
		$request = $this->processUriRequest($dir,$req);

		return $request;
	}
}

?>