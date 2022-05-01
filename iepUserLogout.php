<!-- /*
iepUserLogout.php - Logout user. Unset session variables, alert user of logout, redirect to login page.
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Created: 04/30/2022

*/ -->
<?php

session_start();

// Unset all session variables
session_unset();

?>
<!-- Redirect to login page -->
<script>
    alert("Logged out! Thank you for using the iepPortal.")
</script>
<meta http-equiv="refresh" content="0;iepLogin.php">