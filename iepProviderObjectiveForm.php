<?php session_start(); ?>
<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- iepProviderObjectiveForm.html - IEP Portal Provider Add/Edit Objective form
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Created: 02/21/2022
      Revised: 02/28/2022 Added error messages if fields are left blank
    -->
    <title>IEP Portal: Objective Detail Form</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>

  <body>
      <?php
            $config = require realpath("login.php");
            require_once realpath('User.php');
            require_once realpath('Admin.php');
            require_once realpath('Document.php');
            require_once realpath('Goal.php');
            require_once realpath('Guardian.php');
            require_once realpath('Provider.php');
            require_once realpath('Report.php');
            require_once realpath('Objective.php');
            require_once realpath('Student.php');
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

        // Check SESSION access
        if (array_key_exists("activeStudent", $_SESSION)) {
            echo "activeStudent found in SESSION :-)<br />";
            $activeStudent = unserialize($_SESSION["activeStudent"]);
            echo "activeStudent name: " . $activeStudent->get_full_name();
            echo "<br />";
        } else {
            echo " Did not find activeStudent in SESSION :-( <br />";
        }
        // Check POST values
        if (array_key_exists("objectiveId", $_POST)) {
            echo "objectiveId found in POST: " . $_POST["objectiveId"];
            echo " :-) <br />";
            $objectiveId = $_POST["objectiveId"];
        } else {
            echo " Did not find objectiveId in POST :-( <br />";
            $objectiveId = "";
        }
        if (array_key_exists("goalId", $_POST)) {
            echo "goalId found in POST: " . $_POST["goalId"];
            echo " :-) <br />";
            $goalId = $_POST["goalId"];
        } else {
            echo " Did not find goalId in POST :-( <br />";
        }
        if (array_key_exists("studentName", $_POST)) {
            echo "studentName found in POST: " . $_POST["studentName"];
            echo " :-) <br />";
            $studentName = $_POST["studentName"];
        } else {
            echo " Did not find studentName in POST :-( <br />";
        }
        if (array_key_exists("goalLabel", $_POST)) {
            echo "goalLabel found in POST: " . $_POST["goalLabel"];
            echo " :-) <br />";
            $goalLabel = $_POST["goalLabel"];
        } else {
            echo " Did not find goalLabel in POST :-( <br />";
        }
        if (array_key_exists("objectiveLabel", $_POST)) {
            echo "objectiveLabel found in POST: " . $_POST["objectiveLabel"];
            echo " :-) <br />";
            $objectiveLabel = $_POST["objectiveLabel"];
        } else {
            echo " Did not find objectiveLabel in POST :-( <br />";
            $objectiveLabel = "";
        } 
        if (array_key_exists("objectiveText", $_POST)) {
            echo "objectiveText found in POST: " . $_POST["objectiveText"];
            echo " :-) <br />";
            $objectiveText = $_POST["objectiveText"];
        } else {
            echo " Did not find objectiveText in POST :-( <br />";
            $objectiveText = "";
        }
        if (array_key_exists("objectiveAttempts", $_POST)) {
            echo "objectiveAttempts found in POST: " . $_POST["objectiveAttempts"];
            echo " :-) <br />";
            $objectiveAttempts = $_POST["objectiveAttempts"];
        } else {
            echo " Did not find objectiveAttempts in POST :-( <br />";
            $objectiveAttempts = "";
        }
        if (array_key_exists("objectiveTarget", $_POST)) {
            echo "objectiveTarget found in POST: " . $_POST["objectiveTarget"];
            echo " :-) <br />";
            $objectiveTarget = $_POST["objectiveTarget"];
        } else {
            echo " Did not find objectiveTarget in POST :-( <br />";
            $objectiveTarget = "";
        }
        if (array_key_exists("objectiveStatus", $_POST)) {
            echo "objectiveStatus found in POST: " . $_POST["objectiveStatus"];
            echo " :-) <br />";
            $objectiveStatus = $_POST["objectiveStatus"];
        } else {
            echo " Did not find objectiveStatus in POST :-( <br />";
            $objectiveStatus = "";
        }

      // error variables for incomplete or unacceptable data entry
      $objectiveLabelErr =
      $objectiveTextErr =
      $objectiveAttemptsErr =
      $objectiveTargetErr =
      $objectiveStatusErr = "";

      if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST["objectiveLabel"])) {
            $objectiveLabelErr = "Objective Label is required";
        } else {
            $objectiveLabel = test_input($_POST["objectiveLabel"]);
        }

        if (empty($_POST["objectiveText"])) {
            $objectiveTextErr = "Objective Description is required";
        } else {
            $objectiveText = test_input($_POST["objectiveText"]);
        }

        if (empty($_POST["objectiveAttempts"])) {
            $objectiveAttemptsErr = "Objective Attempts is required";
        } else {
            $objectiveAttempts = test_input($_POST["objectiveAttempts"]);
        }

        if (empty($_POST["objectiveTarget"])) {
            $objectiveTargetErr = "Objective Target is required";
        } else {
            $objectiveTarget = test_input($_POST["objectiveTarget"]);
        }

        if (empty($_POST["objectiveStatus"])) {
            $objectiveStatusErr = "Objective Status is required";
        } else {
            $objectiveStatus = test_input($_POST["objectiveStatus"]);
        }          
      }

      function test_input($data) {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
      }
      ?>
    <header>
      <!-- Insert logo image here -->
      <h1>Objective Detail Form</h1>
    </header>
    <div id="providerObjectiveForm">
    <?php echo "<form action=\"" . htmlspecialchars("iepDashboard.php") . "\" method=\"post\">"; ?>
        <!-- <form action="<?php //echo htmlspecialchars("iepDashboard.php");?>" method="post"> -->
            
            <!-- Hidden field with objectiveId: if accessed by "Edit Objective" button, send objectiveId value
            "New Objective" will leave this blank-->
            <div>
                <input type="hidden" id="objectiveId" name="objectiveId" value="<?php echo $objectiveId; ?>">
            </div>
            <!-- Hidden field with goalId-->
            <div>
                <input type="hidden" id="goalId" name="goalId" value="<?php echo $goalId; ?>">
            </div>
            

            <!-- Disabled field to show student name: populate this with student_id from goal entry-->
            <div>
                <label for="student_name">Student Name</label>
                <input type="text" id="student_name" name="student_name" value="<?php echo $studentName; ?>" disabled>
            </div>
            <!-- Disabled field for goal label -->
            <div>
                <label for="goal_label">Goal Label</label>
                <input type="text" id="goal_label" name="goal_label" value="<?php echo $goalLabel; ?>" disabled>
            </div>

            <!-- Text field for objectiveLabel -->
            <div>
                <label for="objectiveLabel">Objective Label</label>
                <input type="text" id="objectiveLabel" name="objectiveLabel" value="<?php echo $objectiveLabel; ?>">
                <span class="error">* <?php echo $objectiveLabelErr;?></span>
            </div>
            <!-- Text field for objectiveText -->
            <div>
                <label for="objectiveText">Objective Desctiption</label>
                <textarea id="objectiveText" name="objectiveText" rows="6" cols="40"><?php echo $objectiveText; ?></textarea>
                <span class="error">* <?php echo $objectiveTextErr;?></span>
            </div>

            <!-- Number picker for objectiveAttempts -->
            <div>
                <label for="objectiveAttempts">Objective Attempts</label>
                <input type="number" id="objectiveAttempts" name="objectiveAttempts" value="<?php echo $objectiveAttempts; ?>">
                <span class="error">* <?php echo $objectiveAttemptsErr;?></span>
            </div>

            <!-- Number picker for objectiveTarget -->
            <div>
                <label for="objectiveTarget">Objective Target</label>
                <input type="number" id="objectiveTarget" name="objectiveTarget" value="<?php echo $objectiveTarget; ?>">
                <span class="error">* <?php echo $objectiveTargetErr;?></span>
            </div>

            <!-- Radio buttons to set objectiveStatus -->
            <div>
                <label for="objectiveStatus">Objective Status</label>
                <label><input type="radio" name="objectiveStatus" 
                <?php 
                if($objectiveStatus === "") echo "checked";
                if(isset($objectiveStatus) && $objectiveStatus == "0") echo "checked";
                ?> 
                value="0">Active</label>
                <label><input type="radio" name="objectiveStatus" 
                <?php if(isset($objectiveStatus) && $objectiveStatus == "1") echo "checked";?>
                value="1">Complete</label>
                <span class="error">* <?php echo $objectiveStatusErr;?></span>
            </div>
            <!--Submit button to Update Objective-->
            <div>
                <input type="submit" class="submit" name="saveObjective" value="Save Objective">
            </div>


        </form>
    </div>

    <?php 
    echo "<br>";
    echo "<h3>Your Input: </h3>";
    echo "<br>";
    echo "objectiveId: ".$objectiveId;
    echo "<br>";
    echo "goalId: ".$goalId;
    echo "<br>";
    echo "objectiveLabel: ".$objectiveLabel;
    echo "<br>";
    echo "objectiveText: ".$objectiveText;
    echo "<br>";
    echo "objectiveAttempts: ".$objectiveAttempts;
    echo "<br>";
    echo "objectiveTarget: ".$objectiveTarget;
    echo "<br>";
    echo "objectiveStatus: ".$objectiveStatus;
    ?>

    <div id="navigation">
        <a href="iepHome.html" class="navigation">Return to Dashboard</a>
    </div>

  </body>
</html>
<?php $conn->close(); ?>