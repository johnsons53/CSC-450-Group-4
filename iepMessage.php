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
      Date Revised: 04/30/2022 - 
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

      
      // If the 'send' button was activated, send the message
      if(isset($_POST["btnSend"])) {
        sendMessage( );
      }


      /** runQuery($sql, $msg, $success) - execute sql query, display message on failure/success
       * $sql - string to execute
       * $msg - text to display in success/failure message
       * $success - if true, display message, else do not display message */
      function runQuery($sql, $msg, $success) {
        global $conn;
         
        // run the query
        if ($conn->query($sql) === TRUE) {
           if($success) {
              echo "<h4>" . $msg . " successful.</h4>";
           }
        } else {
           echo "<h4>Error when: " . $msg . " using SQL: " . $sql . " " . $conn->error . "</h4>";
        }   
      } // end of runQuery( )

      
      /* **  Close database ** */
      function close_db( ) {
        global $conn;
        $conn->close();
      }


      /* displayMessages( ) - display messages to/from users */
      function displayMessages( ) {
        global $conn;
        global $currentUserId;
        global $currentUserName;
        
        $otherUser = array($_POST['userSelect']);
        $otherUserId = $otherUser[0];
        echo "current user id: " . $currentUserId . "<br />";
        echo "current user name: " . $currentUserName . "<br />";
        echo "other user id: " . $otherUserId . "<br />";

        // Locate other user name
        $sql = "SELECT user_name, user_id FROM user WHERE user_id='" . $otherUserId . "'";
        $result = $conn->query($sql);
        $otherUser = $result->fetch_assoc( );
        $otherUserName = $otherUser['user_name'];

        echo "other username is: " . $otherUserName . "<br />"; //////////////

        /* Locate active user's user name
        $sql = "SELECT user_name, user_id FROM user WHERE user_id='" . $currentUserId . "'";
        $result = $conn->query($sql);
        $currentUserName = $result->fetch_assoc( ); */

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
          echo "results found<br />";

          while($oneMessage = $result->fetch_assoc( )) {
            $messageClass = "otherMessageCard";
            $sentBy = $otherUserName;

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

        // If message is blank, do not send
        if ($message[0] == "") {
          echo "message is blank<br />"; /////////////////////////////
        }
        else {
          // Insert message into SENT message table
          $sql = "INSERT INTO message (message.user_id, message_text) "
          . "VALUES ('" . $currentUserId . "', '"
          . $message[0] . "')";
          runQuery($sql, "Message sent", true);

          echo "message is: " . $message[0] . "<br />"; ////////////////
          echo "current user id is: " . $currentUserId . "<br />"; ////////////////

          // Find message id of message just sent
          $sql = "SELECT message_id, message.user_id, message_text, message_date FROM message WHERE message.user_id='"
          . $currentUserId . "' AND message_text='" . $message[0] . "' ORDER BY message_date DESC";
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
            runQuery($sql, "Message received by other user ", true);
          }
        }
      } // end sendMessage( )


      /* selectMessageRecipient( ) - display list of all users associated with current user
       * user can select one to open the message history and send a message *
      function selectMessageRecipient( ) {
        global $conn;
        global $currentUserId;

        // HARDCODED USER ID to test admin display
        $currentUserId = 11;

        // Determine type of user
        $sql = "SELECT user_id, user_type FROM user WHERE user_id='" . $currentUserId . "'";
        $result = $conn->query($sql);
        $currentUserType = $result->fetch_assoc( );

        // echo "<option value='null'>current user type is: " . $currentUserType['user_type'] . "</option>"; //////////////

        if ($currentUserType['user_type'] != "admin") {
          
          //echo "<option value='null2'>User is not admin</option>"; ///////////////////

          // Locate all students that user can message
          $sql = "SELECT user.user_id AS 'id', user.user_name, student.user_id AS 'studentId', student.student_id "
            . "student_parent.student_id, student_parent.user_id, "
            . "FROM user "
            . "INNER JOIN student_parent ON student_parent.user_id = user.id "
            . "INNER JOIN student ON student.student_id = student_parent.student_id";

          

          /* If user not admin, find associated users to message
          $sql = "SELECT user.user_id AS 'id', user_name, student.user_id, student_parent.user_id, "
          . "provider.user_id, student_parent.student_id, student.student_id "
          . "FROM user "
          . "INNER JOIN student_parent ON student_parent.user_id = user.user_id "
          . "INNER JOIN student ON student.student_id = student_parent.student_id "
          . "INNER JOIN provider ON provider.provider_id = student.provider_id "
          . "ORDER BY user.user_id ASC"; *
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            echo "<option value='null'>more than 1</option>";

            // Cycle through list of all users, display as selection
            while($users = $result->fetch_assoc( )) {
              echo "<option value='" . $users['studentId'] . "'>" . $users['user_name'] . "</option>";
            }
          }
        }
        else {
          // If user is admin, can message all users
          $sql = "SELECT user_id AS 'id', user_name FROM user ORDER BY user_id ASC";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            // Cycle through list of all users, display as selection
            $counter = 0;
            while($users = $result->fetch_assoc( )) {
              if ($counter == 0) {
                echo "<option value='" . $users['id'] . "'>" . $users['user_name'] . "</option>";
              }
              else {
                echo "<option value='" . $users['id'] . "'>" . $users['user_name'] . "</option>";
              }
              $counter += 1;
            }
          }
        }
      } // end selectUserRecipient( ) */
      
    ?>


    <!-- Page is encompassed in grid -->
    <div class="gridContainer">
      <header>
        <!-- Insert logo image here -->
        <h1>IEP Portal</h1>
        <div id="accountHolderInfo">
          <!-- Username, messages button, and account settings button here -->
        </div>
        <div id="horizontalNav">
          <!-- Links are inactive -->
          <a class="hNavButton" href=""><h3 class="button">Documents</h3></a>
          <a class="hNavButton" href=""><h3>Goals</h3></a>
          <a class="hNavButton" href=""><h3>Events</h3></a>
          <a class="hNavButton" href=""><h3>Messages</h3></a>
          <a class="hNavButton" href=""><h3>Information</h3></a>
          <a class="hNavButton" href=""><h3>Settings</h3></a>
        </div>
      </header>

      <!-- Vertical navigation bar -->
      <div class="left" id="verticalNav">
        <!-- Blank: maintains page structure -->
      </div>

      <!-- Main content of page -->
      <div class="mainContent">
        
        <h3>Messages</h3>

        <div class="contentCard" id="messageContent">
          <?php 
            // If the 'send' button was activated, display messages relevant to user
            if(isset($_POST["btnSend"])) {
              displayMessages( );
            }
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
                  userSelectionList($conn, $currentUserId);
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