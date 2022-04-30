      <!-- iepSettings.php - IEP Settings
      Spring 100 CSC 450 Capstone, Group 4
      Author: Andy Yang
      Date Written: 03/2/2022
      Revised: 04/18/2022, Intergrated user interface and now displays user information
      Revised: 04/22/2022 Added selectedUserId and selectedUserInfo to access user data sent on Admin accountSelect change
    -->

      <?php

      include_once realpath("initialization.php");
      global $conn;

    // See if selectedUserId exists in POST
    try {
      if (array_key_exists("selectedUserId", $_GET)) {
        $selectedUserId = $_GET["selectedUserId"];
      } else {
        //$selectedUserId = "";
        echo "No user selected to edit <br />";
      }
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }
    //echo "Selected User Id: ";
    //echo $selectedUserId;
    //echo "<br />";

    /*
    $selectedUserInfo is an associative array containing keys: 
    userFullName, 
    userType, 
    userPassword, 
    userName, 
    userEmail, 
    userAddress, 
    userPhone

    Values correspond to the currently selected user in accountSelect in left nav bar section
    */
    if (isset($selectedUserId)) {
      $selectedUserInfo = getUserInfo($conn, $selectedUserId);
      print_r($selectedUserInfo);
      echo "<br />";
      // Use selectedUserInfo values Admin edits
      $currentUserFullName = $selectedUserInfo["userFullName"];
      $currentUserType = $selectedUserInfo["userType"];
      $currentUsername = $selectedUserInfo["userName"];
      $currentUserPassword = $selectedUserInfo["userPassword"];
      $currentUserEmail = $selectedUserInfo["userEmail"];
      $currentUserAddress = $selectedUserInfo["userAddress"];
      $currentUserPhoneNumber = $selectedUserInfo["userPhone"];

    } else {

          // See if currentUserId and type exist in Session
      try {
        $currentUserId = $_SESSION["currentUserId"];
      } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
      }

      try {
        $currentUserType = $_SESSION["currentUserType"];
      } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
      }


      // Initialize currentUser as new User of correct type
      // Pass $currentUserId, $currentUserType, $conn into createUser() function
      try {
        $currentUser = createUser($currentUserId, $currentUserType, $conn);

      } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
      }

        // Save user information to be displayed
        $currentUserFullName = $currentUser->get_full_name();
        $currentUserType = $currentUser->get_user_type();
        $currentUsername = $currentUser->get_user_name();
        $currentUserPassword = $currentUser->get_user_password();
        $currentUserEmail = $currentUser->get_user_email();
        $currentUserAddress = $currentUser->get_user_address();
        $currentUserPhoneNumber = $currentUser->get_user_phone();

    }


 /*     
    // See if currentUserId and type exist in Session
    try {
      $currentUserId = $_SESSION["currentUserId"];
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }

    try {
      $currentUserType = $_SESSION["currentUserType"];
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }


    // Initialize currentUser as new User of correct type
    // Pass $currentUserId, $currentUserType, $conn into createUser() function
    try {
      $currentUser = createUser($currentUserId, $currentUserType, $conn);

    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }

      // Save user information to be displayed
      $currentUserFullName = $currentUser->get_full_name();
      $currentUserType = $currentUser->get_user_type();
      $currentUsername = $currentUser->get_user_name();
      $currentUserPassword = $currentUser->get_user_password();
      $currentUserEmail = $currentUser->get_user_email();
      $currentUserAddress = $currentUser->get_user_address();
      $currentUserPhoneNumber = $currentUser->get_user_phone();

 */     
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