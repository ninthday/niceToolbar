<?php
/**
 * Google API object init file
 * 
 * @author ninthday <jeffy@ninthday.info>
 */

require_once _APP_PATH . 'inc/gAuthConfig.inc.php';
set_include_path(get_include_path() . PATH_SEPARATOR . _APP_PATH . 'resources/google-api-php-client/src/');

//Google API PHP Library includes
require_once 'Google/Client.php';
require_once 'Google/Service/Oauth2.php';


$gClient = new Google_Client();
$gClient->setApplicationName($application_name);
$gClient->setClientId($client_id);
$gClient->setClientSecret($client_secret);
$gClient->setRedirectUri($redirect_uri);
$gClient->setDeveloperKey($simple_api_key);
$gClient->addScope("https://www.googleapis.com/auth/userinfo.email");

//Send Client Request
$objOAuthService = new Google_Service_Oauth2($gClient);
