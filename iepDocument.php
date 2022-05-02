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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    
  </head>

  <body>

    <?php
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
      $currentUserName = $currentUser->get_full_name();
      $unreadMessageCount = countUnreadMessages($conn, $currentUserId);

      try {
        $activeStudent = createStudent($activeStudentId, $conn);

      } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
      }

      // Can use $currentUser
      $currentUserName = $currentUser->get_full_name();

      // Can use $activeStudent
      $activeStudentName = $activeStudent->get_full_name();

      // Choose an action based on user form submission (add or remove document)
      if(isset($_POST["btnAdd"])) {
        addDocument($activeStudentId, $currentUserId);
      }
      if(isset($_POST["btnRemove"])) {
        deleteDocument( );
      }

      /** addDocument( ) - add document to database */
      function addDocument($studentId, $userId) {
        global $conn;

        $currentDate = date("Y-m-d");
        $currentTime = date("H:i:s");
        $currentDateTime = $currentDate . " " . $currentTime;


        //echo "current datetime is: " . $currentDateTime . "<br />";
        
        //$path = "http://localhost/capstoneCurrent/documents/";
        //$path = "http://localhost:8888/CSC-450-GROUP-4/documents/";

        // File file and file extension to be uploaded
        $filePath = "documents/";
        $fileName = $_FILES["addFile"]["name"];
        $fileToUpload = $filePath . basename($_FILES["addFile"]["name"]);
        $fileType = strtolower(pathinfo($fileToUpload,PATHINFO_EXTENSION));
        
        if ($fileType == "") {
          // Check if a file was selected to upload
          echo "<script>alert(\"No file selected. Please select a file to upload\");</script>";
        }
        else if (file_exists($fileToUpload)) {
          // Check if file of same name in database
          echo "<script>alert(\"Sorry, file already exists\");</script>";
        }
        else if($fileType != "pdf" && $fileType != "docx" && $fileType != "doc") {
          // Check for accepted document file formats (pdf, doc, docx)
          echo "<script>alert(\"Sorry, file type not compatible. Please upload a PDF, DOCX, or DOC file\");</script>";
        }
        else {
          // Attempt to move document to correct server folder
          // If successful, add document info to database
          if (move_uploaded_file($_FILES["addFile"]["tmp_name"], $fileToUpload)) {

            // Upload file to database

            $stmt = $conn->prepare("INSERT INTO document (student_id, user_id, document_date, document_name, document_path)
                                    VALUES (?, ?, ?, ?,?)");
            $stmt->bind_param("iisss", $studentId, $userId, $currentDateTime, $fileName, $path);

            try {
              $stmt->execute();

            } catch (Exception $e) {
              echo "Message: " . $e->getMessage();

            }
            echo "<script>alert(\"File has been uploaded\");</script>";
          }
          else {
            echo "<script>alert(\"An error has occurred uploading your file. Please try again\");</script>";
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

        // Verify that file was selected by user
        if ($deleteDocId[0] == "" || $deleteDocId[0] == "blank") {
          echo "<script>alert(\"No file selected for deletion.\");</script>";
          $deleteKey = 0;
        }
        else {
          // Prepared statement
          $stmt = $conn->prepare("SELECT document_name
                                  FROM document
                                  WHERE document_id=?");
          $stmt->bind_param("i", $deleteDocId[0]);

          try {
            $stmt->execute();

          } catch (Exception $e) {
            echo "Message: " . $e->getMessage();

          }
          $result = $stmt->get_result();
          $docInfo = $result->fetch_assoc( );

          // Check that document was found. If not, display error
          if ($result->num_rows > 0) {
            $docNameToDelete = $docInfo['document_name'];

            if ($deleteKey == 1) {

              // Delete document from server folder and remove from db
              if (unlink($deletePath . $docNameToDelete)) {

                // Prepared Statement
                $stmt = $conn->prepare("DELETE FROM document
                                        WHERE ?=document.document_id");
                $stmt->bind_param("i", $deleteDocId[0]);

                try {
                  $stmt->execute();
      
                } catch (Exception $e) {
                  echo "Message: " . $e->getMessage();
      
                }

                echo "<script>alert(\"File has been deleted\");</script>";
              }
            }
          }
          else {
            echo "<script>alert(\"An error occurred while uploading your document.\");</script>";
          }
        }
      } // end deleteDocument( )


      /* ********************************
       * displayDocumentList( ) - display list of all documents for specified student
       * $displayAsLink - display documents as either plain text or as links
       * TODO: modify to only display documents relevant to user
       * ******************************** */
      function displayDocumentList($studentId) {
        global $conn;

        // Prepared statement - locate documents in database
        $stmt = $conn->prepare("SELECT * 
                                FROM document
                                WHERE student_id=?
                                ORDER BY document_date DESC");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();

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
      function displayDocumentInput($studentId) {
        global $conn;

        $stmt = $conn->prepare("SELECT * 
                                FROM document
                                WHERE student_id=?
                                ORDER BY document_date DESC");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Display documents
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
        echo "<li class='docLink'><a href='" . $document['document_path'] . $document['document_name'] . "' target='_blank'>" . $document['document_name'] . "</a></li>";
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
        <!-- Left blank: no vertical nav needed on this page -->
      </div>

      <!-- Main content of page -->
      <div class="middle" id="mainContent">
        <div class="currentStudentName">
            <h3><?php echo $activeStudentName; ?></h3>
        </div>
        
        <div class="contentCard">
          <h3>Documents</h3>
          <?php 
            displayDocumentList($activeStudentId);
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
                    displayDocumentInput($activeStudentId);
                  ?>
                </select>
              <br /><br />

              <!-- Submit button -->
              <input type="submit" class="documentChange" name="btnRemove" id="btnDocumentRemove" value="Remove Document">

            </fieldset>
          </form>
        </div>
      
        

    </div>
    <?php include_once(realpath("footer.php")); ?>
  </body>
</html>