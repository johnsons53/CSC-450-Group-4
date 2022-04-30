<?php 
  include_once realpath("initialization.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- iepDocument.php - IEP document view page
      Spring 100 CSC 450 Capstone, Group 4
      Author: Sienna-Rae Johnson
      Date Written: 04/19/2022
      Date Revised: 4/28/2022 - add/remove document functionality added
      Date Revised: 4/29/2022 - display documents relevant to active user & selected student only
    -->
    <title>IEP Portal: Documents</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="iepDetailView.js"></script>
    
  </head>

  <body>

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
 
 
// Confirmed $activeStudentId value available via $_SESSION 
try {
  $activeStudentId = $_SESSION["activeStudentId"];
} catch (Exception $e) {
  echo "Message: " . $e->getMessage();

} 
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

try {
  $activeStudent = createStudent($activeStudentId, $conn);

} catch (Exception $e) {
  echo "Message: " . $e->getMessage();
}

// Can use $currentUser
$currentUserName = $currentUser->get_full_name();

// Can use $activeStudent
$activeStudentName = $activeStudent->get_full_name();
  

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

        $currentDateTime = date("Y-m-d\nH:i:s");
        echo "current datetime is: " . $currentDateTime . "<br />";
        
        // TODO: update file path with path for server
        $path = "http://localhost/capstoneCurrent/documents/";

        // File file and file extension to be uploaded
        $filePath = "documents/";
        $fileName = $_FILES["addFile"]["name"];
        $fileToUpload = $filePath . basename($_FILES["addFile"]["name"]);
        $fileType = strtolower(pathinfo($fileToUpload,PATHINFO_EXTENSION));
        
        if ($fileType == "") {
          // Check if a file was selected to upload
          echo "Error: no file selected. Please select a file to upload<br />";
        }
        else if (file_exists($fileToUpload)) {
          // Check if file of same name in database
          echo "Sorry, file already exists<br />";
        }
        else if($fileType != "pdf" && $fileType != "docx" && $fileType != "doc") {
          // Check for accepted document file formats (pdf, doc, docx)
          echo "Error: file type " . $fileType . " not compatible. Please upload a PDF, DOCX, or DOC file.<br />";
        }
        else {
          // Attempt to move document to correct server folder
          // If successful, add document info to database
          if (move_uploaded_file($_FILES["addFile"]["tmp_name"], $fileToUpload)) {

            // Upload file to database
            $newDocument = array($addStudentID, $addUserId, $currentDateTime, $fileName, $path);
            $sql = "INSERT INTO document (student_id, user_id, document_date, document_name, document_path) "
              . "VALUES ('" . $newDocument[0] . "', '"
              . $newDocument[1] . "', '"
              . $newDocument[2] . "', '"
              . $newDocument[3] . "', '"
              . $newDocument[4] . "')";
            runQuery($sql, "New document insert: $fileName", true); 

            echo "The file " . $fileName . " has been uploaded.<br />";
          }
          else {
            echo "An error has occurred uploading your file. Please try again.<br />";
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

          // Check that document was found. If not, display error
          if ($result->num_rows > 0) {
            $docNameToDelete = $docInfo['document_name'];

            echo "Doc ID: " . $deleteDocId[0] . " doc name: " . $docNameToDelete . "<br />"; // DEBUGGING FLAG

            if ($deleteKey == 1) {

              // Delete document from server folder and remove from db
              if (unlink($deletePath . $docNameToDelete)) {
                $sql = "DELETE FROM document WHERE " . $deleteDocId[0] . "=document.document_id";
                          
                // TODO: change true to false below (don't show debugging)
                runQuery($sql, "Delete document: " . $deleteDocId[0], true);
              }
            }
          }
          else {
            echo "An error occurred while uploading your document.<br />";
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

        $sql = "SELECT * FROM document ORDER BY document_date DESC";
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
        <h2><i class="fa fa-user"></i> <?php echo $currentUserName; ?></h2>
      </div>
      <div id="horizontalNav">
        <a class="hNavButton active" id="userHomeLink" href="iepDashboard.php"><h3><i class="fa fa-fw fa-home"></i> Home</h3></a>
        <a class="hNavButton" id="userMessagesLink" href="iepMessage.html"><h3><i class="fa fa-fw fa-envelope"></i> Messages</h3></a>
        <a class="hNavButton" id="userSettingsLink" href="iepSettings.php"><h3><i class="fa fa-gear"></i> Settings</h3></a>
        <a class="hNavButton" id="userLogout" href="#"><h3><i class="fa fa-sign-out"></i> Logout</h3></a>
      </div>
    </header>

      <!-- Vertical navigation bar -->
      <div class="left" id="verticalNav">
<!--         <h3>Navigation</h3>
        <a class="vNavButton" href=""><h3>Child #1</h3></a> -->
      </div>

      <!-- Main content of page -->
      <div class="middle" id="mainContent">
        <div class="currentStudentName">
            <h3><?php echo $activeStudentName; ?></h3>
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
            action=""
            method="POST"
            enctype="multipart/form-data" >
            <fieldset name="addDocument">
              <legend>Add a Document</legend>

              <!-- Document name is saved and document is uploaded to server file -->
              <label for="addFile">Select a file:</label>
              <input type="file" id="addFile" name="addFile" />
              <br /><br />

              <!-- Submit button -->
              <input type="submit" class="documentChange" name="btnAdd" id="btnDocumentAdd" value="Add Document">

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
              <input type="submit" class="documentChange" name="btnRemove" id="btnDocumentRemove" value="Remove Document">

            </fieldset>
          </form>
        </div>
      
      <footer>
        <!-- Insert footer info here -->
      </footer>

    </div>
  </body>
</html>