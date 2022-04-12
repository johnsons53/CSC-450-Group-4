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
                echo "Saved activeStudent in SESSION array. <br />";
            } 
        }

    } else {
        echo "Did not find currentStudents in SESSION. <br />";
    }

    // Access activeStudent from SESSION
    $activeStudent = unserialize($_SESSION['activeStudent']);
    echo "Active Student Name: ";
    echo $activeStudent->get_full_name();
    $studentName = $activeStudent->get_full_name();
    $studentId = $activeStudent->get_student_id();
    echo "<br />";

    // Using activeStudent, access content

   

    if (get_class($currentUser) === 'Guardian') {
      echo "This user is a Guardian. <br />";
    }

    // Student Name
    echo "<div class='currentStudentName'>";
    echo "<h3>" . $studentName . "</h3>";
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
      echo "<li> Next IEP due: " . $activeStudent->get_student_next_iep() . "</li>";
      echo "<li> Next Evaluation due: " . $activeStudent->get_student_next_evaluation() . "</li>";
    
    echo "</ul>";
    echo "</div>"; // end of Schedule div

    // Documents Page Link
    echo "<div class='documents contentCard'>";
      echo "<h3>Documents</h3>";
      /* DOCUMENTS page button */
      echo "<form action=\"" . htmlspecialchars("iepDocumentView.php") . "\" method=\"post\">";
      echo "<input type=\"hidden\" id=\"DstudentId\" name=\"studentId\" value=\"" . $studentId . "\">";
      echo "<input type=\"hidden\" id=\"DstudentName\" name=\"studentName\" value=\"" . $studentName . "\">";
      // New Goal button
      echo "<input type=\"submit\" name=\"documents\" value=\"Documents\">";
      echo "</form>";

    echo "</div>"; // end of Documents div

    // Goals
    echo "<h3>Current Goals</h3>";
    // Display each student goal in a card, with each goal's objectives nested inside
     // New Goal button for Provider users

    if (get_class($currentUser) === 'Provider') {
       
      /* NEW GOAL BUTTON */
      echo "<form action=\"" . htmlspecialchars("iepProviderGoalForm.php") . "\" method=\"post\">";
      // Add hidden fields with data to send to report form. 
      //echo "<input type=\"hidden\" id=\"NGgoalId\" name=\"goalId\" value=\"\">";
      echo "<input type=\"hidden\" id=\"NGstudentId\" name=\"studentId\" value=\"" . $studentId . "\">";
      echo "<input type=\"hidden\" id=\"NGstudentName\" name=\"studentName\" value=\"" . $studentName . "\">";
      // New Goal button
      echo "<input type=\"submit\" name=\"newGoal\" value=\"New Goal\">";
      echo "</form>";
      
    }

    $goals = $activeStudent->get_goals();
      foreach ($goals as $g) {
        // Collect objectives for this Goal
        $goalId = $g->get_goal_id();
        $goalLabel = $g->get_goal_label();
        $goalCategory = $g->get_goal_category();
        $goalText = $g->get_goal_text();
        $goalActive = $g->get_goal_active();

        $objectives = $g->get_objectives();
        // Display Content for each Goal
        echo "<div class='contentCard'>";
          echo "<h4>Goal:" . $g->get_goal_label() . "</h4>";
          echo "<p>Goal Description: " . $g->get_goal_text() . "</p>";

          // Add buttons to modify goal or add new objective
          if (get_class($currentUser) === 'Provider') {
  
              /* UPDATE GOAL BUTTON */
              echo "<form action=\"" . htmlspecialchars("iepProviderGoalForm.php") . "\" method=\"post\">";
              // Add hidden fields with data to send to report form. 
              echo "<input type=\"hidden\" id=\"UGstudentId\" name=\"studentId\" value=\"" . $studentId . "\">";
              echo "<input type=\"hidden\" id=\"UGstudentName\" name=\"studentName\" value=\"" . $studentName . "\">";
              echo "<input type=\"hidden\" id=\"UGgoalId\" name=\"goalId\" value=\"" . $goalId . "\">";
              echo "<input type=\"hidden\" id=\"UGgoalLabel\" name=\"goalLabel\" value=\"" . $goalLabel . "\">";
              echo "<input type=\"hidden\" id=\"UGgoalCategory\" name=\"goalCategory\" value=\"" . $goalCategory . "\">";
              echo "<input type=\"hidden\" id=\"UGgoalText\" name=\"goalText\" value=\"" . $goalText . "\">";
              echo "<input type=\"hidden\" id=\"UGgoalActive\" name=\"goalActive\" value=\"" . $goalActive . "\">";
              // update goal submit button
              echo "<input type=\"submit\" name=\"updateGoal\" value=\"Update Goal\">";
              echo "</form>";

              /* NEW OBJECTIVE BUTTON */
              echo "<form action=\"" . htmlspecialchars("iepProviderObjectiveForm.php") . "\" method=\"post\">";
              // Add hidden fields with data to send to report form. 
              echo "<input type=\"hidden\" id=\"NOGoalId" . $goalId . "\" name=\"goalId\" value=\"" . $goalId . "\">";
              echo "<input type=\"hidden\" id=\"NOStudentName" . $studentName . "\" name=\"studentName\" value=\"" . $studentName . "\">";
              echo "<input type=\"hidden\" id=\"NOgoalLabel" . $goalId . "\" name=\"goalLabel\" value=\"" . $goalLabel . "\">";
              // new objective submit button
              echo "<input type=\"submit\" name=\"newObjective\" value=\"New Objective\">";
              echo "</form>";
              
            }
          // Display each Objective in a box
          foreach ($objectives as $o) {
            // Collect Reports for this Objective
            $objectiveId = $o->get_objective_id();
            $objectiveLabel = $o->get_objective_label();
            $objectiveText = $o->get_objective_text();
            $objectiveAttempts = $o->get_objective_attempts();
            $objectiveTarget = $o->get_objective_target();
            $objectiveStatus = $o->get_objective_status();

            $reports = $o->get_reports();
            echo "<div class='contentCard'>";
            echo "<h5>Objective: " . $objectiveLabel . "</h5>";

              // Add buttons to modify objective or add new report
              if (get_class($currentUser) === 'Provider') {

  
              /* UPDATE OBJECTIVE BUTTON */
              echo "<form action=\"" . htmlspecialchars("iepProviderObjectiveForm.php") . "\" method=\"post\">";
              // hidden fields with data to send to report form.
              echo "<input type=\"hidden\" id=\"UOobjectiveId" . $objectiveId . "\" name=\"objectiveId\" value=\"" . $objectiveId . "\">";
              echo "<input type=\"hidden\" id=\"UOGoalId" . $goalId . "\" name=\"goalId\" value=\"" . $goalId . "\">";
              echo "<input type=\"hidden\" id=\"UOStudentName" . $studentName . "\" name=\"studentName\" value=\"" . $studentName . "\">";
              echo "<input type=\"hidden\" id=\"UOgoalLabel" . $goalId . "\" name=\"goalLabel\" value=\"" . $goalLabel . "\">";
              echo "<input type=\"hidden\" id=\"UOobjectiveLabel" . $objectiveId . "\" name=\"objectiveLabel\" value=\"" . $objectiveLabel . "\">";
              echo "<input type=\"hidden\" id=\"UOobjectiveText" . $objectiveId . "\" name=\"objectiveText\" value=\"" . $objectiveText . "\">";
              echo "<input type=\"hidden\" id=\"UOobjectiveAttempts" . $objectiveId . "\" name=\"objectiveAttempts\" value=\"" . $objectiveAttempts . "\">";
              echo "<input type=\"hidden\" id=\"UOobjectiveTarget" . $objectiveId . "\" name=\"objectiveTarget\" value=\"" . $objectiveTarget . "\">";
              echo "<input type=\"hidden\" id=\"UOobjectiveStatus" . $objectiveId . "\" name=\"objectiveStatus\" value=\"" . $objectiveStatus . "\">";
              //Update Objective button
              echo "<input type=\"submit\" id=\"updateObjective" . $objectiveId . "\" class=\"updateObjective\" name=\"updateObjective\" value=\"Update Objective\">";
              echo "</form>";

              /* NEW REPORT BUTTON */
              echo "<form action=\"" . htmlspecialchars("iepProviderReportForm.php") . "\" method=\"post\">";
              // Add hidden fields with data to send to report form.
              echo "<input type=\"hidden\" id=\"objectiveId" . $objectiveId . "\" name=\"objectiveId\" value=\"" . $objectiveId . "\">";
              echo "<input type=\"hidden\" id=\"objectiveLabel" . $objectiveId . "\" name=\"objectiveLabel\" value=\"" . $objectiveLabel . "\">";
              echo "<input type=\"hidden\" id=\"goalId" . $goalId . "\" name=\"goalId\" value=\"" . $goalId . "\">";
              echo "<input type=\"hidden\" id=\"goalLabel" . $goalId . "\" name=\"goalLabel\" value=\"" . $goalLabel . "\">";
              echo "<input type=\"hidden\" id=\"studentName" . $studentName . "\" name=\"studentName\" value=\"" . $studentName . "\">";
              // New Report button
              echo "<input type=\"submit\" id=\"newReport" . $objectiveId . "\" class=\"newReport\" name=\"newReport\" value=\"New Report\">";
              echo "</form>";
              
            }

            if (isset($reports) && count($reports) > 0) {
              // Select Report to display, default to most recent
              echo "<label for=\"reportSelect\">Select Report Date</label>";
              echo "<select name=\"reportSelect\" class=\"reportSelect\" id=\"reportSelect\" data-objectiveId=\"" . $objectiveId . "\" data-max=\"" . $objectiveAttempts . "\" data-high=\"" . $objectiveTarget . "\" data-low=\"" . $objectiveTarget/2 . "\">";
                // Options for reportSelect
                $reportCount = 0;
                foreach($reports as $r) {
                  if ($reportCount === 0) {
                    echo "<option class=\"reportOption\" value=\"" . $r->get_report_observed() . "\" selected=\"selected\" >" . $r->get_report_date() . "</option>";
                    $reportCount++;
                  } else {
                    echo "<option class=\"reportOption\" value=\"" . $r->get_report_observed() . "\">" . $r->get_report_date() . "</option>";
                  }
                }
              echo "</select>"; // end of select

              $selectedReport = $reports[0];
              $selectedReportId = $selectedReport->get_report_id();
              echo "Initial selectedReportId: " . $selectedReportId;
              echo ", Selected Report Date: ";
              echo $selectedReport->get_report_date();
              echo "<br />";
              // Div to display selected report meter
              echo "<div class=\"reportMeter\" id=\"reportMeter" . $objectiveId . "\">";
                  // Display latest report information

                  $max = $objectiveAttempts;
                  $high = $objectiveTarget;
                  $low = $objectiveTarget /2;
                  $value = $selectedReport->get_report_observed();
                  echo "<p>";
                  echo "<label for=\"reportMeter\">Report Meter:</label>";
                  echo "<meter name=\"reportMeter\" min='0' max='" . $max . "' high='" . $high ."' low='" . $low . "' optimum='" . $max . "' value='" . $value . "'>" . $value . "</meter>";
                  echo "</p>";

              echo "</div>";

              } else {
                echo "<p>No reports to display.</p>";
            }// end of if
            //echo "</div>";
            
            // Expanded details for Objective
            $objectiveDetailsID = "objective" . $o->get_objective_id();
            echo "objectiveDetailsID: " . $objectiveDetailsID . "<br />";
            echo "<div class='expandedDetails' id=" . $objectiveDetailsID . ">";
              echo "<p>Description: " . $o->get_objective_text() ."</p> ";
              echo "<p>Latest Report Date: " . $selectedReport->get_report_date() . "</p>";
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
