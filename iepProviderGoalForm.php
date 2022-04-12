<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);
require_once realpath('User.php');
require_once realpath('Admin.php');
require_once realpath('Document.php');
require_once realpath('Goal.php');
require_once realpath('Guardian.php');
require_once realpath('Provider.php');
require_once realpath('Report.php');
require_once realpath('Objective.php');
require_once realpath('Student.php');
?>
<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- iepProviderGoalForm.html - IEP Portal Provider Add/Edit Goal form
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Created: 02/18/2022
      Revised: 02/21/22 Form fields created
    -->
    <title>IEP Portal: Goal Detail Form</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>

  <body>

  <?php
    //Test data in POST
    if (array_key_exists("studentName", $_POST)) {
        echo "studentName found in POST: " . $_POST["studentName"];
        echo " :-) <br />";
        $studentName = $_POST["studentName"];
    } else {
        echo " Did not find studentName in POST :-( <br />";
    }
    if (array_key_exists("studentId", $_POST)) {
        echo "studentId found in POST: " . $_POST["studentId"];
        echo " :-) <br />";
        $studentId = $_POST["studentId"];
    } else {
        echo " Did not find studentId in POST :-( <br />";
    }
    if (array_key_exists("goalId", $_POST)) {
        echo "goalId found in POST: " . $_POST["goalId"];
        echo " :-) <br />";
        $goalId = $_POST["goalId"];
    } else {
        echo " Did not find goalId in POST :-( <br />";
        $goalId = "";
    }
    if (array_key_exists("goalLabel", $_POST)) {
        echo "goalLabel found in POST: " . $_POST["goalLabel"];
        echo " :-) <br />";
        $goalLabel = $_POST["goalLabel"];
    } else {
        echo " Did not find goalLabel in POST :-( <br />";
        $goalLabel = "";
    }
    if (array_key_exists("goalCategory", $_POST)) {
        echo "goalCategory found in POST: " . $_POST["goalCategory"];
        echo " :-) <br />";
        $goalCategory = $_POST["goalCategory"];
    } else {
        echo " Did not find goalCategory in POST :-( <br />";
        $goalCategory = "";
    }
    if (array_key_exists("goalText", $_POST)) {
        echo "goalText found in POST: " . $_POST["goalText"];
        echo " :-) <br />";
        $goalText = $_POST["goalText"];
    } else {
        echo " Did not find goalText in POST :-( <br />";
        $goalText = "";
    }
    if (array_key_exists("goalActive", $_POST)) {
        echo "goalActive found in POST: " . $_POST["goalActive"];
        echo " :-) <br />";
        $goalActive = $_POST["goalActive"];
    } else {
        echo " Did not find goalActive in POST :-( <br />";
        $goalActive = "";
    }

// define variables from form and set to empty values
// This is not the final version, since some values will be sent when the page
// loads.

$goalLabelErr =
$goalCategoryErr =
$goalTextErr = 
$goalActiveErr = "";




// Has the form been submitted? If not, can display blank values, if so, 
// validate form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Test inputs to make sure there are values when form is submitted

    
    // These are the fields that are modified by this form. They aren't mandatory in the database,
    // but they are required here.
    if (empty($_POST["goalLabel"])) {
        $goalLabelErr = "Goal Label is required";
    } else {
        $goalLabel = test_input($_POST["goalLabel"]);
    }
    
    if (empty($_POST["goalCategory"])) {
        $goalCategoryErr = "Goal Category is required";
    } else {
        $goalCategory = test_input($_POST["goalCategory"]);
    }
    
    if (empty($_POST["goalText"])) {
        $goalTextErr = "Goal Description is required";
    } else {
        $goalText = test_input($_POST["goalText"]);
    }
    if (empty($_POST["goalActive"])) {
        $goalActiveErr = "Goal Status is required";
    } else {
        $goalActive = test_input($_POST["goalActive"]);
    }

    
}

/*
test_input clears the data collected from the form of extra spaces, slashes
and converts special characters to HTML entities. 
*/
function test_input($data) {
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// close of php
?> 

    <header>
      <!-- Insert logo image here -->
      <h1>Goal Detail Form</h1>
    </header>
    <div id="providerGoalForm">
        <?php echo "<form action=\"" . htmlspecialchars("iepDashboard.php") . "\" method=\"post\">"; ?>
            
            <!-- Hidden field with goalId-->
            <div>
                <input type="hidden" id="goalId" name="goalId" value="<?php echo $goalId;?>">
                
            </div>
            <!-- Hidden field with studentId-->
            <div>
                <input type="hidden" id="studentId" name="studentId" value="<?php echo $studentId;?>">
                
            </div>

            <!-- Disabled field to show student first name -->
            <!-- TODO: Combine student first name and last name into single field, populated with data from user table -->
            <div>
                <label for="studentName">Student Name</label>
                <input type="text" id="studentName" name="studentName" value="<?php echo $studentName;?>" disabled>
                
            </div>
            <!-- Text field for goalLabel-->
            <div>
                <label for="goalLabel">Goal Label</label>
                <input type="text" id="goalLabel" name="goalLabel" value="<?php echo $goalLabel;?>">
                <span class="error">* <?php echo $goalLabelErr;?></span>
            </div>
            <!-- Text field for goalCategory-->
            <div>
                <label for="goalCategory--">Goal Category</label>
                <input type="text" id="goalCategory" name="goalCategory" value="<?php echo $goalCategory;?>">
                <span class="error">* <?php echo $goalCategoryErr;?></span>
            </div>
            <!-- Text field for goalText-->
            <div>
                <label for="goalText">Goal Description</label>
                <textarea id="goalText" name="goalText" rows="6" cols="40"><?php echo $goalText; ?></textarea>
                <span class="error">* <?php echo $goalTextErr;?></span>
            </div>
            <!-- Radio buttons to set goalActive -->
            <div>
                <label for="goalActive">Goal Status</label>
                <label><input type="radio" name="goalActive" 
                <?php 
                if($goalActive === "") echo "checked";
                if(isset($goalActive) && $goalActive == "0") echo "checked";
                ?> 
                value="0">Active</label>
                <label><input type="radio" name="goalActive" 
                <?php if(isset($goalActive) && $goalActive == "1") echo "checked";?>
                value="1">Complete</label>
                <span class="error">* <?php echo $goalActiveErr;?></span>
            </div>

            <!--Submit button to Save goal-->
            <!-- Go back to provider dashboard and reload on submit? -->
            <div>
                <input type="submit" class="submit" name="saveGoal" value="Save Goal">
            </div>


        </form>
    </div>

    <?php
    
    $currentUser = unserialize($_SESSION['currentUser']);
    echo "Current User from SESSION: " . $currentUser->get_full_name();
    echo "<br>";
    $currentStudent = unserialize($_SESSION['currentStudent']);
    echo "Current User from SESSION: " . $currentStudent->get_full_name();

    echo "<br>";

    echo "<h2>Your Input:</h2>";
    echo $goalId;
    echo "<br>";
    echo $studentId;
    echo "<br>";
    echo $studentName;
    echo "<br>";
    echo $goalLabel;
    echo "<br>";
    echo $goalCategory;
    echo "<br>";
    echo $goalText;
    ?>

  </body>
</html>

