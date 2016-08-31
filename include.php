# RTcamp_FB_Assignment

<?php
ini_set('max_execution_time', 300);
/**
 * it sets the application id and application secret id
 *
 */
$fb_app_id = '1779769635641437';//App ID
$fb_secret_id = 'a19ff266da230a9cc23de4899f49bceb';//App Secret

$fb_login_url = 'http://localhost/login/index.php';

require_once ('lib/Facebook/autoload.php');
/**
 *
 * define the namespace alies
 * for the use of facebook namespace
 * easy to use
 */
session_start();
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
/*setting application configuration
 and session
  */
FacebookSession::setDefaultApplication($fb_app_id, $fb_secret_id);
$helper = new FacebookRedirectLoginHelper($fb_login_url);

if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
	$session = new FacebookSession($_SESSION['fb_token']);
	try {
		if (!$session -> validate()) {
			$session = null;
		}
	} catch ( Exception $e ) {
		$session = null;
	}
}
if (!isset($session) || $session === null) {
	try {
		$session = $helper -> getSessionFromRedirect();

	} catch( FacebookRequestException $ex ) {
		print_r($ex);
	} catch( Exception $ex ) {
		print_r($ex);
	}
}
function getdatafromfaceboook($url) {
   
	$session = new FacebookSession($_SESSION['fb_token']);
	$request_userinfo = new FacebookRequest($session, 'GET', $url);
	$response_userinfo = $request_userinfo -> execute();
	$userinfo = $response_userinfo -> getGraphObject() -> asArray();

	return $userinfo;
}
?>
