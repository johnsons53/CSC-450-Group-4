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
      Date Written: 04/30/2022
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
    
      // Choose an action based on user form submission (add or remove document)
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

      /* displayMessages( ) - display messages to/from users 
      function displayMessages( ) {
        global $conn;

        // Locate messages sent and received between two users
        $sql = "SELECT * FROM message INNER JOIN message_recipient ON message.message_id = message_recipient.message_id "
          . "WHERE user_id = '" . $currentUserId "'";
        $resultSent = $conn->query($sql);

        // Locate messages received by the user
        $sql = "SELECT * FROM message_recipient WHERE user_id = '" . $currentUserId "'";
        $resultReceived = $conn->query($sql);
      } */

      /* sendMessage( ) - upload a message to the database */
      function sendMessage( ) {
        global $conn;

        // Hardcoded users for now
        $sendToUser = 16;
        $currentUserId = 12;
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
  
          echo "sent message id is: " . $sentMessage . "<br />"; ///////////////
  
          // Insert message into message_recipient table
          $sql = "INSERT INTO message_recipient (message_id, message_recipient.user_id) "
            . "VALUES ('" . $sentMessage . "', '"
            . $sendToUser . "')";
          runQuery($sql, "Message sent ", true);
        }
      } // end sendMessage( )


      /* ********************************
       * displayDocumentList( ) - display list of all docments in db
       * $displayAsLink - display documents as either plain text or as links
       * TODO: modify to only display documents relevant to user
       * ******************************** */
      function displayDocumentList( ) {
        global $conn;

        $sql = "SELECT * FROM document ORDER BY document_date DESC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          
          echo "<ul>";
          // Display first row document information
          $heading = $result->fetch_assoc( );
          displayDocumentLink($heading);
  
          // Display the rest of the documents
          while($row = $result->fetch_assoc( )) {
            displayDocumentLink($row);
          } // end while( )
          // End list
          echo "</ul>";
        } // end if
      }

      /* ********************************
        * displayDocumentLink($document) - display a document & its info as a link to the document
        * ******************************** */
      function displayDocumentLink($document) {
        global $conn; 
        echo "<li><a href='" . $document['document_path'] . $document['document_name'] . "' target='_blank'>" . $document['document_name'] . "</a></li>";
      }

      /* ********************************
        * displayDocumentOption($document) - display a document & its info as an input list option
        * ******************************** */
      function displayDocumentOption($document) {
        global $conn; 
        echo "<option value='" . $document['document_id'] . "'>" . $document['document_name'] . "</option>";
      }

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
        <h3>Navigation</h3>
      </div>

      <!-- Main content of page -->
      <div class="middle" id="mainContent">
        <div class="currentStudentName">
            <h3>Student Name</h3>
        </div>
        
        <div class="contentCard">
          <h3>Messages</h3>
          <?php 
            
          ?>
        </div>

        <!-- Messaging display & interface -->
        <div class="messages">
          <!-- List user messages -->

          <form name="frmSendMessage"
            action="<?PHP echo htmlentities($_SERVER['PHP_SELF']); ?>"
            method="POST">
            <fieldset name="sendMessage">
              <legend>Send a message</legend>

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