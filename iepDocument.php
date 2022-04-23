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
      
      // Close database 
      function close_db( ) {
        global $conn;
        $conn->close();
      }

      /* ********************************
       * displayDocumentList( ) - display list of all docments in db
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
          displayDocumentName($heading);
  
          // Display the rest of the dpcuments
          while($row = $result->fetch_assoc( )) {
              displayDocumentName($row); 
          } // end while( )
          // End list
          echo "</ul>";
        } // end if
      }

      /* ********************************
          * displayDocumentButton($heading) - display a document & its info 
          * $row - db row containing document info
          * called by displayDocuments( )
          * ******************************** */
          function displayDocumentName($heading) {
            global $conn; 
        
            // Display document info in list format
            echo "<li><a href='" . $heading['document_path'] . $heading['document_name'] . "' target='_blank'>" . $heading['document_name'] . "</a></li>";
        }

      /* ********************************
          * displayDocument($heading) - display a document & its info 
          * $row - db row containing document info
          * called by displayDocuments( )
          * ******************************** */
          function displayDocument($heading) {
            global $conn; 
        
            // Display document info in list format
            echo "<div class='contentCard'>";
            echo "<h4>" . $heading['document_name'] . "</h4>";
            echo "<embed src='" . $heading['document_path'] . $heading['document_name'] . "' width='100%' height='700' type='application/pdf'>";
            echo "</div>";
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
          <!-- Links are inactive: no further pages have been built -->
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
        <a class="vNavButton" href=""><h3>Child #2</h3></a>
        <a class="vNavButton" href=""><h3>Child #3</h3></a>
      </div>

      <!-- Main content of page -->
      <div class="middle" id="mainContent">
        <div class="currentStudentName">
            <h3>Student Name</h3>
        </div>   
        <div class="calendar contentCard">
            <h3>Calendar</h3>

        </div>
        <div class="schedule contentCard">
            <h3>Upcoming</h3>
        </div>
        
        <div class="contentCard">
          <h3>Documents</h3>
          <?php 
            displayDocumentList( );
            close_db( );
          ?>
        </div>
      
      <footer>
        <!-- Insert footer info here -->
      </footer>

    </div>
  </body>
</html>