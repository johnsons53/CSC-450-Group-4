<?php
/*
goalFormResponse.php - Provider Goal Form Response code
Spring 100 CSC 450 Capstone, Group 4
Author: Lisa Ahnell
Date Written: 04/19/2022
Revised: 
*/

include_once realpath("initialization.php");
global $conn;

// Save New or Updated Goal to database
if(isset($_POST["saveGoal"])) {   
  if($_POST["goalId"] == "") {
    // Insert New goal
    try {
      insertGoal($conn, $_POST["studentId"], $_POST["goalLabel"], $_POST["goalCategory"], 
      $_POST["goalText"], $_POST["goalActive"]);
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }

  } else {
    // Update Goal with selected goalId
    try {
      updateGoal($conn, $_POST["studentId"], $_POST["goalId"], $_POST["goalLabel"], 
      $_POST["goalCategory"], $_POST["goalText"], $_POST["goalActive"]);
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