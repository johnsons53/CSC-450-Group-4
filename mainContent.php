<?php 
/*
mainContent.php - Main Content of Dashboard page
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 04/10/2022
      Revised: 
      04/15/2022: Converted Provider access to report forms from links to separate pages to content loaded
      into div on same page. Added jQuery/AJAX handling of forms and data.
      04/17/2022: Modified use of SESSION data;
      cleanup of unnecessary testing code;
      Completed data updating on changes to database for Report
*/
include_once realpath("initialization.php");
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 <!DOCTYPE html>
 <html>

 <?php

    global $conn;


    $currentUserId;
    $currentUserType;
    $activeStudent;
    $activeStudentName;
    $activeStudentId;


    // Did mainContent.php load as the result of a tab click? 
    // Check for POST["activeStudentId"]
    if(array_key_exists("activeStudentId", $_POST)) {
      // update the value in SESSION
      $_SESSION["activeStudentId"] = $_POST["activeStudentId"];
    }

    // Look for activeStudentId, currentUserId and type in SESSION
    try {
      $currentUserId = $_SESSION["currentUserId"];
      $currentUserType = $_SESSION["currentUserType"];
      $activeStudentId = $_SESSION["activeStudentId"];
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }

    // Use $activeStudentId to create $currentStudent
    try {
      $activeStudent = createStudent($activeStudentId, $conn);
      $activeStudentName = $activeStudent->get_full_name();
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }

    // Student Name
    echo "<div class='currentStudentName'>";
    echo "<h3>" . $activeStudentName . "</h3>";
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
      echo "<input type=\"hidden\" id=\"DstudentId\" name=\"activeStudentId\" value=\"" . $activeStudentId . "\">";
      echo "<input type=\"hidden\" id=\"DstudentName\" name=\"activeStudentName\" value=\"" . $activeStudentName . "\">";
      // New Goal button
      echo "<input type=\"submit\" name=\"documents\" value=\"Documents\">";
      echo "</form>";

    echo "</div>"; // end of Documents div

    // Goals
    echo "<h3>Current Goals</h3>";
    // Display each student goal in a card, with each goal's objectives nested inside
     // New Goal button for Provider users

    if (strcmp($currentUserType, "provider") === 0) {
       
      /* NEW GOAL BUTTON */
      echo "<form action=\"" . htmlspecialchars("iepProviderGoalForm.php") . "\" method=\"post\">";
      // Add hidden fields with data to send to report form. 
      //echo "<input type=\"hidden\" id=\"NGgoalId\" name=\"goalId\" value=\"\">";
      echo "<input type=\"hidden\" id=\"NGstudentId\" name=\"activeStudentId\" value=\"" . $activeStudentId . "\">";
      echo "<input type=\"hidden\" id=\"NGstudentName\" name=\"activeStudentName\" value=\"" . $activeStudentName . "\">";
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
          if (strcmp($currentUserType, "provider") === 0) {
  
              /* UPDATE GOAL BUTTON */
              echo "<form action=\"" . htmlspecialchars("iepProviderGoalForm.php") . "\" method=\"post\">";
              // Add hidden fields with data to send to report form. 
              echo "<input type=\"hidden\" id=\"UGstudentId\" name=\"activeStudentId\" value=\"" . $activeStudentId . "\">";
              echo "<input type=\"hidden\" id=\"UGstudentName\" name=\"activeStudentName\" value=\"" . $activeStudentName . "\">";
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
              echo "<input type=\"hidden\" id=\"NOgoalId" . $goalId . "\" name=\"goalId\" value=\"" . $goalId . "\">";
              echo "<input type=\"hidden\" id=\"NOstudentName" . $activeStudentName . "\" name=\"activeStudentName\" value=\"" . $activeStudentName . "\">";
              echo "<input type=\"hidden\" id=\"NOgoalLabel" . $goalId . "\" name=\"goalLabel\" value=\"" . $goalLabel . "\">";
              // new objective submit button
              echo "<input type=\"submit\" name=\"newObjective\" value=\"New Objective\">";
              echo "</form>";

              /* DELETE GOAL BUTTON change to regular button*/
              echo "<input type=\"submit\" class=\"deleteGoal\" name=\"deleteGoal\" data-goalId=\"" . $goalId . "\"value=\"Delete Goal\">";

              /* Div to confirm goal deletion */
              echo "<div class=\"deleteGoalMessage\" id=\"deleteGoalMessage" . $goalId . "\">";
              echo "<p>This Div id: deleteGoalMessage" . $goalId . "</p>";
              echo "</div>";

              
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
              if (strcmp($currentUserType, "provider") === 0) {

  
              /* UPDATE OBJECTIVE BUTTON */
              echo "<form action=\"" . htmlspecialchars("iepProviderObjectiveForm.php") . "\" method=\"post\">";
              // hidden fields with data to send to report form.
              echo "<input type=\"hidden\" id=\"UOobjectiveId" . $objectiveId . "\" name=\"objectiveId\" value=\"" . $objectiveId . "\">";
              echo "<input type=\"hidden\" id=\"UOGoalId" . $goalId . "\" name=\"goalId\" value=\"" . $goalId . "\">";
              echo "<input type=\"hidden\" id=\"UOStudentName" . $activeStudentName . "\" name=\"activeStudentName\" value=\"" . $activeStudentName . "\">";
              echo "<input type=\"hidden\" id=\"UOgoalLabel" . $goalId . "\" name=\"goalLabel\" value=\"" . $goalLabel . "\">";
              echo "<input type=\"hidden\" id=\"UOobjectiveLabel" . $objectiveId . "\" name=\"objectiveLabel\" value=\"" . $objectiveLabel . "\">";
              echo "<input type=\"hidden\" id=\"UOobjectiveText" . $objectiveId . "\" name=\"objectiveText\" value=\"" . $objectiveText . "\">";
              echo "<input type=\"hidden\" id=\"UOobjectiveAttempts" . $objectiveId . "\" name=\"objectiveAttempts\" value=\"" . $objectiveAttempts . "\">";
              echo "<input type=\"hidden\" id=\"UOobjectiveTarget" . $objectiveId . "\" name=\"objectiveTarget\" value=\"" . $objectiveTarget . "\">";
              echo "<input type=\"hidden\" id=\"UOobjectiveStatus" . $objectiveId . "\" name=\"objectiveStatus\" value=\"" . $objectiveStatus . "\">";
              //Update Objective button
              echo "<input type=\"submit\" id=\"updateObjective" . $objectiveId . "\" class=\"updateObjective\" name=\"updateObjective\" value=\"Update Objective\">";
              echo "</form>";
              
            }

            if (isset($reports) && count($reports) > 0) {
              // Select Report to display, default to most recent
              //echo "<form action=\"\" method=\"post\">";

              echo "<label for=\"reportSelect\">Select Report Date</label>";
              echo "<select name=\"reportSelect\" class=\"reportSelect\" id=\"reportSelect" . $objectiveId . "\" data-objectiveId=\"" . $objectiveId . "\" data-max=\"" . $objectiveAttempts . "\" data-high=\"" . $objectiveTarget . "\" data-low=\"" . $objectiveTarget/2 . "\">";
                // Options for reportSelect
                $reportCount = 0;
                foreach($reports as $r) {
                  if ($reportCount === 0) {
                    echo "<option class=\"reportOption\" data-reportdate=\"" . $r->get_report_date() . "\" data-reportid=\"" . $r->get_report_id() . "\" value=\"" . $r->get_report_observed() . "\" selected=\"selected\">" . $r->get_report_date() . "</option>";
                    $reportCount++;
                  } else {
                    echo "<option class=\"reportOption\" data-reportdate=\"" . $r->get_report_date() . "\" data-reportid=\"" . $r->get_report_id() . "\" value=\"" . $r->get_report_observed() . "\">" . $r->get_report_date() . "</option>";
                  }
                }
              echo "</select>"; // end of select

              // Report buttons for Provider Users
              if (strcmp($currentUserType, "provider") === 0) {
                echo "<div>";
                // Modify selected report button
                // Open form with values of selected report
                echo "<input type=\"submit\" data-objectiveid=\"" . $objectiveId . "\"id=\"modifyReport" . $objectiveId . "\" class=\"reportFormButton\" name=\"modifyReport\" value=\"Modify Selected Report\">";

                // Add new report button
                // Open form with only objectiveId value
                echo "<input type=\"submit\" data-objectiveid=\"" . $objectiveId . "\" data-reportDate=\"\" data-reportId=\"\" data-reportObserved=\"\" id=\"addReport" . $objectiveId . "\" class=\"reportFormButton\" name=\"addReport\" value=\"New Report\">";

                //echo "</form>";

                // Div for Report Form, do not display unless Report buttons clicked
                echo "<div class=\"reportForm\" id=\"reportForm" . $objectiveId . "\" display=\"block\">";

                echo "</div>"; // end of Report Form Div

                echo "</div>";

              }


              $selectedReport = $reports[0];
              $selectedReportId = $selectedReport->get_report_id();
              echo "Initial selectedReportId: " . $selectedReportId;
              echo ", Selected Report Date: ";
              echo $selectedReport->get_report_date();
              echo "<br />";
              // Div to display selected report meter
              echo "<div class=\"reportMeter\" id=\"reportMeter" . $objectiveId . "\">";

              echo "</div>"; //end of reportMeter div

              } else {
                echo "<p>No reports to display.</p>";
            }// end of if

            
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
