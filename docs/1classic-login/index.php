<?php
if (!isset($_SERVER['PHP_AUTH_USER']))
{
	header('WWW-Authenticate: Basic realm="My Realm"');
	header('HTTP/1.0 401 Unauthorized');
	//echo 'Text to send if user hits Cancel button';
	echo 'Kita berjanji tidak akan berkongsi kasih<br>
		eh salah berkongsi email dengan orang lain.';
	echo '<p> <a href=\"logout.php\">Take Me At Home</a> </p>';
	exit;
}
else
{
	# debug array
	$teka[] = 'A';
	$teka[] = 'B';
	echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
	echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
	echo "<p>This is {$teka[0]} </p>";
	echo "<p>This is {$teka[1]} </p>";
	echo '<pre>';
	print_r($_COOKIE);
	echo '</pre>';
	echo "<p> <a href=\"logout.php\">Logout here</a> </p>";
}
?>