<?php

/*
iepDashboard.php - IEP Parent/Student/Provider Dashboard
Spring 100 CSC 450 Capstone, Group 4
Author: Lisa Ahnell, Sienna-Rae Johnson
Date Written: 02/26/2022
Revised: 02/28/2022 Added containers for expanded view. Began setting up PHP sections.
Revised: 04/05/2022 Implemented toggling between student tabs.
Revised: 04/12/2022 Added report select
Revised: 04/13/2022 Modified provider forms and buttons to appear on dashboard page.
Revised: 04/17/2022 Adjustments to data flow from iepLogin to SESSION to dashboard to fix
issue updating data after changes to database; Cleanup of old code and testing code

*/
include_once realpath("initialization.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>IEP Portal: Parent Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="iepDetailView.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script> document.getElementById("defaultOpen").click(); </script> -->
    <script>
      //Run when student tab link is clicked

      $(document).ready(function() {

        $(".tablinks").click(function() {
          // Declare all variables
          //var i, tablinks;

          // Get all elements with class="tablinks" and remove the class "active"
          $('.tablinks').removeClass('active');
          // Add class "active" to the clicked tablink
          $(this).addClass("active");

          $(".tabcontent").load("mainContent.php", {
              activeStudentId: $(this).attr("data-studentId"),
              activeStudentName: $(this).attr("data-studentName")
          },
          function() {
            $(".reportSelect").each(function() {
              var objectiveId = $(this).attr("data-objectiveId");
              var selectedValue = $(this).find(":selected").val();
              var max = $(this).attr("data-max");
              var high = $(this).attr("data-high");
              var low = $(this).attr("data-low");

              $("#reportMeter" + objectiveId).load("reportMeter.php", {
                "value" : selectedValue,
                "max": max,
                "high": high,
                "low": low

              });
            });
          });
        });

        //Identify the defaultOpen element
        $("#defaultOpen").click();

        // Change Report meter display when different report selected
        $(document).on("change", ".reportSelect", function() {
          
          var selectedValue = $(this).find(":selected").val();
          var selectedObjective = $(this).attr("data-objectiveId");
          $("#reportMeter" + selectedObjective).load("reportMeter.php", {
            value: selectedValue,
            max: $(this).attr("data-max"),
            high: $(this).attr("data-high"),
            low: $(this).attr("data-low")
          });
        });

        // Open Report Form on Page with button click
        $(document).on("click", ".reportFormButton", function() {
          // Collect data to add to report form
          var objectiveId = $(this).attr("data-objectiveid");
          if ($(this).attr("name") == "modifyReport") {
            // set variable values to selected
            var selectedValue = $("#reportSelect" + objectiveId).find(":selected").val();
            var selectedDate = $("#reportSelect" + objectiveId).find(":selected").attr("data-reportdate");
            var selectedReportId = $("#reportSelect" + objectiveId).find(":selected").attr("data-reportid");
          } else {
            // values should be empty
            var selectedValue = "";
            var selectedDate = "";
            var selectedReportId = "";
          }

          $("#reportForm"+ objectiveId).load("reportForm.php", {
            "objectiveId" : objectiveId,
            "selectedValue" : selectedValue,
            "selectedDate" : selectedDate,
            "selectedReportId" : selectedReportId
          }, function() {

          });
        });

        // submit buttons from report form
        $(document).on("click", ".reportSubmit", function() {
          var objectiveId = $("#objectiveId").val();
          var reportObserved = $("#reportObserved").val();
          var reportDate = $("#reportDate").val();
          var reportId = $("#reportId").val();

          if ($(this).attr("name") == "saveReport") {
            $("#reportForm"+ objectiveId).load("reportFormResponse.php", {
            "saveReport" : $("#saveReport").val(),
            "objectiveId" : objectiveId,
            "reportObserved" : reportObserved,
            "reportDate" : reportDate,
            "reportId" : reportId
          }, function() {
            alert("Report Saved");
            // Load mainContent with activeStudent data
            $(".tablinks.active").click();
        
          });

          }

          if ($(this).attr("name") == "deleteReport") {
            // Load reportFormResponse with report data to delete
            $("#reportForm"+ objectiveId).load("reportFormResponse.php", {
            "deleteReport" : $("#deleteReport").val(),
            "reportId" : reportId
            }, function() {
              alert("Report Deleted " + $(".tablinks.active").length);
              // Load mainContent with activeStudent data
              $(".tablinks.active").click();
            });
          }

          if ($(this).attr("name") == "cancelReport") {
            // Load reportFormResponse with report cancelled message
            $("#reportForm"+ objectiveId).load("reportFormResponse.php", {
              "cancelReport" : $("#cancelReport").val(),
              "reportId" : reportId
            }, function() {

              alert("Report Cancelled");
            });
          }
        });
 
      });
    </script>

    
  </head>

  <body>
    <?php 


    // variables
    $currentUserId;
    $currentUserType;
    $students = [];
    $currentUser;
    $currentUserName;
    $activeStudent;


    // Create connection
    global $conn;
    
    // See if currentUserId and type exist in Session
    try {
      $currentUserId = $_SESSION["currentUserId"];
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }

    try {
      $currentUserType = $_SESSION["currentUserType"];
    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }


    // Initialize currentUser as new User of correct type
    // Pass $currentUserId, $currentUserType, $conn into createUser() function
    try {
      $currentUser = createUser($currentUserId, $currentUserType, $conn);

    } catch (Exception $e) {
      echo "Message: " . $e->getMessage();
    }

    $currentUserName = $currentUser->get_full_name();

    // Set students array depending on type of user
    if (strcmp($currentUserType, "provider") === 0) {
      $students = $currentUser->get_provider_students();

    } elseif (strcmp($currentUserType, "user") === 0) {

      $students = $currentUser->get_guardian_students();
     
    } elseif (strcmp($currentUserType, "student") === 0) {
      // Student User--only one value for students, same as current user
      $students[] = $currentUser;
    } else {
      echo "incompatible user type";
      echo "<br />";

    }

    // Default active student value:
    $activeStudent = $students[0];
    $activeStudentId = $activeStudent->get_student_id();
    $activeStudentName = $activeStudent->get_full_name();


    // Save activeStudentId to SESSION
    $_SESSION["activeStudentId"] = $activeStudentId;


    /*
    if(isset($_POST["saveGoal"])) {
      echo "_POST['saveGoal'] is set <br />";
      echo "POST studentId: " . $_POST["studentId"] . "<br />";
      echo "POST goalId: " . $_POST["goalId"] . "<br />";
      echo "POST goalLabel: " . $_POST["goalLabel"] . "<br />";
      echo "POST goalCategory: " . $_POST["goalCategory"] . "<br />";
      echo "POST goalText: " . $_POST["goalText"] . "<br />";
      echo "POST goalActive: " . $_POST["goalActive"] . "<br />";
      // Insert new goal or update existing goal?
      if($_POST["goalId"] == "") {
        // Insert goal
        if (insertGoal($conn, $_POST["studentId"], $_POST["goalLabel"], $_POST["goalCategory"], $_POST["goalText"], $_POST["goalActive"])) {
          // Alert Report added successfully
          echo "New Goal: ". $_POST["goalLabel"] ." saved :-) <br />";
        } else {
          // Alert report not added
          echo "New Goal: ". $_POST["goalLabel"] ." NOT saved :-( <br />";
        }
      } else {
        // Update goal
        if (updateGoal($conn, $_POST["studentId"], $_POST["goalLabel"], $_POST["goalCategory"], $_POST["goalText"], $_POST["goalActive"], $_POST["goalId"])) {
          // Alert Goal updated successfully
          echo "Existing Goal: ". $_POST["goalLabel"] ." updated :-) <br />";
        } else {
          // Alert Goal not added
          echo "Existing Goal: ". $_POST["goalLabel"] ." NOT updated :-( <br />";
        }

      }
    } else {
      echo "_POST['saveGoal'] is NOT set <br />";
    }
    

    if(isset($_POST["saveObjective"])) {
      echo "_POST['saveObjective'] is set <br />";
      echo "POST objectiveId: " . $_POST["objectiveId"] . "<br />";
      echo "POST goalId: " . $_POST["goalId"] . "<br />";
      echo "POST objectiveLabel: " . $_POST["objectiveLabel"] . "<br />";
      echo "POST objectiveText: " . $_POST["objectiveText"] . "<br />";
      echo "POST objectiveAttempts: " . $_POST["objectiveAttempts"] . "<br />";
      echo "POST objectiveTarget: " . $_POST["objectiveTarget"] . "<br />";
      echo "POST objectiveStatus: " . $_POST["objectiveStatus"] . "<br />";
      // Insert new objective or update existing objective?
      if($_POST["objectiveId"] == "") {
          // Insert objective
          if (insertObjective($conn, $_POST["goalId"], $_POST["objectiveLabel"], 
          $_POST["objectiveText"], $_POST["objectiveAttempts"], $_POST["objectiveTarget"], 
          $_POST["objectiveStatus"])) {
            // Alert Objective added successfully
            echo "New Objective: ". $_POST["objectiveLabel"] ." saved :-) <br />";
          } else {
            // Alert report not added
            echo "New Objective: ". $_POST["objectiveLabel"] ." NOT saved :-( <br />";
          }
      } else {
        // Update objective
        if (updateObjective($conn, $_POST["objectiveId"], $_POST["goalId"], $_POST["objectiveLabel"], 
        $_POST["objectiveText"], $_POST["objectiveAttempts"], $_POST["objectiveTarget"], 
        $_POST["objectiveStatus"])) {
          // Alert Objective updated successfully
          echo "Existing Objective: ". $_POST["objectiveLabel"] ." updated :-) <br />";
        } else {
          // Alert report not added
          echo "Existing Objective: ". $_POST["objectiveLabel"] ." NOT updated :-( <br />";
          }
        
        
      }
    } else {
      echo "_POST['saveObjective'] is NOT set <br />";
    }
    */
  
    ?>
    <!-- Page is encompassed in grid -->
    <div class="gridContainer">
      <header>
        <!-- Insert logo image here -->
        <h1>IEP Portal</h1>
        <div id="accountHolderInfo">
          <!-- Username, messages button, and account settings button here -->
        </div>
        <div id="horizontalNav">
          <!-- Links are inactive: no further pages have been built -->
          <a class="hNavButton" href=""><h3 class="button">Documents</h3></a>
          <a class="hNavButton" href=""><h3>Goals</h3></a>
          <a class="hNavButton" href=""><h3>Events</h3></a>
          <a class="hNavButton" href=""><h3>Messages</h3></a>
          <a class="hNavButton" href=""><h3>Information</h3></a>
          <a class="hNavButton" href=""><h3>Settings</h3></a>
        </div>
      </header>

      <!-- Vertical navigation bar -->
      <div class="left" id="verticalNav">
        <h3>Navigation</h3>

        <?php
        // Toggle between different students for this user
        $studentCount = 0;
        foreach ($students as $value) {
          $studentName = $value->get_full_name();
          // Needs to be the userId of the chosen student
          $studentId = $value->get_student_id();

          // Version from testing
          if ($studentCount == 0) {
            echo "<div class=\"tab\">";
            //echo "<a class='vNavButton, tablinks' href='' id='defaultOpen' onclick='openTab(event, \"" . $studentName . "\");' data-studentName=\"" . $studentName . "\" data-student_id='" . $studentId . "'><h3>" . $studentName . "</h3></a>";

            echo "<button class=\"tablinks vNavButton\" id=\"defaultOpen\" data-studentId=\"" . $studentId . "\" data-studentName=\"" . $studentName . "\">" . $studentName . "</button>";
            echo "</div>";
          } else {
            echo "<div class=\"tab\">";
            //echo "<a class='vNavButton, tablinks' href='' onclick='openTab(event, \"" . $studentName . "\");' data-studentName=\"" . $studentName . "\" data-student_id='" . $studentId . "'><h3>" . $studentName . "</h3></a>";

            echo "<button class=\"tablinks vNavButton\" data-studentId=\"" . $studentId . "\" data-studentName=\"" . $studentName . "\" >" . $studentName . "</button>";
            echo "</div>";
        }
        

        $studentCount++;
          
      }
         
        ?>

      </div>

      <!-- Main content of page -->
      <div class="middle mainContent tabcontent" id="mainContent"></div>
    
      <footer>
        <!-- Insert footer info here -->
      </footer>

    </div>
  </body>


</html>
<?php
$conn->close();
?>