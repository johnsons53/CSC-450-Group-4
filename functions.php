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
function deleteGoal() {

}
function insertGoal() {

}
function updateGoal() {

}
/*
Objective functions: deleteObjective, insertObjective, updateObjective 
*/
function deleteObjective() {

}
function insertObjective() {

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
/*
Report functions: deleteReport, insertReport, updateReport 
*/

function insertReport($conn, $objectiveId, $reportDate, $reportObserved) {
    //Insert form data into database using prepared statement and bound parameters
    $stmt = $conn->prepare("INSERT INTO report (objective_id, report_date, report_observed) VALUES (?,?,?)");

    // prepare statement, bind parameters
    $stmt->bind_param("isi", $objectiveId, $reportDate, $reportObserved);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    return true;
}

?>