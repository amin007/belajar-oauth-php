<?php
//echo '<hr><h2>FB Config</h2><hr>';

# Include the autoloader provided in the SDK
//require_once __DIR__ . '/facebook-php-graph-sdk/autoload.php';

# Include required libraries
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

# Call Facebook API
$fb = new Facebook(array(
	'app_id' => FB_APP_ID,
	'app_secret' => FB_APP_SECRET,
	'default_graph_version' => 'v3.2',
));

# Get redirect login helper
$helper = $fb->getRedirectLoginHelper();

# Try to get access token
try
{
	if(isset($_SESSION['facebook_access_token']))
		$accessToken = $_SESSION['facebook_access_token'];
	else
		$accessToken = $helper->getAccessToken();
}
catch(FacebookResponseException $e)
{
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
}
catch(FacebookSDKException $e)
{
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
}
//*/