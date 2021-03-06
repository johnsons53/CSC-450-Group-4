<?php
/*
goalForm.php - Provider Goal Form
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 04/10/2022
      Revised: 04/19/2022 : Removed notes from testing
*/
// error reporting for web app
// for testing:
error_reporting((E_ALL | E_STRICT));

// start session 
session_start();

// Token for this session, check to see if expired
if (!isset($_SESSION["token"]) || time() > $_SESSION["tokenExpires"]) {
    // set token and time
    $_SESSION["token"] = bin2hex(openssl_random_pseudo_bytes(16));
    $_SESSION["tokenExpires"] = time() + 900;
    $_SESSION["logId"] = 1;
}

// include constants defined on login.php
include_once realpath("login.php");

// Database connection
$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  exit(0);
} else {
    //echo "Database Connection created \n";
}

// include other backend files for page

include_once realpath('User.php');
require_once realpath('Admin.php');
require_once realpath('Document.php');
require_once realpath('Goal.php');
require_once realpath('Guardian.php');
require_once realpath('Provider.php');
require_once realpath('Report.php');
require_once realpath('Objective.php');
require_once realpath('Student.php');
require_once realpath('SentMessage.php');
require_once realpath('ReceivedMessage.php');
require_once realpath('functions.php');
