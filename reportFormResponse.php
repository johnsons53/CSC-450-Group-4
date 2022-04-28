<?php
/*
reportFormResponse.php - Code to process report changes to database
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 04/15/2022
      Revised: 04/17/2022 Modified use of SESSION data;
      cleanup of unnecessary testing code, added try/catch blocks
      04/27/2022 : Adjusted form data validation
*/
include_once realpath("initialization.php");
global $conn;

// Save New or Updated Report to database
if(isset($_POST["saveReport"])) { 
  // Sanitize input
  $reportDate = test_input($_POST["reportDate"]);
  $reportObserved = test_input(($_POST["reportObserved"]));
  
  if($_POST["reportId"] == "") {
    // Insert New report
    try {
      insertReport($conn, $_POST["objectiveId"], $reportDate, $reportObserved);
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }

  } else {
    // Update Report with selected reportId
    try {
      updateReport($conn, $_POST["objectiveId"], $reportDate, $reportObserved,
      $_POST["reportId"]);
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }
  }
   
    
}

// Delete Report from database
if(isset($_POST["deleteReport"])) {

  try {
    deleteReport($conn, $_POST["reportId"]);
  } catch (Exception $e) {
    echo "Message: " . $e->getMessage();

  }
}
?>