      <!-- iepSettings.php - IEP Settings
      Spring 100 CSC 450 Capstone, Group 4
      Author: Andy Yang
      Date Written: 03/2/2022
      Revised: 04/18/2022, Intergrated user interface and now displays user information
    -->

      <?php

      // Initialize the session
      session_start();
      //echo "session status: " . session_status() . "<br />";

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

      // Unserialize and set as currentUser value
      $currentUser = unserialize($_SESSION["currentUser"]);

      // Save user information to be displayed
      $currentUserFullName = $currentUser->get_full_name();
      $currentUserType = $currentUser->get_user_type();
      $currentUsername = $currentUser->get_user_name();
      $currentUserPassword = $currentUser->get_user_password();
      $currentUserEmail = $currentUser->get_user_email();
      $currentUserAddress = $currentUser->get_user_address();
      $currentUserPhoneNumber = $currentUser->get_user_phone();
      ?>

      <!DOCTYPE html>

      <html lang="en">

      <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>IEP Account Settings</title>
        <link rel="stylesheet" type="text/css" href="style.css">
      </head>

      <body>
        <div class="gridContainer">
          <header>
            <!-- Insert logo image here -->
            <h1>IEP Portal</h1>
            <div id="accountHolderInfo">
              <!-- Username, messages button, and account settings button here -->
            </div>
            <div id="horizontalNav">
              <a class="hNavButton" href="">
                <h3 class="button">Documents</h3>
              </a>
              <a class="hNavButton" href="">
                <h3>Goals</h3>
              </a>
              <a class="hNavButton" href="">
                <h3>Events</h3>
              </a>
              <a class="hNavButton" href="iepMessage.html">
                <h3>Messages</h3>
              </a>
              <a class="hNavButton" href="iepSettings.php">
                <h3>Information</h3>
              </a>
              <a class="hNavButton" href="iepSettings.php">
                <h3>Settings</h3>
              </a>
            </div>
          </header>

          <!-- Vertical navigation bar -->
          <div class="left" id="verticalNav">
            <h3>Navigation</h3>
          </div>

          <!-- Display User information -->
          <div>
            <?php
            echo $currentUserFullName . "<br><br>";
            echo "Account Type: " . $currentUserType . "<br><br>";
            echo "Username: " . $currentUsername . "<br><br>";
            echo "Current Password: " . $currentUserPassword . "<br><br>";
            echo "Current Email: " . $currentUserEmail . "<br>";
            ?>
            <input type="submit" class="submit" value="Change Email"> <br> <br>

            <?php
            echo "Current Address: " . $currentUserAddress . "<br>";
            ?>
            <input type="submit" class="submit" value="Change Address"> <br> <br>

            <?php
            echo "Current Phone Number: " . $currentUserPhoneNumber . "<br>";
            ?>
            <input type="submit" class="submit" value="Change Phone Number">
          </div>

          </form>
        </div>

      </body>

      </html>