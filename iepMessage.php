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


/*
      // Connection constants for use with AMPPS
      define("SERVER_NAME", "localhost");
      define("DBF_USER_NAME", "root"); 
      define("DBF_PASSWORD", "mysql");
      define("DATABASE_NAME", "iep_portal");

      // Create new connection object, then test connection
      $conn = new mysqli(SERVER_NAME, DBF_USER_NAME, DBF_PASSWORD);
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      
      // Select database
      $conn->select_db(DATABASE_NAME);
 */   
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

        // Hardcoded users for now
        $otherUser = 16;
        $thisUser = 13;

        // Locate other user name
        $sql = "SELECT user_name, user_id FROM user WHERE user_id='" . $otherUser . "'";
        $result = $conn->query($sql);
        $otherUserName = $result->fetch_assoc( );

        // Locate active user's user name
        $sql = "SELECT user_name, user_id FROM user WHERE user_id='" . $thisUser . "'";
        $result = $conn->query($sql);
        $thisUserName = $result->fetch_assoc( );

        // Locate messages sent and received between two users
        $sql = "SELECT message.user_id AS 'sender', " 
          . "message_recipient.user_id AS 'recipient', message.message_id, message_recipient.message_id, "
          . "message.message_text, message.message_date, message_recipient.message_read " 
          . "FROM message INNER JOIN message_recipient ON message.message_id = message_recipient.message_id "
          . "WHERE (message.user_id='" . $thisUser . "' OR message.user_id='" . $otherUser . "') " 
          . " AND (message_recipient.user_id='" . $thisUser . "' OR message_recipient.user_id='" . $otherUser . "')"
          . " ORDER BY message.message_date ASC";
        $result = $conn->query($sql);

        // Display messages as divs
        if ($result->num_rows > 0) {

          while($oneMessage = $result->fetch_assoc( )) {
            $messageClass = "otherMessageCard";
            $sentBy = $otherUserName['user_name'];

            if ($oneMessage['sender'] == $thisUser) {
              $messageClass = "userMessageCard";
              $sentBy = $thisUserName['user_name'];
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

        // Hardcoded users for now
        $sendToUser = 16;
        $currentUserId = 13;
        $message = array($_POST['txtMessage']);

        // Insert message into SENT message table
        $sql = "INSERT INTO message (message.user_id, message_text) "
          . "VALUES ('" . $currentUserId . "', '"
          . $message[0] . "')";
        runQuery($sql, "Message sent", true);

        echo "message is: " . $message[0] . "<br />"; ////////////////
        echo "id is: " . $currentUserId . "<br />"; ////////////////

        // Find message id of message just sent
        $sql = "SELECT message_id, message.user_id, message_text, message_date FROM message WHERE message.user_id='"
          . $currentUserId . "' AND message_text='" . $message[0] . "' ORDER BY message_date DESC";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {

          // Pull message id
          $firstRow = $result->fetch_assoc( );
          $sentMessage = $firstRow['message_id'];
  
          // Insert message into message_recipient table
          $sql = "INSERT INTO message_recipient (message_id, message_recipient.user_id) "
            . "VALUES ('" . $sentMessage . "', '"
            . $sendToUser . "')";
          runQuery($sql, "Message received by other user ", true);
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
              <!-- Selection List for message recipients -->
              <?php
                userSelectionList($conn);
              ?>
              <br /><br />

              <!-- Text field to type message -->
              <label for="txtMessage">Type your message:</label>
              <input type="text" id="txtMessage" name="txtMessage">
              
              <!-- Submit button: send message -->
              <input type="submit" name="btnSend" value="Send">

            </fieldset>
        </div>
    </div>
  </body>
</html>