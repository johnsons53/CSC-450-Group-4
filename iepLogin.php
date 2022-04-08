    <!-- iepLogin.php - IEP Login
      Spring 100 CSC 450 Capstone, Group 4
      Author: Andy Yang
      Date Written: 02/21/2022
      Revised: 03/28/2022, Placed into php page
      Revised: 04/08/2022, Added functional connection to database
    -->

    <?php

    /* Will test when given further direction
    // Initialize the session
    session_start();

    // Check if user is already logged in
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: welcome.php");
        exit;
    }
    */

    // Taken from login.php
    $filepath = realpath('login.php');
    $config = require $filepath;
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

    // Username and password sent from form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Define variables and initialize with empty values
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Checks if given login and password matches one in the database
        // If it does then it'll display sucessful log in, if not it'll display incorrect 
        if (null !== (['username'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT * FROM user WHERE user_name = '$username' and user_password = '$password' limit 1";

            $result = $conn->query($sql);

            if (mysqli_num_rows($result) == 1) {
                echo "You have successfully logged in";
                exit();
            } else {
                echo "You have entered incorrect information";
            }
        }
    }
    ?>

    <!DOCTYPE html>

    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>IEP Login</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
        <form method="POST" action="#">
            <header>
                <!-- Insert logo image here -->
                <h1>IEP Login</h1>

            </header>

            <!-- Text field for username_Login-->
            <div>
                <label for="username_Login">Username</label>
                <input type="text" placeholder="Enter Username" name="username" required>
            </div>

            <!-- Text field for password_Login-->
            <div>
                <label for="password_Login">Password</label>
                <input type="password" placeholder="Enter Password" name="password" required>
            </div>

            <!--Login button to login-->
            <div>
                <input type="submit" class="btn btn-primary" value="Login">
            </div>

        </form>
        </div>

    </body>

    </html>