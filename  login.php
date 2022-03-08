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
      Date Written: 03/03/2022
      Revised: 03/07/2022 - modify method of storing data on page, update connection code'
      removed testing notes and old functionality.
      */

    /* Replace these values for use on your local host, will update for site hosting.
     To use on other pages include this:
     <?php
      $config = require ' login.php'; 
      $db_hostname = $config['DB_HOSTNAME'];
      $db_username = $config['DB_USERNAME'];
      $db_password = $config['DB_PASSWORD'];
      $db_database = $config['DB_DATABASE'];
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
    return [
      "DB_HOSTNAME" => "localhost",
      "DB_DATABASE" => 'iep_portal',
      "DB_USERNAME" => 'root',
      'DB_PASSWORD' => 'root'

    ];    
?>

</body>
</html>


