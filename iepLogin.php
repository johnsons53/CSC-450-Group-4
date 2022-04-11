    <!-- iepLogin.php - IEP Login
      Spring 100 CSC 450 Capstone, Group 4
      Author: Andy Yang
      Date Written: 02/21/2022
      Revised: 03/28/2022, Placed into php page
      Revised: 04/08/2022, Added functional connection to database
      Revised: 04/10/2022, Created switch statement that assigns current session user to user_type of user's login information
    -->

    <?php

    // Initialize the session
    session_start();

    /* Will test feature when fully functional
    // Check if user is already logged in
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: iepLogin.php");
        exit;
    }
    */

    // Taken from login.php
    $filepath = realpath('login.php');
    $config = require $filepath;

    //Including other class pages to create user objects
    require_once realpath('User.php');
    require_once realpath('Admin.php');
    require_once realpath('Document.php');
    require_once realpath('Goal.php');
    require_once realpath('Guardian.php');
    require_once realpath('Provider.php');
    require_once realpath('Report.php');
    require_once realpath('Objective.php');
    require_once realpath('Student.php');

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
        if (null !== (['username'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $sql = "SELECT * FROM user WHERE user_name = '$username' and user_password = '$password' limit 1";

            $result = $conn->query($sql);

            //Places row into an array, all information of user
            $row = mysqli_fetch_row($result);

            if (mysqli_num_rows($result) == 1) {

                //Switch statement that checks for user type then creates object
                //of user type, sets session and current user to that newly created object
                //$row[10] is user_type
                switch ($row[10]) { 
                    case "admin":
                        while ($row = $result->fetch_assoc()) {
                            $admin = new Admin(
                                $row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                                $row['user_city'], $row['user_district'], $row['user_type'],
                                $row['admin_id'], $row['admin_active']
                            );

                            // add current user to $_SESSION array
                            $_SESSION['currentUser'] = serialize($admin);
                            $currentUser = $admin;
                        }
                        //echo "This is a Admin account"; //Used to check account type
                        break;

                    case "provider":
                        while ($row = $result->fetch_assoc()) {
                            $provider = new Provider(
                                $row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                                $row['user_city'], $row['user_district'], $row['user_type'],
                                $row['provider_id'], $row['provider_title']
                            );

                            // add current user to $_SESSION array
                            $_SESSION['currentUser'] = serialize($provider);
                            $currentUser = $provider;
                        }
                        //echo "This is a Provider account"; //Used to check account type
                        break;

                    case "user":
                        while ($row = $result->fetch_assoc()) {
                            $provider = new Guardian(
                                $row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                                $row['user_city'], $row['user_district'], $row['user_type'],
                            );

                            // add current user to $_SESSION array
                            $_SESSION['currentUser'] = serialize($guardian);
                            $currentUser = $guardian;
                        }
                        //echo "This is a Guardian account"; //Used to check account type
                        break;

                    case "student":
                        while ($row = $result->fetch_assoc()) {
                            $student = new Student(
                                $row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                                $row['user_city'], $row['user_district'], $row['user_type'],
                                $row['student_id'], $row['provider_title'], $row['student_school'],
                                $row['student_grade'], $row['student_homeroom'], $row['student_dob'],
                                $row['student_eval_date'], $row['student_next_evaluation'], $row['student_iep_date'],
                                $row['student_next_iep'], $row['student_eval_status'], $row['student_iep_status'],
                            );

                            // add current user to $_SESSION array
                            $_SESSION['currentUser'] = serialize($student);
                            $currentUser = $student;
                        }
                        //echo "This is a Student account"; //Used to check account type
                        break;
                }
                header("Location: iepDashboard.php");
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