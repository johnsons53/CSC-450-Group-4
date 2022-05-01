    <!-- iepLogin.php - IEP Login
      Spring 100 CSC 450 Capstone, Group 4
      Author: Andy Yang
      Date Written: 02/21/2022
      Revised: 03/28/2022, Placed into php page
      Revised: 04/08/2022, Added functional connection to database
      Revised: 04/10/2022, Created switch statement that assigns current session user to user_type of user's login information
      Revised: 04/12/2022, Revised switch statement to ensure correct data present to create Admin, Provider and Student objects. (Lisa Ahnell)
      Revised: 04/19/2022, Added temporary user interface
      Revised: 04/30/2022, Deleted leftover code
      Revised: 04/30/2022, Added check for if user has logged in already
      Revised: 04/30/2022, Added alert for if user fails to login
    -->

    <?php

    include_once realpath("loginInitialization.php");
    global $conn;

    // Check if user is already logged in, if so redirects to iepDashboard.php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        header("location: iepDashboard.php");
        exit;
    }

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
            } else {
                echo ("<script> alert('The username or password you typed is incorrect. Please try again.'); location.href='iepLogin.php'; </script>");
                exit();
            }

            // Places userId and userType into SESSION along with a flag that checks if user has logged in, redirecting to dashboard 
            $_SESSION["currentUserId"] = $userId;
            $_SESSION["currentUserType"] = $userType;
            $_SESSION['loggedin'] = true;
            header("Location: iepDashboard.php");
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