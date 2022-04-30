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