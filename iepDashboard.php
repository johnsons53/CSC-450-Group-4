<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- iepParentHome.php - IEP Parent Dashboard
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell, Sienna-Rae Johnson
      Date Written: 02/26/2022
      Revised: 02/28/2022 Added containers for expanded view. Began setting up PHP sections.
      04/05/2022 Implemented toggling between student tabs.

    -->
    <title>IEP Portal: Parent Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="iepDetailView.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script> document.getElementById("defaultOpen").click(); </script> -->
    <script>
      //Run when student tab link is clicked
      $(document).ready(function() {
          $(".tablinks").click(function() {
              $(".tabcontent").load("mainContent.php", {
                  activeStudentId: $(this).attr("data-studentId"),
                  activeStudentName: $(this).attr("data-studentName")
              });
          });
          //Identify the defaultOpen element
          document.getElementById("defaultOpen").click();
      });


      function openTab(evt, tabName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
          tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
          tablinks[i].className = tablinks[i].className.replace(" active", "");
          
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
      }
    </script>

    
  </head>

  <body>
    <!-- TODO Add PHP here to manage variables and queries to database -->
    <?php 
    // display errors for testing
    ini_set('display_errors', 1);
    error_reporting(E_ALL|E_STRICT);

    // connect to other files
    $filepath = realpath('login.php');
    $config = require($filepath);

    require_once realpath('User.php');
    require_once realpath('Admin.php');
    require_once realpath('Document.php');
    require_once realpath('Goal.php');
    require_once realpath('Guardian.php');
    require_once realpath('Provider.php');
    require_once realpath('Report.php');
    require_once realpath('Objective.php');
    require_once realpath('Student.php');
    require_once realpath('functions.php');


    
    $db_hostname = $config['DB_HOSTNAME'];
    $db_username = $config['DB_USERNAME'];
    $db_password = $config['DB_PASSWORD'];
    $db_database = $config['DB_DATABASE'];

    // Create connection
    $conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    test();

    $currentUser;
    // Select a guardian from the database for demonstration purposes
/*     $sql = "SELECT * 
            FROM user
            WHERE user_id='13'";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // show the data in each row
        while ($row = $result->fetch_assoc()) {
            // new Guardian object from row data
            $guardian = new Guardian($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                $row['user_city'], $row['user_district'], $row['user_type']);
            // add current user to $_SESSION array
            $currentUser = $guardian;
            $_SESSION['currentUser'] = serialize($guardian);
            
            echo $guardian->get_full_name() . " created as GUARDIAN <br />";
            echo "User for this SESSION: " . $_SESSION['currentUser']->get_full_name() . " <br />";

        } 
    } else {
        echo "0 results <br />";
    }
    $students = $_SESSION['currentUser']->get_guardian_students(); */

    // Select a Provider 
    $sql = "SELECT * 
    FROM user
    INNER JOIN provider USING (user_id)
    WHERE user_id='15'";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
    // show the data in each row
    while ($row = $result->fetch_assoc()) {
        // new Guardian object from row data
        $provider = new Provider($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
            $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
            $row['user_city'], $row['user_district'], $row['user_type'],
            $row['provider_id'], $row['provider_title']);
        // add current user to $_SESSION array
        $_SESSION['currentUser'] = serialize($provider);
        $currentUser = $provider;
        
        echo $provider->get_full_name() . " created as PROVIDER  LINE 144<br />";
        //echo "User for this SESSION: " . $_SESSION['currentUser']->get_full_name() . " <br />";

    } 
    } else {
    echo "0 results <br />";
    }
    if(array_key_exists('currentUser', $_SESSION)) {
      echo "SESSION contains currentUser value <br />";
    }

    // Save array of students on page and in SESSION
    $students = $currentUser->get_provider_students();
    $_SESSION["currentStudents"] = serialize($students);

    // Set default current student to first student in the list
    $_SESSION['currentStudent'] = serialize($students[0]);
    if(array_key_exists('currentStudent', $_SESSION)) {
      echo "SESSION contains currentStudent value <br />";
    }

    if(isset($_POST["insertReport"])) {
      if (insertReport($conn, $_POST["objectiveId"], $_POST["reportDate"], $_POST["reportObserved"])) {
        // Alert Report added successfully
        echo "New Report: ". $_POST["reportDate"] ." saved :-) <br />";
      } else {
        // Alert report not added
        echo "New Report: ". $_POST["reportDate"] ." NOT saved :-( <br />";;
      }
    }
    
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
        // CHANGE to match function call above
        updateObjective($conn, $_POST["objectiveId"], $_POST["goalId"], $_POST["objectiveLabel"], 
        $_POST["objectiveText"], $_POST["objectiveAttempts"], $_POST["objectiveTarget"], 
        $_POST["objectiveStatus"]);
      }
    } else {
      echo "_POST['saveObjective'] is NOT set <br />";
    }
  
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
          $studentId = $value->get_student_id();

          // Version from testing
          if ($studentCount == 0) {
            echo "<div class=\"tab\">";
            //echo "<a class='vNavButton, tablinks' href='' id='defaultOpen' onclick='openTab(event, \"" . $studentName . "\");' data-studentName=\"" . $studentName . "\" data-student_id='" . $studentId . "'><h3>" . $studentName . "</h3></a>";

            echo "<button class=\"tablinks vNavButton\" onclick=\"openTab(event, '" . $studentName . "')\" id=\"defaultOpen\" data-studentId=\"" . $studentId . "\" data-studentName=\"" . $studentName . "\">" . $studentName . "</button>";
            echo "</div>";
          } else {
            echo "<div class=\"tab\">";
            //echo "<a class='vNavButton, tablinks' href='' onclick='openTab(event, \"" . $studentName . "\");' data-studentName=\"" . $studentName . "\" data-student_id='" . $studentId . "'><h3>" . $studentName . "</h3></a>";

            echo "<button class=\"tablinks vNavButton\" data-studentId=\"" . $studentId . "\" data-studentName=\"" . $studentName . "\" onclick=\"openTab(event, '" . $studentName . "')\">" . $studentName . "</button>";
            echo "</div>";
        }
        

        $studentCount++;
          
      }
         
        ?>

      </div>

      <!-- Main content of page -->
      <?php

         foreach ($students as $value) {
          $current_student = $value;
          $current_student_name = $value->get_full_name();
          $current_student_id = $value->get_student_id();
          // create a mainContent Div for each Student
          // Only need to create an empty div here for each student with correct name, id and classes

          echo "<div class='middle mainContent tabcontent' id='" . $current_student_name . "' >";
 
          echo "</div>";  // end of div id='mainContent'
        } // end of foreach students as value 
      ?>

      
      
      <footer>
        <!-- Insert footer info here -->
      </footer>

    </div>
  </body>


</html>
<?php
$conn->close();
?>