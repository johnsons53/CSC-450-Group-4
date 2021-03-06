<?php
/*
objectiveFormResponse.php - Provider Objective Form Response code
Spring 100 CSC 450 Capstone, Group 4
Author: Lisa Ahnell
Date Written: 04/18/2022
Revised: 
04/27/2022 : Adjusted form data validation
*/

include_once realpath("initialization.php");
global $conn;

// Save New or Updated Objective to database
if(isset($_POST["saveObjective"])) { 
  // Sanitize input
  $objectiveLabel = test_input($_POST["objectiveLabel"]);
  $objectiveText = test_input($_POST["objectiveText"]); 
  if($_POST["objectiveId"] == "") {
    // Insert New objective
    try {
      insertObjective($conn, $_POST["goalId"], $objectiveLabel, $objectiveText, 
    $_POST["objectiveAttempts"], $_POST["objectiveTarget"], $_POST["objectiveStatus"]);
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }

  } else {
    // Update Objective with selected objectiveId
    try {
      updateObjective($conn, $_POST["objectiveId"], $_POST["goalId"], $objectiveLabel, $objectiveText, 
      $_POST["objectiveAttempts"], $_POST["objectiveTarget"], $_POST["objectiveStatus"]);
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }
  } 
    
}

// Delete Objective from database
if(isset($_POST["deleteObjective"])) {

  try {
    deleteObjective($conn, $_POST["objectiveId"]);
  } catch (Exception $e) {
    echo "Message: " . $e->getMessage();

  }
}
?>