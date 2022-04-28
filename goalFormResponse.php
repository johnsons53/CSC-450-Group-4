<?php
/*
goalFormResponse.php - Provider Goal Form Response code
Spring 100 CSC 450 Capstone, Group 4
Author: Lisa Ahnell
Date Written: 04/19/2022
Revised: 
04/27/2022 : Adjusted form data validation
*/

include_once realpath("initialization.php");
global $conn;

// Save New or Updated Goal to database
if(isset($_POST["saveGoal"])) {  
  // Sanitize input
  $goalLabel = test_input($_POST["goalLabel"]); 
  $goalCategory = test_input($_POST["goalCategory"]); 
  $goalText = test_input($_POST["goalText"]); 

  if($_POST["goalId"] == "") {
    // Insert New goal
    try {
      insertGoal($conn, $_POST["studentId"], $goalLabel, $goalCategory, 
      $goalText, $_POST["goalActive"]);
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }

  } else {
    // Update Goal with selected goalId
    try {
      updateGoal($conn, $_POST["studentId"], $goalLabel, 
      $goalCategory, $goalText, $_POST["goalActive"], $_POST["goalId"]);
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }
  } 
    
}

// Delete Goal from database
if(isset($_POST["deleteGoal"])) {

  try {
    deleteGoal($conn, $_POST["goalId"]);
  } catch (Exception $e) {
    echo "Message: " . $e->getMessage();

  }
}
?>