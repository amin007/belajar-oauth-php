<?php
###########################################################################################
# include fail dari github dalam bentuk class
include '../../../i-tatarajah.php';# untuk capaian localhost
include '../../../i-listfiles.php';# untuk header dan footer
$tajuk = Tajuk_Muka_Surat;
# kod rahsia untuk github oauth-php
$clientID = ClientID;
$clientSecret = ClientSecret;
# semak data dulu
//echo '<br>$clientID = ' . $clientID;
//echo '<br>$clientSecret = ' . $clientSecret;
###########################################################################################

diatas('Github Local 000');
#------------------------------------------------------------------------------------------
// https://gist.github.com/aaronpk/3612742
define('OAUTH2_CLIENT_ID', $clientID);
define('OAUTH2_CLIENT_SECRET', $clientSecret);

$authorizeURL = 'https://github.com/login/oauth/authorize';
$tokenURL = 'https://github.com/login/oauth/access_token';
$apiURLBase = 'https://api.github.com/';
#------------------------------------------------------------------------------------------
session_start();
#------------------------------------------------------------------------------------------
# 1. Start the login process by sending the user to Github's authorization page
if(get('action') == 'login')
{

	# Generate a random hash and store in the session for security
	$_SESSION['state'] = hash('sha256', microtime(TRUE).rand().$_SERVER['REMOTE_ADDR']);
	unset($_SESSION['access_token']);
	$params = array(
		'client_id' => OAUTH2_CLIENT_ID,
		'redirect_uri' => 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'],
		'scope' => 'user',
		'state' => $_SESSION['state']
	);

	# Redirect the user to Github's authorization page
	header('Location: ' . $authorizeURL . '?' . http_build_query($params));
	die();
}
#------------------------------------------------------------------------------------------
# 2. When Github redirects the user back here, there will be a "code" and "state"
# parameter in the query string
if(get('code'))
{
	# Verify the state matches our stored state
	if(!get('state') || $_SESSION['state'] != get('state'))
	{
		header('Location: ' . $_SERVER['PHP_SELF']);
		die();
	}

	# Exchange the auth code for a token
	$token = apiRequest($tokenURL, array(
		'client_id' => OAUTH2_CLIENT_ID,
		'client_secret' => OAUTH2_CLIENT_SECRET,
		'redirect_uri' => 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'],
		'state' => $_SESSION['state'],
		'code' => get('code')
	));

	// $_SESSION['access_token'] = $token->access_token;

	header('Location: ' . $_SERVER['PHP_SELF']);
}
#------------------------------------------------------------------------------------------
# 3. check if session valid or not
if(session('access_token'))
{
	$user = apiRequest($apiURLBase . 'user');

	echo '<h3>Logged In</h3>';
	echo '<h4>' . $user->name . '</h4>';
	echo '<h4>' . $user->id . '</h4>';
	echo '<img src="' . $user->avatar_url . '">';
	//echo '<pre>'; print_r($user); echo '</pre>';
}
else
{
	echo '<h3>Not logged in</h3>';
	echo '<p><a href="?action=login"'
	. 'class="btn btn-secondary btn-block text-white">Log In Github '
	. '<i class="fab fa-github"></i></a></p>';
}
#------------------------------------------------------------------------------------------

###########################################################################################
#------------------------------------------------------------------------------------------
function apiRequest($url, $post=FALSE, $headers=array())
{
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');

	if($post)
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

	$headers[] = 'Accept: application/json';

	if(session('access_token'))
	{
		$headers[] = 'Authorization: Bearer ' . session('access_token');
	}

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	//echo '<pre>$response:'; print_r($response); echo '</pre>';

	return json_decode($response);
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
dibawah();