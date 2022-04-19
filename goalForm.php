<?php
/*
goalForm.php - Provider Goal Form
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 04/19/2022
      Revised: 
*/
include_once realpath("initialization.php");

?>

<!DOCTYPE html>

<html lang="en">
  <body>

  <?php
    //Test data in POST
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
?> 
    <div id="providerGoalForm">
        <?php echo "<form action=\"" . htmlspecialchars("iepDashboard.php") . "\" method=\"post\">"; ?>
        <h4>Goal Detail Form</h4>
            <!-- Hidden field with goalId-->
            <div>
                <input type="hidden" id="goalId<?php echo $studentId ?>" name="goalId" value="<?php echo $goalId;?>">
                
            </div>
            <!-- Hidden field with studentId-->
            <div>
                <input type="hidden" id="studentId" name="studentId" value="<?php echo $studentId;?>">
                
            </div>
            <!-- Text field for goalLabel-->
            <div>
                <label for="goalLabel">Goal Label</label>
                <input type="text" id="goalLabel<?php echo $studentId ?>" name="goalLabel" value="<?php echo $goalLabel;?>">
                <span class="error">* <?php echo $goalLabelErr;?></span>
            </div>
            <!-- Text field for goalCategory-->
            <div>
                <label for="goalCategory--">Goal Category</label>
                <input type="text" id="goalCategory<?php echo $studentId ?>" name="goalCategory" value="<?php echo $goalCategory;?>">
                <span class="error">* <?php echo $goalCategoryErr;?></span>
            </div>
            <!-- Text field for goalText-->
            <div>
                <label for="goalText">Goal Description</label>
                <textarea id="goalText<?php echo $studentId ?>" name="goalText" rows="6" cols="40"><?php echo $goalText; ?></textarea>
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
                <input type="button" id="saveGoal<?php echo $studentId ?>" class="save goalSubmit" name="saveGoal" value="Save Goal">
            </div>
                    <!-- Button to Cancel Objective -->
            <div>
                <input type="button" id="cancelGoal<?php echo $studentId ?>" class="cancel goalSubmit" name="cancelGoal" value="Cancel">
            </div>



        </form>
    </div>
  </body>
</html>



