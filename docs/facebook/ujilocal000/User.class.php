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
	function sqlUpdate($userTbl,$userData)
	{
		return $query = ' UPDATE ' . $userTbl . ' SET' . "\r"
		. ' first_name = "' . $userData['first_name'] . '",' . "\r"
		. ' last_name = "' . $userData['last_name'] . '",' . "\r"
		. ' email = "' . $userData['email'] . '",' . "\r"
		. ' gender = "' . $userData['gender'] . '",' . "\r"
		. ' picture = "' . $userData['picture'] . '",' . "\r"
		. ' link = "' . $userData['link'] . '",' . "\r"
		. ' modified = NOW()' . "\r"
		. ' WHERE oauth_provider = "' . $userData['oauth_provider'] . '"'
		. ' AND oauth_uid = "' . $userData['oauth_uid'] . '"';
	}
#------------------------------------------------------------------------------------------
	function sqlInsert($userTbl,$userData)
	{
		return $query = 'INSERT INTO ' . $userTbl . ' SET' . "\r"
		. ' oauth_provider = "' . $userData['oauth_provider'] . '",' . "\r"
		. ' oauth_uid = "' . $userData['oauth_uid'] . '",' . "\r"
		. ' first_name = "' . $userData['first_name'] . '",' . "\r"
		. ' last_name = "' . $userData['last_name'] . '",' . "\r"
		. ' email = "' . $userData['email'] . '",' . "\r"
		. ' gender = "' . $userData['gender'] . '",' . "\r"
		. ' picture = "' . $userData['picture'] . '",' . "\r"
		. ' link = "' . $userData['link'] . '",' . "\r"
		. ' created = NOW(),'
		. ' modified = NOW()';
	}
#------------------------------------------------------------------------------------------
###########################################################################################
}