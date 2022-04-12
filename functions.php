<?php
/*
functions.php - library of functions for iepPortal
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Created: 04/06/2022
      Revised: 
*/
function test() {
    echo "Function test() called from functions.php.<br />";
}
/*
Goal functions: deleteGoal, insertGoal, updateGoal 
*/
function deleteGoal($conn, $goalId) {
    // Delete selected goal in the database
    $stmt = $conn->prepare("DELETE 
                            FROM goal
                            WHERE goal_id=?");

    // prepare statement, bind parameters
    $stmt->bind_param("i", $goalId);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    return true;

}
function insertGoal($conn, $studentId, $goalLabel, $goalCategory, $goalText, $goalActive) {
    //Insert form data into database using prepared statement and bound parameters
    $stmt = $conn->prepare("INSERT INTO goal (student_id, goal_label, goal_category, goal_text, goal_active) 
                        VALUES (?,?,?,?,?)");

    // prepare statement, bind parameters
    $stmt->bind_param("isssi", $studentId, $goalLabel, $goalCategory, $goalText, $goalActive);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    return true; 
}
function updateGoal($conn, $studentId, $goalLabel, $goalCategory, $goalText, $goalActive, $goalId) {
    // Update selected goal in the database
    $stmt = $conn->prepare("UPDATE goal
                            SET student_id=?,
                                goal_label=?,
                                goal_category=?,
                                goal_text=?,
                                goal_active=?
                            WHERE goal_id=?");

    // prepare statement, bind parameters
    $stmt->bind_param("isssii", $studentId, $goalLabel, $goalCategory, $goalText, $goalActive, $goalId);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    return true;
}

function refreshGoals() {

}
/*
Objective functions: deleteObjective, insertObjective, updateObjective 
*/
function deleteObjective($conn, $objectiveId) {
    // Delete selected objective in the database
    $stmt = $conn->prepare("DELETE 
                            FROM objective
                            WHERE objective_id=?");

    // prepare statement, bind parameters
    $stmt->bind_param("i", $objectiveId);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    return true;

}
function insertObjective($conn, $goalId, $objectiveLabel, $objectiveText, $objectiveAttempts, $objectiveTarget, $objectiveStatus) {
    //Insert form data into database using prepared statement and bound parameters
    $stmt = $conn->prepare("INSERT INTO objective (goal_id, objective_label, objective_text, objective_attempts, objective_target, objective_status) 
                        VALUES (?,?,?,?,?,?)");

    // prepare statement, bind parameters
    $stmt->bind_param("issiii", $goalId, $objectiveLabel, $objectiveText, $objectiveAttempts, $objectiveTarget, $objectiveStatus);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    return true; 

}
function updateObjective ($conn, $objectiveId, $goalId, $objectiveLabel, $objectiveText, $objectiveAttempts, $objectiveTarget, $objectiveStatus) {
    // Update selected objective in the database
    $stmt = $conn->prepare("UPDATE objective
                            SET goal_id=?,
                                objective_label=?,
                                objective_text=?,
                                objective_attempts=?,
                                objective_target=?,
                                objective_status=?
                            WHERE objective_id=?");

    // prepare statement, bind parameters
    $stmt->bind_param("issiiii", $goalId, $objectiveLabel, $objectiveText, $objectiveAttempts, $objectiveTarget, $objectiveStatus, $objectiveId);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    return true;
}
function refreshObjectives($conn, $student) {

    foreach ($student->goals as $value) {
        // clear existing reports
        $value->$objectives = [];
        $value->store_objectives($conn, $value->get_goal_id());
    }
}
/*
Report functions: deleteReport, insertReport, updateReport 
*/
function deleteReport($conn, $reportId, $student) {
    // Delete selected report in the database
    $stmt = $conn->prepare("DELETE 
                            FROM report
                            WHERE report_id=?");

    // prepare statement, bind parameters
    $stmt->bind_param("i", $reportId);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    //refresh reports for this student
    refreshReports($student);

    return true;
}
function insertReport($conn, $objectiveId, $reportDate, $reportObserved, $student) {
    //Insert form data into database using prepared statement and bound parameters
    $stmt = $conn->prepare("INSERT INTO report (objective_id, report_date, report_observed) VALUES (?,?,?)");

    // prepare statement, bind parameters
    $stmt->bind_param("isi", $objectiveId, $reportDate, $reportObserved);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    //refresh reports for this student
    refreshReports($student);

    return true;
}

function updateReport($conn, $objectiveId, $reportDate, $reportObserved, $reportId, $student) {
    // Update selected report in the database
    $stmt = $conn->prepare("UPDATE report
                            SET objective_id=?,
                                report_date=?,
                                report_observed=?
                            WHERE report_id=?");

    // prepare statement, bind parameters
    $stmt->bind_param("isii", $objectiveId, $reportDate, $reportObserved, $reportId);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    //refresh reports for this student
    refreshReports($student);

    return true;
}
function refreshReports($student) {

    foreach ($student->goals as $g) {
        foreach ($g->objectives as $o) {
            // clear existing reports
            $o->reports = [];
            $o->store_reports($o->get_objective_id());
        }
       
    }
    echo "reports refreshed :-) <br/ >";

}

?>