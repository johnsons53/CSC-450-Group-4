<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- iepParentHome.php - IEP Parent Dashboard
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell, Sienna-Rae Johnson
      Date Written: 02/26/2022
      Revised: 02/28/2022 Added containers for expanded view. Began setting up PHP sections.
    -->
    <title>IEP Portal: Parent Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="iepDetailView.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script> document.getElementById("defaultOpen").click(); </script> -->


    
  </head>

  <body>
    <!-- TODO Add PHP here to manage variables and queries to database -->
    <?php 
    // display errors for testing
    ini_set('display_errors', 1);
    error_reporting(E_ALL|E_STRICT);

    // connect to other files
    $filepath = realpath('login.php');
    $config = require($filepath);

    require_once realpath('User.php');
    require_once realpath('Admin.php');
    require_once realpath('Document.php');
    require_once realpath('Goal.php');
    require_once realpath('Guardian.php');
    require_once realpath('Provider.php');
    require_once realpath('Report.php');
    require_once realpath('Objective.php');
    require_once realpath('Student.php');

    
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

    $currentUser;
    // Select a guardian from the database for demonstration purposes
/*     $sql = "SELECT * 
            FROM user
            WHERE user_id='13'";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // show the data in each row
        while ($row = $result->fetch_assoc()) {
            // new Guardian object from row data
            $guardian = new Guardian($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                $row['user_city'], $row['user_district'], $row['user_type']);
            // add current user to $_SESSION array
            $currentUser = $guardian;
            $_SESSION['currentUser'] = serialize($guardian);
            
            echo $guardian->get_full_name() . " created as GUARDIAN <br />";
            echo "User for this SESSION: " . $_SESSION['currentUser']->get_full_name() . " <br />";

        } 
    } else {
        echo "0 results <br />";
    }
    $students = $_SESSION['currentUser']->get_guardian_students(); */

    // Select a Provider 
    $sql = "SELECT * 
    FROM user
    INNER JOIN provider USING (user_id)
    WHERE user_id='15'";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
    // show the data in each row
    while ($row = $result->fetch_assoc()) {
        // new Guardian object from row data
        $provider = new Provider($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
            $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
            $row['user_city'], $row['user_district'], $row['user_type'],
            $row['provider_id'], $row['provider_title']);
        // add current user to $_SESSION array
        $_SESSION['currentUser'] = serialize($provider);
        $currentUser = $provider;
        
        echo $provider->get_full_name() . " created as PROVIDER  LINE 144<br />";
        //echo "User for this SESSION: " . $_SESSION['currentUser']->get_full_name() . " <br />";

    } 
    } else {
    echo "0 results <br />";
    }
    if(array_key_exists('currentUser', $_SESSION)) {
      echo "SESSION contains currentUser value";
    }

    // Save array of students
    $students = $currentUser->get_provider_students();

    // Set default current student to first student in the list
    $_SESSION['currentStudent'] = serialize($students[0]);
    if(array_key_exists('currentStudent', $_SESSION)) {
      echo "SESSION contains currentStudent value";
    }

    
    
    //$_SESSION['current_student'] = $current_student;
    
    //echo "Current Student SESSION: ";
    //print_r $_SESSION['current_student'];
    //echo "<br />";
    //echo "Current Student: " . $current_student->get_full_name() . " <br />";
    
    
    // Variables needed: array of student_id values associated with the parent user
    // array of student names, pulled from user table and combined into one string for display
    // selected student to control which student's records are displayed
    // array of selected student values from student table
    // array for selected student's current goals
    // array for selected student's current objectives
    // report data for creating graph
    //  
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

        <?php
        // Toggle between different students for this user
        $studentCount = 0;
        foreach ($students as $value) {
          $studentName = $value->get_full_name();
          $studentId = $value->get_student_id();

          // Version from testing
          if ($studentCount == 0) {
            echo "<div class=\"tab\">";
            //echo "<a class='vNavButton, tablinks' href='' id='defaultOpen' onclick='openTab(event, \"" . $studentName . "\");' data-studentName=\"" . $studentName . "\" data-student_id='" . $studentId . "'><h3>" . $studentName . "</h3></a>";

            echo "<button class=\"tablinks vNavButton\" onclick=\"openTab(event, '" . $studentName . "')\" id=\"defaultOpen\" data-studentId=\"" . $studentId . "\" data-studentName=\"" . $studentName . "\">" . $studentName . "</button>";
            echo "</div>";
          } else {
            echo "<div class=\"tab\">";
            //echo "<a class='vNavButton, tablinks' href='' onclick='openTab(event, \"" . $studentName . "\");' data-studentName=\"" . $studentName . "\" data-student_id='" . $studentId . "'><h3>" . $studentName . "</h3></a>";

            echo "<button class=\"tablinks vNavButton\" data-studentId=\"" . $studentId . "\" data-studentName=\"" . $studentName . "\" onclick=\"openTab(event, '" . $studentName . "')\">" . $studentName . "</button>";
            echo "</div>";
        }
        

        $studentCount++;
          
      }
         
        ?>

      </div>

      <!-- Main content of page -->
      <?php
        // Generate content for each student, then only display one at a time
        foreach ($students as $value) {
          $current_student = $value;
          $current_student_name = $value->get_full_name();
          $current_student_id = $value->get_student_id();
          // create a mainContent Div for each Student
          // Only need to create an empty div here for each student with correct name, id and classes

          echo "<div class='middle mainContent tabcontent' id='" . $current_student_name . "' >";
            // Student Name
            echo "<div class='currentStudentName'>";
              echo "<h3>" . $current_student->get_full_name() . "</h3>";
            echo "</div>"; // end of student name div

            // Calendar
            echo "<div class='calendar contentCard'>";
              echo "<h3>Calendar</h3>";

            echo "</div>"; // end of Calendar div

            // Schedule
            echo "<div class='schedule contentCard'>";
              echo "<h3>Upcoming</h3>";
              echo "<ul>";
            
              // Display list of next IEP and next Evaluation for $current_student
              echo "<li> Next IEP due: " . $current_student->get_student_next_iep() . "</li>";
              echo "<li> Next Evaluation due: " . $current_student->get_student_next_evaluation() . "</li>";
            
            echo "</ul>";
            echo "</div>"; // end of Schedule div

            // Goals
            echo "<h3>Current Goals</h3>";
            // Display each student goal in a card, with each goal's objectives nested inside

            // New Goal button for Provider users
            if (get_class($currentUser) === 'Provider') {
               
              echo "<form action=\"iepProviderGoalForm.php\" method=\"post\">";
              echo "<input type=\"submit\" value=\"New Goal\">";
              echo "</form>";
              
            }
            
            if (isset($_POST['Submit'])) {
              //$_SESSION['currentStudent'] = serialize($current_student);
              //$_SESSION['currentStudent'] = $current_student;
              
            }

            $goals = $current_student->get_goals();
            foreach ($goals as $g) {
              // Collect objectives for this Goal
              $objectives = $g->get_objectives();
              // Display Content for each Goal
              echo "<div class='contentCard'>";
                echo "<h4>Goal:" . $g->get_goal_label() . "</h4>";
                echo "<p>Goal Description: " . $g->get_goal_text() . "</p>";
                // Display each Objective in a box
                foreach ($objectives as $o) {
                  // Collect Reports for this Objective
                  $reports = $o->get_reports();
                  echo "<div class='contentCard'>";
                  echo "<h5>Objective: " . $o->get_objective_label() . "</h5>";
                  // Display meter of latest report if available
                  if (isset($reports) && count($reports) > 0) {
                    // Display latest report information
                    $latest_report = $reports[0];
                    $max = $o->get_objective_attempts();
                    $high = $o->get_objective_target();
                    $low = $o->get_objective_target() /2;
                    $value = $latest_report->get_report_observed();
                    echo "<p>Latest Report: ";
                    echo "<meter min='0' max='" . $max . "' high='" . $high ."' low='" . $low . "' optimum='" . $max . "' value='" . $value . "'>" . $value . "</meter>";
                    echo "</p>";
                  } // end of if

                  // Expanded details for Objective
                  $objectiveDetailsID = "objective" . $o->get_objective_id();
                  echo "objectiveDetailsID: " . $objectiveDetailsID . "<br />";
                  echo "<div class='expandedDetails' id=" . $objectiveDetailsID . ">";
                    echo "<p>Description: " . $o->get_objective_text() ."</p> ";
                    echo "<p>Latest Report Date: " . $latest_report->get_report_date() . "</p>";
                    echo "<p>Report Data: Graph of report data to come</p>";
                  echo "</div>"; //end of expandedDetails

                  // Expand/Hide button
                  //echo "<script> showMessage(); </script>";
                  echo "<button type='custom' id='objectiveDetails' onclick='showHide(\"" . $objectiveDetailsID . "\");'>+</button>";
                  // Try PHP version of button
                  
                  echo "</div>"; // end of Objective Div

                } // end of foreach(objectives)

                // Expanded details for Goal
                $goalDetailsID = "goal" . $g->get_goal_id();
                echo "goalDetailsID: " . $goalDetailsID . "<br />";
                echo "<div class='expandedDetails' id=" . $goalDetailsID . ">";
                echo "<p>Category: " . $g->get_goal_category() . "</p>";
                echo "<p>Description: " . $g->get_goal_text() . "</p>";
                echo "<p>Date Range: " . $current_student->get_student_iep_date() . " - " . $current_student->get_student_next_iep() . "</p>";
                echo "</div>"; // end of expandedDetails

                // Expand/Hide button
                echo "<button type='custom' id='goalDetails' onclick='showHide(\"" . $goalDetailsID . "\");'>+</button>";
                //echo "<button type='custom' id='goalDetails' onclick='showHide(this);'>+</button>";
              echo "</div>"; // end of Goal Div
            } // end of foreach(goal)

          echo "</div>";  // end of div id='mainContent'
        } // end of foreach students as value
      ?>

      
      
      <footer>
        <!-- Insert footer info here -->
      </footer>

    </div>
  </body>

  <script>
      //jQuery
      $(document).ready(function() {
          $(".tablinks").click(function() {
              $(".tabcontent").load("mainContent.php", {
                  activeStudentId: $(this).attr("data-studentId"),
                  activeStudentName: $(this).attr("data-studentName")
              });
          });
          //Identify the defaultOpen element
          document.getElementById("defaultOpen").click();
      });

      function openTab(evt, tabName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
          tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
          tablinks[i].className = tablinks[i].className.replace(" active", "");
          
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
      }
    </script>
</html>
<?php
//Functions for this page


?>