<?php
session_start();
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
    // Select a guardian from the database for demonstration purposes
    $sql = "SELECT * 
            FROM user
            WHERE user_id='12'";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // show the data in each row
        while ($row = $result->fetch_assoc()) {
            // new Guardian object from row data
            $guardian = new Guardian($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                $row['user_city'], $row['user_district'], $row['user_type']);
            // add current user to $users array
            $_SESSION['guardianUser'] = $guardian;
            echo $guardian->get_full_name() . " created as GUARDIAN <br />";
            echo "User for this SESSION: " . $_SESSION['guardianUser']->get_full_name() . " <br />";

        } 
    } else {
        echo "0 results <br />";
    }
    $students = $_SESSION['guardianUser']->get_guardian_students();
    $current_student = $students[0];
    echo "Current Student: " . $current_student->get_full_name() . " <br />";
    
    
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
        // Links to each student for this user
        // Make these buttons that change the value of $current_student
        foreach ($students as $value) {
          echo "<a class='vNavButton' href=''><h3>" . $value->get_full_name() . "</h3></a>";
          //echo "<button type='custom' id='" . $value->get_full_name() . "' onclick='" . $current_student = $value ."'>" . $value->get_full_name() . "</button>";
        }
        ?>
      </div>

      <!-- Main content of page -->
      <div class="middle" id="mainContent">
        <div class="currentStudentName">
            <?php echo "<h3>" . $current_student->get_full_name() . "</h3>"; ?>
        </div>   
        <div class="calendar contentCard">
            <h3>Calendar</h3>

        </div>
        <div class="schedule contentCard">
            <h3>Upcoming</h3>
            <ul>
            <?php
              // Display list of next IEP and next Evaluation for $current_student
              echo "<li> Next IEP due: " . $current_student->get_student_next_iep() . "</li>";
              echo "<li> Next Evaluation due: " . $current_student->get_student_next_evaluation() . "</li>";
            ?>
            </ul>
        </div>
        <h3>Current Goals</h3>
        <?php 
        // Display each student goal in a card, with each goal's objectives nested inside
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
            }

            // Expanded details for Objective
            echo "<div class='expandedDetails'>";
            echo "<p>Description: " . $o->get_objective_text() ."</p> ";
            echo "<p>Latest Report Date: " . $latest_report->get_report_date() . "</p>";
            echo "<p>Report Data: Graph of report data to come</p>";
            echo "</div>";

            // Expand/Hide button
            echo "<button type='custom' id='objectiveDetails' onclick='showHide(this);'>+</button>";
            
            echo "</div>"; // end of Objective Div

            // Expanded details for Goal
            echo "<div class='expandedDetails'>";
            echo "<p>Category: " . $g->get_goal_category() . "</p>";
            echo "<p>Description: " . $g->get_goal_text() . "</p>";
            echo "<p>Date Range: " . $current_student->get_student_iep_date() . " - " . $current_student->get_student_next_iep() . "</p>";
            echo "</div>";

            // Expand/Hide button
            echo "<button type='custom' id='objectiveDetails' onclick='showHide(this);'>+</button>";

          }
          echo "</div>"; // end of Goal Div
        }
        ?>
        

      
      <footer>
        <!-- Insert footer info here -->
      </footer>

    </div>
  </body>
</html>