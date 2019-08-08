<?php
###########################################################################################
# include fail dari github dalam bentuk class
include '../../../i-tatarajah.php';# untuk capaian localhost
include '../../../../php-graph-sdk/src/Facebook/autoload.php';#
//require_once 'config.php';# Include configuration file
include '../../../i-listfiles.php';# untuk header dan footer
$tajuk = Tajuk_Muka_Surat;
$output = null;
# kod rahsia untuk facebook oauth-php
# semak data dulu
//echo '<br>$clientID = ' . $clientID;
//echo '<br>$clientSecret = ' . $clientSecret;
###########################################################################################

#------------------------------------------------------------------------------------------
if(!session_id())
{
	session_start();
}
#------------------------------------------------------------------------------------------
// https://www.codexworld.com/login-with-facebook-using-php/
require_once '../fbConfig.php';# FB Config
require_once 'User.class.php';# Include User class

if(isset($accessToken))
{
	list($oAuth2Client,$longLivedAccessToken,$graphResponse,$fbUser)
		= semakFB($accessToken);

	$user = new User();# Initialize User class
	$fbUserData = susunDataFB($fbUser);# Getting user's profile data
	$userData = $user->checkUser($fbUserData);# Insert/update user data to the database
	$_SESSION['userData'] = $userData;# Storing user data in the session

	# Get logout url
	$logoutURL = $helper->getLogoutUrl($accessToken, FB_REDIRECT_URL . 'logout.php');
	# Render Facebook profile data
	$output = paparFBUser($userdata,$logoutURL);
}
else
{
	# Get login url
	$permissions = ['email'];# Optional permissions
	$loginURL = $helper->getLoginUrl(FB_REDIRECT_URL, $permissions);
	//$loginURL = '?get=code';# contoh sahaja

	# Render Facebook login button
	$output  = '<a href="' . htmlspecialchars($loginURL) . '"';
	$output .= ' class="btn btn-secondary btn-block text-white">';
	$output .= 'Login Dari Facebook <i class="fab fa-facebook"></i></a>';
}
#------------------------------------------------------------------------------------------
diatas('Facebook Local 000');
echo $output;# papar output di sini
dibawah();
#------------------------------------------------------------------------------------------

###########################################################################################
#------------------------------------------------------------------------------------------
function semakFB($accessToken)
{
	if(isset($_SESSION['facebook_access_token']))
	{
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}
	else
	{
		# Put short-lived access token in session
		$_SESSION['facebook_access_token'] = (string) $accessToken;

		# OAuth 2.0 client handler helps to manage access tokens
		$oAuth2Client = $fb->getOAuth2Client();

		# Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

		# Set default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
	}
	###------------------------------------------------------------------------------------
	# Redirect the user back to the same page if url has "code" parameter in query string
	if(isset($_GET['code']))
	{
		header('Location: ./');
	}
	###------------------------------------------------------------------------------------
	# Getting user's profile info from Facebook
	try
	{
		$graphResponse = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,picture');
		$fbUser = $graphResponse->getGraphUser();
	}
	catch(FacebookResponseException $e)
	{
		echo 'Graph returned an error: ' . $e->getMessage();
		session_destroy();
		header("Location: ./");# Redirect user back to app login page
		exit;
	}
	catch(FacebookSDKException $e)
	{
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
    }
	###------------------------------------------------------------------------------------
	return array($oAuth2Client,$longLivedAccessToken,$graphResponse,$fbUser);
}
#------------------------------------------------------------------------------------------
function susunDataFB($fbUser)
{
	$fbUserData = array();
	$fbUserData['oauth_uid']  = !empty($fbUser['id'])?$fbUser['id']:'';
	$fbUserData['first_name'] = !empty($fbUser['first_name'])?$fbUser['first_name']:'';
	$fbUserData['last_name']  = !empty($fbUser['last_name'])?$fbUser['last_name']:'';
	$fbUserData['email']      = !empty($fbUser['email'])?$fbUser['email']:'';
	$fbUserData['gender']     = !empty($fbUser['gender'])?$fbUser['gender']:'';
	$fbUserData['picture']    = !empty($fbUser['picture']['url'])?$fbUser['picture']['url']:'';
	$fbUserData['link']       = !empty($fbUser['link'])?$fbUser['link']:'';
	$fbUserData['oauth_provider'] = 'facebook';
	###------------------------------------------------------------------------------------
	return $fbUserData;
}
#------------------------------------------------------------------------------------------
function paparFBUser($userdata,$logoutURL)
{
	if(!empty($userData))
	{
		$p  = '<h2>Facebook Profile Details</h2>';
		$p .= '<div class="ac-data">';
		$p .= '<img src="' . $userData['picture'] . '"/>';
		$p .= '<p><b>Facebook ID:</b> ' . $userData['oauth_uid'] . '</p>';
		$p .= '<p><b>Name:</b> ' . $userData['first_name'];
		$p .= ' ' . $userData['last_name'] . '</p>';
		$p .= '<p><b>Email:</b> ' . $userData['email'] . '</p>';
		$p .= '<p><b>Gender:</b> '.$userData['gender'] . '</p>';
		$p .= '<p><b>Logged in with:</b> Facebook</p>';
		$p .= '<p><b>Profile Link:</b> <a href="' . $userData['link'] . '"';
		$p .= ' target="_blank">Click to visit Facebook page</a></p>';
		$p .= '<p><b>Logout from <a href="' . $logoutURL . '">Facebook</a></p>';
		$p .= '</div>';
	}
	else
	{
		$p = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
	}
	###------------------------------------------------------------------------------------
	return $p;
}
#------------------------------------------------------------------------------------------
function get($key, $default=NULL)
{
	return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}
#------------------------------------------------------------------------------------------
function session($key, $default=NULL)
{
	return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}
#------------------------------------------------------------------------------------------
###########################################################################################