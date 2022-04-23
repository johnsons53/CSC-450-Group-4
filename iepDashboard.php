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
Revised: 04/22/2022 Added handling for Admin user

*/
include_once realpath("initialization.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>IEP Portal: Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="iepDetailView.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
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

        // Handle detail view toggle with jQuery?
        $(document).on("click", ".detailViewButton", function() {
          // detail view button needs to contain the id of its associated detail view div
          var detailDivId = $(this).attr("data-detailDivId");
          //alert(detailDivId);
          // if element with detailDivId has display of block, set to none,
          // if it has display of none, set to block
          if ($("#" + detailDivId).attr("display") == "block") {
            $("#" + detailDivId).attr("display", "none");
          } else {
            $("#" + detailDivId).attr("display", "block");
          }
        });
        // Hide the detail views
        $(".detailViewButton").click();

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

        // Load Settings page for selected user when selected by admin
        $(document).on("change", "#accountSelect", function() {
          var selectedUserId = $(this).find(":selected").val();
          alert(selectedUserId);
          // Load admin content into accountContent div
          $("#accountContent").load("iepSettings.php", {
            "selectedUserId": selectedUserId
          });
        });

        // Open Goal Form on page to modify existing goal with button click
        $(document).on("click", ".modifyGoalFormButton", function() {
          var studentId = $(this).attr("data-studentId");
          var goalId = $(this).attr("data-goalId");
          var goalLabel = $(this).attr("data-goalLabel");
          var goalText = $(this).attr("data-goalText");
          var goalCategory = $(this).attr("data-goalCategory");
          var goalActive = $(this).attr("data-goalActive");

          $("#modifyGoalForm" + goalId).load("goalForm.php", {
            "studentId" : studentId,
            "goalId" : goalId,
            "goalLabel" : goalLabel,
            "goalCategory" : goalCategory,
            "goalText" : goalText,
            "goalActive" : goalActive
          });
        });

        // Open Goal Form on page to add new goal
        $(document).on("click", ".newGoalFormButton", function() {
          //alert("Goal form for student id: " + $(this).attr("data-studentId"));
          var studentId = $(this).attr("data-studentId");
          $("#newGoalForm" + studentId).load("goalForm.php", {
            "studentId" : studentId,
          });
        });

        // Save or Cancel Goal button
        $(document).on("click", ".goalSubmit", function() {
          // variables for form data
          var studentId = $("#studentId").val();
          var goalId = $("#goalId" + studentId).val();
          var goalLabel = $("#goalLabel" + studentId).val();
          var goalText = $("#goalText" + studentId).val();
          var goalCategory = $("#goalCategory" + studentId).val();
          var goalActive = $("input[name='goalActive']:checked").val();

          // Is this a new goal or a modified one?
          if (goalId == "") {
            // New Goal, load into newGoalForm div
            // if save, send data to goalFormResponse
            if ($(this).attr("name") == "saveGoal") {
              
              $("#newGoalForm"+ studentId).load("goalFormResponse.php", {
                "saveGoal" : $("#saveGoal" + studentId).val(),
                "studentId" : studentId,
                "goalId" : goalId,
                "goalLabel" : goalLabel,
                "goalCategory" : goalCategory,
                "goalText" : goalText,
                "goalActive" : goalActive
              }, function() {
                
                // Load mainContent with activeStudent data
                $(".tablinks.active").click();
                alert("Goal Saved");
            
              });

            }
            
            // if cancel, send nothing to goalFormResponse
            if ($(this).attr("name") == "cancelGoal") {
              $("#newGoalForm"+ studentId).load("goalFormResponse.php", {
                "cancelGoal" : $("#cancelGoal" + studentId).val(),
              }, function() {
                alert("Goal Cancelled");          
              });

            }

          } else {
            // modified goal, load into modifyGoalForm div
            // if save, send data to goalFormResponse
            if ($(this).attr("name") == "saveGoal") {
              $("#modifyGoalForm"+ goalId).load("goalFormResponse.php", {
                "saveGoal" : $("#saveGoal" + studentId).val(),
                "studentId" : studentId,
                "goalId" : goalId,
                "goalLabel" : goalLabel,
                "goalCategory" : goalCategory,
                "goalText" : goalText,
                "goalActive" : goalActive
              }, function() {
                alert("Goal Saved");
                // Load mainContent with activeStudent data
                $(".tablinks.active").click();
                
            
              });

            }
            
            // if cancel, send nothing to objectiveFormResponse
            if ($(this).attr("name") == "cancelGoal") {
              $("#modifyGoalForm"+ goalId).load("goalFormResponse.php", {
                "cancelGoal" : $("#cancelGoal" + studentId).val(),
              }, function() {
                alert("Goal Cancelled");          
              });
            }
          }
        });

        // Delete Goal button
        $(document).on("click", ".deleteGoalButton", function() {
          var goalId = $(this).attr("data-goalId");
            $("#modifyGoalForm"+ goalId).load("goalFormResponse.php", {
              "deleteGoal" : "deleteGoal",
              "goalId" : goalId,
            }, function() {
              alert("Goal Deleted");
              // Load mainContent with activeStudent data
              $(".tablinks.active").click();
          
            });  
        });

        // Open Objective Form on page to modify existing objective with button click
        $(document).on("click", ".modifyObjectiveFormButton", function() {
          //alert("modify objective");

          var objectiveId = $(this).attr("data-objectiveId");
          var goalId = $(this).attr("data-goalId");
          var objectiveLabel = $(this).attr("data-objectiveLabel");
          var objectiveText = $(this).attr("data-objectiveText");
          var objectiveAttempts = $(this).attr("data-objectiveAttempts");
          var objectiveTarget = $(this).attr("data-objectiveTarget");
          var objectiveStatus = $(this).attr("data-objectiveStatus");

          $("#modifyObjectiveForm" + objectiveId).load("objectiveForm.php", {
            "objectiveId" : objectiveId,
            "goalId" : goalId,
            "objectiveLabel" : objectiveLabel,
            "objectiveText" : objectiveText,
            "objectiveAttempts" : objectiveAttempts,
            "objectiveTarget" : objectiveTarget,
            "objectiveStatus" : objectiveStatus
          });
        });

        // Open Objective Form on page to add new Objective with button click
        $(document).on("click", ".newObjectiveFormButton", function() {
          var goalId = $(this).attr("data-goalId");
          $("#newObjectiveForm" + goalId).load("objectiveForm.php", {
            "goalId" : goalId,
          });
        });

        // Save or Cancel Objective
        $(document).on("click", ".objectiveSubmit", function() {
          // variables for form data
          var objectiveId = $("#objectiveId").val();
          var goalId = $("#goalId").val();
          var objectiveLabel = $("#objectiveLabel").val();
          var objectiveText = $("#objectiveText").val();
          var objectiveAttempts = $("#objectiveAttempts").val();
          var objectiveTarget = $("#objectiveTarget").val();
          var objectiveStatus = $("input[name='objectiveStatus']:checked").val();

          // Is this a new objective or a modified one?
          if (objectiveId == "") {
            // New Objective, load into newObjectiveForm div
            // if save, send data to objectiveFormResponse
            if ($(this).attr("name") == "saveObjective") {
              $("#newObjectiveForm"+ goalId).load("objectiveFormResponse.php", {
                "saveObjective" : $("#saveObjective").val(),
                "objectiveId" : objectiveId,
                "goalId" : goalId,
                "objectiveLabel" : objectiveLabel,
                "objectiveText" : objectiveText,
                "objectiveAttempts" : objectiveAttempts,
                "objectiveTarget" : objectiveTarget,
                "objectiveStatus" : objectiveStatus
              }, function() {
                alert("Objective Saved");
                // Load mainContent with activeStudent data
                $(".tablinks.active").click();
            
              });

            }
            
            // if cancel, send nothing to objectiveFormResponse
            if ($(this).attr("name") == "cancelObjective") {
              $("#newObjectiveForm"+ goalId).load("objectiveFormResponse.php", {
                "cancelObjective" : $("#cancelObjective").val(),
              }, function() {
                alert("Objective Cancelled");          
              });

            }

          } else {
            // modified objective, load into modifyObjectiveForm div
            // if save, send data to objectiveFormResponse
            if ($(this).attr("name") == "saveObjective") {
              $("#modifyObjectiveForm"+ objectiveId).load("objectiveFormResponse.php", {
                "saveObjective" : $("#saveObjective").val(),
                "objectiveId" : objectiveId,
                "goalId" : goalId,
                "objectiveLabel" : objectiveLabel,
                "objectiveText" : objectiveText,
                "objectiveAttempts" : objectiveAttempts,
                "objectiveTarget" : objectiveTarget,
                "objectiveStatus" : objectiveStatus
              }, function() {
                alert("Objective Saved");
                // Load mainContent with activeStudent data
                $(".tablinks.active").click();
            
              });

            }
            
            // if cancel, send nothing to objectiveFormResponse
            if ($(this).attr("name") == "cancelObjective") {
              $("#modifyObjectiveForm"+ objectiveId).load("objectiveFormResponse.php", {
                "cancelObjective" : $("#cancelObjective").val(),
              }, function() {
                alert("Objective Cancelled");          
              });
            }
          }
        });

        // Delete objective button
        $(document).on("click", ".deleteObjectiveButton", function() {
          var objectiveId = $(this).attr("data-objectiveId");
            $("#modifyObjectiveForm"+ objectiveId).load("objectiveFormResponse.php", {
              "deleteObjective" : "deleteObjective",
              "objectiveId" : objectiveId,
            }, function() {
              alert("Objective Deleted");
              // Load mainContent with activeStudent data
              $(".tablinks.active").click();
          
            });  
        });

        // Open Report Form on Page with button click
        $(document).on("click", ".reportFormButton", function() {
          // Collect data to add to report form
          var objectiveId = $(this).attr("data-objectiveid");
          if ($(this).attr("name") == "modifyReport") {
            // set variable values to selected
            var selectedValue = $("#accountSelect" + objectiveId).find(":selected").val();
            var selectedDate = $("#accountSelect" + objectiveId).find(":selected").attr("data-reportdate");
            var selectedReportId = $("#accountSelect" + objectiveId).find(":selected").attr("data-reportid");
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
    $accounts;
    $activeAccount;


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

    // Provider, Guardian and Student users will view student IEP data
    // Admin users will view and modify general user account data for any other user

    if (strcmp($currentUserType, "admin") != 0) {
      // For users other than admin, find students to display
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


    } else {
      // User is an admin

    }
    /*
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
  */
  ?>
    <!-- Page is encompassed in grid -->
    <div class="gridContainer">
      <header>
        <!-- Insert logo image here -->
        <h1>IEP Portal</h1>
        <div id="accountHolderInfo">
          <!-- Username, messages button, and account settings button here -->
          <h2>Welcome: <?php echo $currentUserName; ?></h2>
        </div>
        <div id="horizontalNav">

          <a class="hNavButton" href=""><h3>Messages</h3></a>
          <a class="hNavButton" href=""><h3>Settings</h3></a>
        </div>
      </header>

      <!-- Vertical navigation bar -->
      <div class="left" id="verticalNav">
        

        <?php
        // For Admin User, this section to contain select input with each available user account
        if (strcmp($currentUserType, "admin") === 0) {
          echo "<h3>Available Accounts</h3>";

          // function returning Lastname, Firstname and user_id of each user from db
          $accounts = getUserList($conn);
          //print_r($accounts);

          // Select input for each available account
          // Would refine further by enabling search, or selecting by school and grade

          if (isset($accounts) && count($accounts) > 0) {
            // select input for accounts  
            echo "<label for=\"accountSelect\">Select User Account</label>";
            echo "<select name=\"accountSelect\" class=\"accountSelect\" id=\"accountSelect\">";
              // Options for accountSelect
              foreach($accounts as $a => $a_value) {
                  echo "<option class=\"accountOption\" value=\"" . $a . "\">" . $a_value . "</option>";
              }
            echo "</select>"; // end of select
          } // end of if accounts set and has values  
        } else {
          // For other users, this section to contain tab links to available student data
          echo "<h3>Your Student Accounts</h3>";
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
            
          } // end of foreach students as value


        } // end of if user is of type admin

/*
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
  */       
      ?>

      </div>
      <!-- Account content area -->
      <div class="middle mainContent accountContent" id="accountContent"></div>

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