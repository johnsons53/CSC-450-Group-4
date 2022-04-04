<?php 
 session_start();
 ini_set('display_errors', 1);
 error_reporting(E_ALL|E_STRICT);
 //$sessionStudent = unserialize($_SESSION['currentStudent']);
 //echo $sessionStudent->get_full_name();
 

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

 
 ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 <!DOCTYPE html>
 <html>

 <?php
    // Is the currentUser info in the SESSION array?
    if (array_key_exists('currentUser', $_SESSION)) {
        // Access currentUser
        $currentUser = unserialize($_SESSION['currentUser']);
        echo "User Name: ";
        echo $currentUser->get_full_name();
        echo "<br />";

    }

    if(array_key_exists('activeStudentId', $_POST)) {
        echo "activeStudentId: " . $_POST['activeStudentId'];
    }

    echo "Student Name: ";
    echo $_POST['activeStudentName'];
    echo "<br />";
    echo "Student ID: ";
    echo $_POST['activeStudentId'];
    echo "<br />";
    
    // Are the currentStudents in the SESSION array?
    if(array_key_exists('currentStudents', $_SESSION)) {
        echo "Found currentStudents in SESSION. <br />";
        // access currentStudents
        $myStudents = unserialize($_SESSION['currentStudents']);
        foreach($myStudents as $value) {
            // Look for match
            echo $value->get_full_name();
            echo "<br />";
            if($_POST['activeStudentId'] == $value->get_student_id()) {
                // modify activeStudent in SESSION
                echo "Found match for student ID. <br />";
                $_SESSION['activeStudent'] = serialize($value);
                echo "Saved student in SESSION array. <br />";
            } 
        }

    } else {
        echo "Did not find currentStudents in SESSION. <br />";
    }

    // Access activeStudent from SESSION
    $activeStudent = unserialize($_SESSION['activeStudent']);
    echo "Active Student Name: ";
    echo $activeStudent->get_full_name();
    echo "<br />";

    // Using activeStudent, access content

    // New Goal button for Provider users
    echo get_class($currentUser);

    if (get_class($currentUser) === 'Guardian') {
      echo "This user is a Guardian. <br />";
    }

    if (get_class($currentUser) === 'Provider') {
       
      echo "<form action=\"iepProviderGoalForm.php\" method=\"post\">";
      echo "<input type=\"submit\" value=\"New Goal\">";
      echo "</form>";
      
    }
    
    if (isset($_POST['Submit'])) {
      //$_SESSION['currentStudent'] = serialize($current_student);
      //$_SESSION['currentStudent'] = $current_student;
      
    }

    $goals = $activeStudent->get_goals();
            foreach ($goals as $g) {
              // Collect objectives for this Goal
              $objectives = $g->get_objectives();
              // Display Content for each Goal
              echo "<div class='contentCard'>";
                echo "<h4>Goal:" . $g->get_goal_label() . "</h4>";
                echo "<p>Goal Description: " . $g->get_goal_text() . "</p>";

                // Add buttons to modify goal or add new objective
                if (get_class($currentUser) === 'Provider') {
       
                    echo "<form action=\"iepProviderGoalForm.php\" method=\"post\">";
                    echo "<input type=\"submit\" value=\"Modify Goal\">";
                    echo "</form>";

                    echo "<form action=\"iepProviderObjectiveForm.php\" method=\"post\">";
                    echo "<input type=\"submit\" value=\"New Objective\">";
                    echo "</form>";
                    
                  }
                // Display each Objective in a box
                foreach ($objectives as $o) {
                  // Collect Reports for this Objective
                  $reports = $o->get_reports();
                  echo "<div class='contentCard'>";
                  echo "<h5>Objective: " . $o->get_objective_label() . "</h5>";

                    // Add buttons to modify objective or add new report
                    if (get_class($currentUser) === 'Provider') {
       
                    echo "<form action=\"iepProviderObjectiveForm.php\" method=\"post\">";
                    echo "<input type=\"submit\" value=\"Modify Objective\">";
                    echo "</form>";

                    echo "<form action=\"iepProviderReportForm.php\" method=\"post\">";
                    echo "<input type=\"submit\" value=\"New Report\">";
                    echo "</form>";
                    
                  }
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
                  echo "<button type='custom' id='objectiveDetails' onclick='showHide(\"" . $objectiveDetailsID . "\");'>+</button>";
                  
                  echo "</div>"; // end of Objective Div

                } // end of foreach(objectives)

                // Expanded details for Goal
                $goalDetailsID = "goal" . $g->get_goal_id();
                echo "goalDetailsID: " . $goalDetailsID . "<br />";
                echo "<div class='expandedDetails' id=" . $goalDetailsID . ">";
                echo "<p>Category: " . $g->get_goal_category() . "</p>";
                echo "<p>Description: " . $g->get_goal_text() . "</p>";
                echo "<p>Date Range: " . $activeStudent->get_student_iep_date() . " - " . $activeStudent->get_student_next_iep() . "</p>";
                echo "</div>"; // end of expandedDetails

                // Expand/Hide button
                echo "<button type='custom' id='goalDetails' onclick='showHide(\"" . $goalDetailsID . "\");'>+</button>";
              echo "</div>"; // end of Goal Div
            } // end of foreach(goal)
        
?>
  
</html>
