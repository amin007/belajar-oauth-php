<?php
//session_start();
unset($_SERVER['PHP_AUTH_USER']);
unset($_SERVER['PHP_AUTH_PW']);
unset($_COOKIE['PHPSESSID']);
//unset($_SESSION);
//session_destroy();
//header('location:../');
header('HTTP/1.0 401 Unauthorized');
//header('location:../');
exit();