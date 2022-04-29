<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- iepDocument.php - IEP document view page
      Spring 100 CSC 450 Capstone, Group 4
      Author: Sienna-Rae Johnson
      Date Written: 04/19/2022
    -->
    <title>IEP Portal: Documents</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="iepDetailView.js"></script>
    
  </head>

  <body>
    <!-- TODO Add PHP here to manage variables and queries to database -->
    <?php 
    // Variables needed: array of student_id values associated with the parent user
    // array of student names, pulled from user table and combined into one string for display
    // selected student to control which student's records are displayed
    // array of selected student values from student table
    // array for selected student's current goals
    // array for selected student's current objectives
    // report data for creating graph
    //  

    //include_once realpath("initialization.php");

/*
// Confirmed $activeStudentId and $activeStudentName values sent via $_POST
      try {
        echo $_POST["activeStudentId"];
        echo "<br />";
        echo $_POST["activeStudentName"];
        echo "<br />";
      } catch (Exception $e) {
        echo "Message: " . $e->getMessage();

      }

*/ 
/* 
// Confirmed $activeStudentId value available via $_SESSION 
try {
  echo $_SESSION["activeStudentId"];
  echo "<br />";
} catch (Exception $e) {
  echo "Message: " . $e->getMessage();

} 
*/  

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
      if(isset($_POST["btnAdd"])) {
        addDocument( );
      }
      if(isset($_POST["btnRemove"])) {
        deleteDocument( );
      }

      /** addDocument( ) - add document to database */
      function addDocument( ) {
        global $conn;

        // TODO: update student and user id to pull from page info
        $addStudentID = 0;
        $addUserId = 0;
        //$docName = $_POST['addFile'];
        // TODO: update file path with path for server
        $path = "http://localhost/capstoneCurrent/documents/";

        /* File object and file extension to be uploaded
        $fileToUpload = $path . basename($_FILES["addFile"]["name"]);
        $fileType = strtolower(pathinfo($fileToUpload,PATHINFO_EXTENSION)); */

        
        echo "<h4>" . $docName . "</h4>"; ///////////////////////////// DEBUGGING FLAG


        // File object and file extension to be uploaded
        $target_directory = "documents/";
        $fileToUpload = $target_directory . basename($_FILES["addFile"]["name"]);
        $fileType = strtolower(pathinfo($fileToUpload,PATHINFO_EXTENSION));

        echo "<h4>" . $fileType . "</h4>"; ///////////////////////////////

        // Check for only document file formats
        $uploadKey = 1;
        /*if($fileType != "pdf" && $fileType != "docx" && $fileType != "doc") {
          echo "Error: file type not compatible. Please upload a PDF, DOCX, or DOC file.";
          $uploadKey = 0;
        }*/

        /* Check filesize
        if ($_FILES["addFile"]["size"] > 3000000) {
          echo "Error: file is too large to upload";
          $uploadKey = 0;
        }*/

        if($uploadKey == 0) {
          echo "File not uploaded.";
        }
        else {
          if (move_uploaded_file($_FILES["addFile"]["tmp_name"], $fileToUpload)) {

            // Upload file to database
            // TODO: get CURRENT time and input into database
            $newDocument = array($addStudentID, $addUserId, $_FILES["addFile"]["name"], $path);
            $sql = "INSERT INTO document (student_id, user_id, document_date, document_name, document_path) "
              . "VALUES ('" . $newDocument[0] . "', '"
              . $newDocument[1] . "', '"
              . "1000-01-01 00:00:00" . "', '"
              . $newDocument[2] . "', '"
              . $newDocument[3] . "')";
            runQuery($sql, "New document insert: $docName", true); 

            echo "The file " . htmlspecialchars(basename($_FILES["addFile"]["name"])) . " has been uploaded.";
          }
          else {
            echo "An error has occurred uploading your file. Please try again.";
          }
        }
      } // end addDocument( )


      /** deleteDocument( ) - delete document from database and server */
      function deleteDocument( ) {
        global $conn;
        
        // Save form input (document id) as array
        $deleteDocId = array($_POST['removeFile']);
        $deletePath = "documents/";
        $deleteKey = 1;

        if ($deleteDocId[0] == "" || $deleteDocId[0] == "blank") {
          echo "No file selected for deletion.";
          $deleteKey = 0;
        }
        else {
          // Locate document name and extension
          $sql = "SELECT document_name FROM document WHERE document_id=" . $deleteDocId[0];
          $result = $conn->query($sql);
          $docInfo = $result->fetch_assoc( );
          $docNameToDelete = $docInfo['document_name'];

          echo "Doc ID: " . $deleteDocId[0] . " doc name: " . $docNameToDelete . "<br />";



          if ($deleteKey == 1) {
            // TODO: delete file from server as well as database
            if (unlink($deletePath . $docNameToDelete)) {
              // Delete delete document row from database
              $sql = "DELETE FROM document WHERE " . $deleteDocId[0] . "=document.document_id";
                        
              // TODO: change true to false below (don't show debugging)
              runQuery($sql, "Delete document: " . $deleteDocId[0], true);
            }
          }
        }
        
      } // end deleteDocument( )


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


      /* ********************************
       * displayDocumentList( ) - display list of all docments in db
       * $displayAsLink - display documents as either plain text or as links
       * TODO: modify to only display documents relevant to user
       * ******************************** */
      function displayDocumentList( ) {
        global $conn;

        $sql = "SELECT * FROM document";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          // Start list
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
       * displayDocumentInput( ) - display all documents as input options
       * TODO: modify to only display documents relevant to user
       * ******************************** */
      function displayDocumentInput( ) {
        global $conn;

        $sql = "SELECT * FROM document";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          
          // Display first row document information
          $heading = $result->fetch_assoc( );
          displayDocumentOption($heading);
  
          // Display the rest of the documents
          while($row = $result->fetch_assoc( )) {
            displayDocumentOption($row);
          } // end while( )
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
        <a class="vNavButton" href=""><h3>Child #1</h3></a>
      </div>

      <!-- Main content of page -->
      <div class="middle" id="mainContent">
        <div class="currentStudentName">
            <h3>Student Name</h3>
        </div>
        
        <div class="contentCard">
          <h3>Documents</h3>
          <?php 
            displayDocumentList( );
          ?>
        </div>


        <!-- Add (upload) a document -->
        <div class="formAdd">
          <form name="frmAddDocument"
            action="<?PHP echo htmlentities($_SERVER['PHP_SELF']); ?>"
            method="POST"
            enctype="multipart/form-data" >
            <fieldset name="addDocument">
              <legend>Add a Document</legend>

              <!-- Document name is saved and document is uploaded to server file -->
              <label for="addFile">Select a file:</label>
              <input type="file" id="addFile" name="addFile" />
              <br /><br />

              <!-- Submit button -->
              <input type="submit" name="btnAdd" value="Add Document">

            </fieldset>
          </form>
        </div>


        <!-- Remove (delete) a document -->
        <div class="formRemove">
          <form name="frmRemoveDocument"
            action="<?PHP echo htmlentities($_SERVER['PHP_SELF']); ?>"
            method="POST" >
            <fieldset name="removeDocument">
              <legend>Remove a Document</legend>

              <!-- Auto-populate list of documents -->
              <label for="removeFile">Select a file:</label>
                <select name="removeFile">
                  <option value="blank"></option>
                  <?php 
                    displayDocumentInput( );
                  ?>
                </select>
              <br /><br />

              <!-- Submit button -->
              <input type="submit" name="btnRemove" value="Remove Document">

            </fieldset>
          </form>
        </div>
      
      <footer>
        <!-- Insert footer info here -->
      </footer>

    </div>
  </body>
</html>