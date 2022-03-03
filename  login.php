
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
     $db_server = mysql_connect($db_hostname, $db_username, $db_password);

     if (!$db_server) die("Unable to connect of MySQL: " . mysql_error());
     ?>
    */

    $db_hostname = 'localhost';
    $db_database = 'iep_portal';
    $db_username = 'add user name here';
    $db_password = 'add password here';
?>