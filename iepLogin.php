    <!-- iepLogin.php - IEP Login
      Spring 100 CSC 450 Capstone, Group 4
      Author: Andy Yang
      Date Written: 02/21/2022
      Revised: 03/28/2022, Placed into php page
      Revised: 04/08/2022, Added functional connection to database
      Revised: 04/10/2022, Created switch statement that assigns current session user to user_type of user's login information
      Revised: 04/12/2022, Revised switch statement to ensure correct data present to create Admin, Provider and Student objects. (Lisa Ahnell)
      Revised: 04/19/2022, Added temporary user interface
    -->

    <?php

    // Initialize the session
    //session_start();
    include_once realpath("initialization.php");
    //echo "session status: " . session_status() . "<br />";

    /* Will test feature when fully functional
    // Check if user is already logged in
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: iepLogin.php");
        exit;
    }
    */

    /*
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

    // Variable declaration
    $username;
    $userType;
    $userId;
    $password;
    $currentUser;

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
    */
    global $conn;

    // Username and password sent from form
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Define variables and initialize with empty values
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Checks if given login and password matches one in the database
        // Need to get additional information from database for Admin, Provider or student users!

        if (null !== (['username'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Prepare sql statement
            // I converted the database queries to prepared statements, a safer way to process data from input
            $stmt = $conn->prepare("SELECT user_id, user_type
                    FROM user
                    WHERE user_name=?
                    AND user_password=?
                    LIMIT 1");
            // Bind parameters
            $stmt->bind_param("ss", $username, $password);
            // Execute statment
            $stmt->execute();
            // Get result
            $result = $stmt->get_result();
            // Did we get one result?
            if ($result->num_rows == 1) {
                // Found username and password match, proceed to check user type
                while ($row = $result->fetch_assoc()) {
                    // Assign these values to variables outside of the conditional
                    $userType = $row["user_type"];
                    $userId = $row["user_id"];
                }

                /* Original query and Switch statement
            Query only retreived data from user table, and was not sufficient to create other types of users
             */
                //$sql = "SELECT * FROM user WHERE user_name = '$username' and user_password = '$password' limit 1";

                //$result = $conn->query($sql);

                //Places row into an array, all information of user
                //$row = mysqli_fetch_row($result);

                //if (mysqli_num_rows($result) == 1) {

                //Switch statement that checks for user type then creates object
                //of user type, sets session and current user to that newly created object
                //$row[10] is user_type
                /*                 switch ($row['user_type']) { 
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
                } */
                //header("Location: iepDashboard.php");
                //exit();
            } else {
                echo "You have entered incorrect information";
            }

            // Put userId and userType into SESSION
            $_SESSION["currentUserId"] = $userId;
            $_SESSION["currentUserType"] = $userType;

            /*
            /* Reconfiguring to only put userId and userType into SESSION, had trouble with data on Dashboard page. Rearranged Switch 
            If match is found for username and password, retrieve the appropriate data for the user type matching the saved user id
            switch ($userType) {
                case "admin":
                    // New call to database for Admin user data
                    $stmt = $conn->prepare(
                        "SELECT * 
                        FROM user
                        INNER JOIN admin
                        USING (user_id)
                        WHERE user_id=?
                        AND admin.admin_active=\"1\"");
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows == 1) {
                        while($row = $result->fetch_assoc()) {
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
                    }
                    //echo "This is a Admin account"; //Used to check account type
                    break;

                case "provider":
                    // New call to database for Provider user data
                    $stmt = $conn->prepare(
                        "SELECT * 
                        FROM user
                        INNER JOIN provider
                        USING (user_id)
                        WHERE user_id=?");
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows == 1) {
                        while($row = $result->fetch_assoc()) {

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
                    }                    
                    //echo "This is a Provider account"; //Used to check account type
                    break;

                case "user":
                    // New call to database for rest of user data
                    $stmt = $conn->prepare(
                        "SELECT * 
                        FROM user
                        WHERE user_id=?");
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows == 1) {
                        while($row = $result->fetch_assoc()) {
                            $guardian = new Guardian(
                                $row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                                $row['user_city'], $row['user_district'], $row['user_type']
                            );
        
                            // add current user to $_SESSION array
                            $_SESSION['currentUser'] = serialize($guardian);
                            $currentUser = $guardian;
                        }
                    }                    
                    //echo "This is a Guardian account"; //Used to check account type
                    break;

                case "student":
                    // New call to database for Student User data
                    $stmt = $conn->prepare(
                        "SELECT * 
                        FROM user
                        INNER JOIN student
                        USING (user_id)
                        WHERE user_id=?");
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows == 1) {
                        while($row = $result->fetch_assoc()) {
                            $student = new Student(
                                $row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                                $row['user_city'], $row['user_district'], $row['user_type'],
                                $row['student_id'], $row['provider_id'], $row['student_school'],
                                $row['student_grade'], $row['student_homeroom'], $row['student_dob'],
                                $row['student_eval_date'], $row['student_next_evaluation'], $row['student_iep_date'],
                                $row['student_next_iep'], $row['student_eval_status'], $row['student_iep_status']
                            );
        
                            // add current user to $_SESSION array
                            $_SESSION['currentUser'] = serialize($student);
                            $currentUser = $student;
                        }
                    } 
                    
                    //echo "This is a Student account"; //Used to check account type
                    break;
            }
            //$conn->close();
            */
            header("Location: iepSettings.php");
            exit();
        }
    }
    ?>

    <!DOCTYPE html>

    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>IEP Login</title>
        <link rel="stylesheet" type="text/css" href="iepLogin.css">
    </head>

    <body>
        <?php
        //echo "<form action=\"" . htmlspecialchars("iepDashboard.php") . "\" method=\"post\">";
        ?>
        <form method="POST" action="#">


            <!-- Text field for username_Login-->
            <div class="wrapper">
            <h1 style="text-align:center;font-size:40px">IEP Login</h1>
                <label for="username_Login">Username</label> 
                <input type="text" placeholder="Enter Username" name="username" required>

                <label for="password_Login">Password</label>
                <input type="password" placeholder="Enter Password" name="password" required>

                <input type="submit" class="btn btn-primary" value="Login">
            </div>

        </form>
        </div>

    </body>

    </html>