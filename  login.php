<!DOCTYPE html>
<html>
<head>
  <title>login</title>
</head>
<body>
<?php // login.php
    /* login.php - Database Login information
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 03/23/2022
      Revised: n/a
      */

    /* Replace these values for use on your local host, will update for site hosting.
     To use on other pages include this:
     <?php
     require_once 'login.php';
    // Create connection
    $conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Connected successfully. <br />";

    $conn->close();

    echo "Connection closed.";
     ?>
    */

    echo "This message is on the login page. <br />";
    return [
      "DB_HOSTNAME" => "localhost",
      "DB_DATABASE" => 'iep_portal',
      "DB_USERNAME" => 'root',
      'DB_PASSWORD' => 'root'

    ];

    //$db_hostname = 'localhost';
    //$db_database = 'iep_portal';
    //$db_username = 'root';
    //$db_password = 'root';

    
?>

</body>
</html>


