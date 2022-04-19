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
      04/18/2022: Converted provider objective froms to open in divs on dashboard page;
      Completed form handling for objective changes
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
      echo "<input type=\"submit\" name=\"documents\" value=\"Documents\">";
      echo "</form>";

    echo "</div>"; // end of Documents div

    // Goals
    echo "<h3>Current Goals</h3>";
    // Display each student goal in a card, with each goal's objectives nested inside
     // New Goal button for Provider users

    if (strcmp($currentUserType, "provider") === 0) {
       
      /* NEW GOAL BUTTON */
      echo "<input type=\"button\" id=\"newGoal" . $activeStudentId . "\" " . 
      "data-studentId=\"" . $activeStudentId . "\" " .
      "class=\"newGoalFormButton\" name=\"newGoal\" value=\"New Goal\">";

      // Div for new goal form
      echo "<div class=\"newGoalForm\" id=\"newGoalForm" . $activeStudentId . "\" display=\"block\">";

      echo "</div>";
      /*
      echo "<form action=\"" . htmlspecialchars("iepProviderGoalForm.php") . "\" method=\"post\">";
      // Add hidden fields with data to send to report form. 
      //echo "<input type=\"hidden\" id=\"NGgoalId\" name=\"goalId\" value=\"\">";
      echo "<input type=\"hidden\" id=\"NGstudentId\" name=\"activeStudentId\" value=\"" . $activeStudentId . "\">";
      echo "<input type=\"hidden\" id=\"NGstudentName\" name=\"activeStudentName\" value=\"" . $activeStudentName . "\">";
      // New Goal button
      echo "<input type=\"submit\" name=\"newGoal\" value=\"New Goal\">";
      echo "</form>";

      */
      
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

          // Provider Modify and Delete Goal Buttons and form
          if (strcmp($currentUserType, "provider") === 0) {

            echo "<div class=\"goalChanges\">";
            // Modify current objective--load goalForm.php below with activeStudentId, goalId, goalLabel, goalCategory
            // goalText, goalActive
            echo "<input type=\"button\" id=\"modifyGoal" . $goalId . "\" " . 
                "data-studentId=\"" . $activeStudentId . "\" " .
                "data-goalId=\"" . $goalId . "\" " .
                "data-goalLabel=\"" . $goalLabel . "\" " . 
                "data-goalCategory=\"" . $goalCategory . "\" " .
                "data-goalText=\"" . $goalText . "\" " . 
                "data-goalActive=\"" . $goalActive . "\" " .  
                "class=\"modifyGoalFormButton\" name=\"modifyGoal\" value=\"Modify Goal\">";
            // Delete current objective
            echo "<input type=\"button\" data-goalId=\"" . $goalId . "\"id=\"deleteGoal" . $goalId . "\"" . 
                " class=\"deleteGoalButton\" name=\"deleteGoal\" value=\"Delete Goal\">";

            // Div for modify objective form
            echo "<div class=\"modifyGoalForm\" id=\"modifyGoalForm" . $goalId . "\" display=\"block\">";

            echo "</div>";


          echo "</div>"; // end of objectiveChanges
  
              /* UPDATE GOAL BUTTON */
              /*
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
*/
              /* DELETE GOAL BUTTON change to regular button*/
             // echo "<input type=\"submit\" class=\"deleteGoal\" name=\"deleteGoal\" data-goalId=\"" . $goalId . "\"value=\"Delete Goal\">";

              /* Div to confirm goal deletion */
              /*
              echo "<div class=\"deleteGoalMessage\" id=\"deleteGoalMessage" . $goalId . "\">";
              echo "<p>This Div id: deleteGoalMessage" . $goalId . "</p>";
              echo "</div>";
              */

            /* NEW OBJECTIVE BUTTON */

            echo "<input type=\"button\" id=\"newObjective" . $goalId . "\" " . 
              "data-goalId=\"" . $goalId . "\" " .
              "class=\"newObjectiveFormButton\" name=\"newObjective\" value=\"New Objective\">";

            // Div for new ojbective form
            echo "<div class=\"newObjectiveForm\" id=\"newObjectiveForm" . $goalId . "\" display=\"block\">";

            echo "</div>";

              

              
          } // end of if userType === provider
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

            /* PROVIDER OBJECTIVE BUTTONS */
            if (strcmp($currentUserType, "provider") === 0) {
              echo "<div class=\"objectiveChanges\">";
                // Modify current objective--load objectiveForm.php below with objectiveId, goalId, objectiveLabel, objectiveText
                // objectiveAttempts, objectiveTarget, objectiveStatus
                echo "<input type=\"button\" id=\"modifyObjective" . $objectiveId . "\" " . 
                    "data-objectiveid=\"" . $objectiveId . "\" " .
                    "data-goalId=\"" . $goalId . "\" " .
                    "data-objectiveLabel=\"" . $objectiveLabel . "\" " . 
                    "data-objectiveText=\"" . $objectiveText . "\" " .
                    "data-objectiveAttempts=\"" . $objectiveAttempts . "\" " . 
                    "data-objectiveTarget=\"" . $objectiveTarget . "\" " .  
                    "data-objectiveStatus=\"" . $objectiveStatus . "\" " . 
                    "class=\"modifyObjectiveFormButton\" name=\"modifyObjective\" value=\"Modify Objective\">";
                // Delete current objective
                echo "<input type=\"button\" data-objectiveid=\"" . $objectiveId . "\"id=\"deleteObjective" . $objectiveId . "\"" . 
                    " class=\"deleteObjectiveButton\" name=\"deleteObjective\" value=\"Delete Objective\">";

                // Div for modify objective form
                echo "<div class=\"modifyObjectiveForm\" id=\"modifyObjectiveForm" . $objectiveId . "\" display=\"block\">";

                echo "</div>";


              echo "</div>"; // end of objectiveChanges
            } // end of if userType === provider

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

              /* PROVIDER REPORT BUTTONS */
              if (strcmp($currentUserType, "provider") === 0) {
                echo "<div class\"reportChanges>";
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

              // Div to display selected report meter
              echo "<div class=\"reportMeter\" id=\"reportMeter" . $objectiveId . "\">";

              echo "</div>"; //end of reportMeter div

              } else {
                echo "<p>No reports to display.</p>";
            }// end of if

            
            // Expanded details for Objective
            $objectiveDetailsID = "objective" . $o->get_objective_id();
            echo "<div class='expandedDetails' id=\"" . $objectiveDetailsID . "\" display=\"none\">";
              echo "<p>Description: " . $o->get_objective_text() ."</p> ";
              if (isset($reports) && count($reports) > 0) {
                echo "<p>Latest Report Date: " . $reports[0]->get_report_date() . "</p>";
                echo "<p>Report Data: Graph of report data to come</p>";
              }
            echo "</div>"; //end of expandedDetails

            // Expand/Hide button
            echo "<button type='custom' class=\"detailViewButton\" data-detailDivId=\"" . $objectiveDetailsID . "\" id='objectiveDetails' onclick='showHide(\"" . $objectiveDetailsID . "\");'>+</button>";
            
            echo "</div>"; // end of Objective Div

          } // end of foreach(objectives)

          // Expanded details for Goal
          $goalDetailsID = "goal" . $g->get_goal_id();
          echo "<div class='expandedDetails' id=\"" . $goalDetailsID . "\" display=\"none\">";
          echo "<p>Category: " . $g->get_goal_category() . "</p>";
          echo "<p>Description: " . $g->get_goal_text() . "</p>";
          echo "<p>Date Range: " . $activeStudent->get_student_iep_date() . " - " . $activeStudent->get_student_next_iep() . "</p>";
          echo "</div>"; // end of expandedDetails

          // Expand/Hide button
          echo "<button type='custom' class=\"detailViewButton\" data-detailDivId=\"" . $goalDetailsID . "\" id='goalDetails' onclick='showHide(\"" . $goalDetailsID . "\");'>+</button>";
        echo "</div>"; // end of Goal Div
      } // end of foreach(goal)

        
?>
  
</html>
