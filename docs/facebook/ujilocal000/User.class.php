<?php
/*
 * User Class
 * This class is used for database related (connect, insert, and update) operations
 * @author    CodexWorld.com
 * @url        http://www.codexworld.com
 * @license    http://www.codexworld.com/license
 */
class User
{
###########################################################################################
#------------------------------------------------------------------------------------------
	private $dbHost     = DB_HOST;
	private $dbUsername = DB_NAME;
	private $dbPassword = DB_PASS;
	private $dbName     = DB_NAME;
	private $userTbl    = JADUAL00;
#------------------------------------------------------------------------------------------
	function __construct()
	{
		if(!isset($this->db))
		{
			#Connect to the database
			$conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
			if($conn->connect_error)
				die("Failed to connect with MySQL: " . $conn->connect_error);
			else
				$this->db = $conn;
		}
	}
#------------------------------------------------------------------------------------------
	function checkUser($userData = array())
	{
		if(!empty($userData))
		{
			# Check whether user data already exists in database
			$prevQuery = 'SELECT * FROM ' . $this->userTbl . "\r"
			. ' WHERE oauth_provider = "' . $userData['oauth_provider'] . '"' . "\r"
			. ' AND oauth_uid = "' .$userData['oauth_uid'] . '"';
			$prevResult = $this->db->query($prevQuery);
			###----------------------------------------------------------------------------
			if($prevResult->num_rows > 0)
				# Update user data if already exists
				$update = $this->db->query($this->sqlUpdate($this->userTbl,$userData));
			else #Insert user data
				$insert = $this->db->query($this->sqlInsert($this->userTbl,$userData));
			###----------------------------------------------------------------------------
			# Get user data from the database
			$result = $this->db->query($prevQuery);
			$userData = $result->fetch_assoc();
		}
		###--------------------------------------------------------------------------------
		# Return user data
		return $userData;
	}
#------------------------------------------------------------------------------------------
	function sqlCreate($userTbl='users')
	{
		return $sql = 'CREATE TABLE `' . $userTbl . '` ('
		. "\r" . ' `id` int(11) NOT NULL AUTO_INCREMENT,'
		. "\r" . " `oauth_provider` enum('','facebook','google','twitter')"
		. ' COLLATE utf8_unicode_ci NOT NULL,'
		. "\r" . ' `oauth_uid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,'
		. "\r" . ' `first_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,'
		. "\r" . ' `last_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,'
		. "\r" . ' `email` varchar(25) COLLATE utf8_unicode_ci NOT NULL,'
		. "\r" . ' `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,'
		. "\r" . ' `picture` varchar(200) COLLATE utf8_unicode_ci NOT NULL,'
		. "\r" . ' `link` varchar(100) COLLATE utf8_unicode_ci NOT NULL,'
		. "\r" . ' `created` datetime NOT NULL,'
		. "\r" . ' `modified` datetime NOT NULL,'
		. "\r" . ' PRIMARY KEY (`id`)'
		. "\r" . ' ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;';
	}
#------------------------------------------------------------------------------------------
	function sqlUpdate($userTbl,$userData)
	{
		return $sql = ' UPDATE `' . $userTbl . '` SET'
		. "\r" . ' `first_name` = "' . $userData['first_name'] . '",'
		. "\r" . ' `last_name` = "' . $userData['last_name'] . '",'
		. "\r" . ' `email` = "' . $userData['email'] . '",'
		. "\r" . ' `gender` = "' . $userData['gender'] . '",'
		. "\r" . ' `picture` = "' . $userData['picture'] . '",'
		. "\r" . ' `link` = "' . $userData['link'] . '",'
		. "\r" . ' `modified` = NOW()'
		. "\r" . ' WHERE `oauth_provider` = "' . $userData['oauth_provider'] . '"'
		. "\r" . ' AND `oauth_uid` = "' . $userData['oauth_uid'] . '"';
	}
#------------------------------------------------------------------------------------------
	function sqlInsert($userTbl,$userData)
	{
		return $sql = 'INSERT INTO `' . $userTbl . '` SET'
		. "\r" . ' `oauth_provider` = "' . $userData['oauth_provider'] . '",'
		. "\r" . ' `oauth_uid` = "' . $userData['oauth_uid'] . '",'
		. "\r" . ' `first_name` = "' . $userData['first_name'] . '",'
		. "\r" . ' `last_name` = "' . $userData['last_name'] . '",'
		. "\r" . ' `email` = "' . $userData['email'] . '",'
		. "\r" . ' `gender` = "' . $userData['gender'] . '",'
		. "\r" . ' `picture` = "' . $userData['picture'] . '",'
		. "\r" . ' `link` = "' . $userData['link'] . '",'
		. "\r" . ' `created` = NOW(),'
		. "\r" . ' `modified` = NOW()';
	}
#------------------------------------------------------------------------------------------
###########################################################################################
}