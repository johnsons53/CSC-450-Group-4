<?php 
  include_once realpath("initialization.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- iepMessage.php - IEP messaging page
      Spring 100 CSC 450 Capstone, Group 4
      Author: Sienna-Rae Johnson
      Date Written: 04/29/2022
      Date Revised: 
        04/30/2022: send/receive messages functionality fixes
        05/01/2022: formatting changes, bug fixes
    -->
    <title>IEP Portal: Messages</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="iepDetailView.js"></script>
    
  </head>

  <body>
    <?php

      /** Find current user */
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

      $currentUserName = $currentUser->get_full_name();
      $unreadMessageCount = countUnreadMessages($conn, $currentUserId);
      
      // If the 'send' button was activated, send the message
      if(isset($_POST["btnSend"])) {
        sendMessage( );
      }


      /* getOtherUserId( ) - returns selected user id, if selected */
      function getOtherUser( ) {
        global $conn;
        global $currentUserId;

        if (isset($_POST['btnSend'])) {
          // Get other user info from form input
          $otherUser = array($_POST['userSelect']);
          $otherUserId = $otherUser[0];
          /* echo "current user id: " . $currentUserId . "<br />";
          echo "current user name: " . $currentUserName . "<br />";
          echo "other user id: " . $otherUserId . "<br />"; */

          // Locate other user name
          $sql = "SELECT user_name, user_id FROM user WHERE user_id='" . $otherUserId . "'";
          $result = $conn->query($sql);
          $otherUser = $result->fetch_assoc( );
          $otherUserName = $otherUser['user_name'];
        }
        else {
          // Get other user info from a default option
          $sql = "SELECT user_last_name, user_first_name, user_id "
            . " FROM user WHERE user_id <> " . $currentUserId 
            . " ORDER By user_last_name";
          $result = $conn->query($sql);
          $otherUser = $result->fetch_assoc( );
          $otherUserId = $otherUser['user_id'];
          $otherUserName = $otherUser['user_first_name'] . " " . $otherUser['user_last_name'];
        }

        // Return user id and name as array
        $otherUser = array($otherUserId, $otherUserName);
        return $otherUser;
      }


      /* displayMessages( ) - display messages to/from users */
      function displayMessages( ) {
        global $conn;
        global $currentUserId;
        global $currentUserName;

        // Determine if user has set message recipient
        // If not, use a default user.
        $otherUser = getOtherUser( );
        $otherUserId = $otherUser[0];
        $otherUserName = $otherUser[1];

        // echo "other username is: " . $otherUserName . "<br />";

        // Mark all incoming messages from other user as read
        $sql = "UPDATE message_recipient
                INNER JOIN message ON message.message_id = message_recipient.message_id  
                set message_read = '1' 
                WHERE message.user_id = '" . $otherUserId . "' AND message_recipient.user_id = '"
                . $currentUserId . "'";
        $result = $conn->query($sql);

        // Locate messages sent and received between two users
        $sql = "SELECT message.user_id AS 'sender', " 
          . "message_recipient.user_id AS 'recipient', message.message_id, message_recipient.message_id, "
          . "message.message_text, message.message_date, message_recipient.message_read " 
          . "FROM message INNER JOIN message_recipient ON message.message_id = message_recipient.message_id "
          . "WHERE (message.user_id='" . $currentUserId . "' OR message.user_id='" . $otherUserId . "') " 
          . " AND (message_recipient.user_id='" . $currentUserId . "' OR message_recipient.user_id='" . $otherUserId . "')"
          . " ORDER BY message.message_date ASC";
        $result = $conn->query($sql);

        // Display messages as divs
        if ($result->num_rows > 0) {

          // Cycle through all messages and display
          while($oneMessage = $result->fetch_assoc( )) {

            // By default, assume the current message is an incoming message
            $messageClass = "otherMessageCard";
            $sentBy = $otherUserName;

            // Check if the message was sent by the user
            if ($oneMessage['sender'] == $currentUserId) {
              $messageClass = "userMessageCard";
              $sentBy = $currentUserName;
            }

            // Start displaying message
            echo "<div class='" . $messageClass . " messageCard'>";
            echo "<h4 class='msgUserName'>" . $sentBy . "</h4>";
            echo "<h4 class='msgDate'>" . $oneMessage['message_date'] . "</h4>";
            echo "<p class='message'>" . $oneMessage['message_text'] . "</p>";
            echo "</div>";
          } // end while
        } // end if
      } // end displayMessages( )


      /* sendMessage( ) - upload a message to the database */
      function sendMessage( ) {
        global $conn;
        global $currentUserId;

        // Find other user id
        $otherUser = array($_POST['userSelect']);
        $otherUserId = $otherUser[0];
        
        $message = array($_POST['txtMessage']);
        $message = test_input($message[0]);

        // If message is blank, do not send
        // Pressing send button also updates the message display
        if ($message == "") {
          // echo "message is blank<br />";
        }
        else {
          // Insert message into SENT message table
          $sql = "INSERT INTO message (message.user_id, message_text) "
          . "VALUES ('" . $currentUserId . "', '"
          . $message . "')";
          $conn->query($sql);

          /* 
          echo "message is: " . $message[0] . "<br />";
          echo "current user id is: " . $currentUserId . "<br />";
          */

          // Find message id of message just sent
          $sql = "SELECT message_id, message.user_id, message_text, message_date FROM message WHERE message.user_id='"
          . $currentUserId . "' AND message_text='" . $message . "' ORDER BY message_date DESC";
          $result = $conn->query($sql);

          if($result->num_rows > 0) {
            echo "result found flag 33<br />";

            // Pull message id
            $firstRow = $result->fetch_assoc( );
            $sentMessage = $firstRow['message_id'];

            // Insert message into message_recipient table
            $sql = "INSERT INTO message_recipient (message_id, message_recipient.user_id) "
              . "VALUES ('" . $sentMessage . "', '"
              . $otherUserId . "')";
            $conn->query($sql);
          }
        }
      } // end sendMessage( )
      
    ?>


    <!-- Page is encompassed in grid -->
    <div class="gridContainer">
    <header>
      <!-- Insert logo image here -->
      <h1>IEP Portal</h1>
      <div id="accountHolderInfo">
        <!-- Username, messages button, and account settings button here -->

        <h2><i class="fa fa-user"></i> <?php echo $currentUserName; ?></h2>
      </div>
      <div id="horizontalNav">
        <a class="hNavButton active" id="userHomeLink" href="iepDashboard.php">
          <h3><i class="fa fa-fw fa-home"></i> Home</h3>
        </a>
        <a class="hNavButton" id="userMessagesLink" href="iepMessage.php">
          <h3><i class="fa fa-fw fa-envelope"></i> Messages<span class="badge"><?php echo $unreadMessageCount;?></span></h3>
        </a>
        <a class="hNavButton" id="userSettingsLink" href="userSettings.php">
          <h3><i class="fa fa-gear"></i> Settings</h3>
        </a>
        <a class="hNavButton" id="userLogout" href="iepUserLogout.php">
          <h3><i class="fa fa-sign-out"></i> Logout</h3>
        </a>


      </div>

    </header>

      <!-- Vertical navigation bar -->
      <div class="left" id="verticalNav">
        <!-- Blank: maintains page structure -->
      </div>

      <!-- Main content of page -->
      <div class="mainContent">
        
        <h3>Messages:
          <?php 
            // Display other user name at top of page
            $otherUser = getOtherUser( );
            echo $otherUser[1];
          ?>
        </h3>

        <div class="contentCard" id="messageContent">
          <?php 
            displayMessages( );
          ?>
        </div>

        <!-- Messaging interface: select user and send message -->
        <div class="messageForm">

          <!-- Send message form -->
          <form name="frmSendMessage"
            action="<?PHP echo htmlentities($_SERVER['PHP_SELF']); ?>"
            method="POST">
            <fieldset name="sendMessage">
              <legend>Send a message</legend>
              <label for="userSelect">Select recipient:</label>
              <select name="userSelect" class="userSelect" id="userSelect" size='5' multiple>
                <!-- Selection List for message recipients, excludes current user -->
                <?php
                  global $currentUserId;

                  // Retrieve selected user from prior page load, if applicable
                  $otherUser = getOtherUser( );

                  userSelectionList($conn, $currentUserId, $otherUser[0]);
                ?>
              </select>
              <br /><br />

              <!-- Text field to type message -->
              <label for="txtMessage">Type your message:</label>
              <input type="text" id="txtMessage" name="txtMessage">
              
              <!-- Submit button: send message -->
              <input type="submit" name="btnSend" value="Send">

            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>