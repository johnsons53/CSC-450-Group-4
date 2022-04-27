<?php
/*
goalForm.php - Provider Goal Form
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 04/19/2022
      Revised: 04/25/2022 : Removed testing code
      04/26/2022 : Added table to display form elements neatly
*/
include_once realpath("initialization.php");

?>

<!DOCTYPE html>

<html lang="en">
  <body>

  <?php
    //Test data in POST
    if (array_key_exists("studentId", $_POST)) {
        $studentId = $_POST["studentId"];
    } else {
    }
    if (array_key_exists("goalId", $_POST)) {
        $goalId = $_POST["goalId"];
    } else {
        $goalId = "";
    }
    if (array_key_exists("goalLabel", $_POST)) {
        $goalLabel = $_POST["goalLabel"];
    } else {
        $goalLabel = "";
    }
    if (array_key_exists("goalCategory", $_POST)) {
        $goalCategory = $_POST["goalCategory"];
    } else {
        $goalCategory = "";
    }
    if (array_key_exists("goalText", $_POST)) {
        $goalText = $_POST["goalText"];
    } else {
        $goalText = "";
    }
    if (array_key_exists("goalActive", $_POST)) {
        $goalActive = $_POST["goalActive"];
    } else {
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
        <form action="" method="post" class="providerForm">
            <!-- Hidden field with goalId-->
            <div>
                <input type="hidden" id="goalId<?php echo $studentId ?>" name="goalId" value="<?php echo $goalId;?>">
                
            </div>
            <!-- Hidden field with studentId-->
            <div>
                <input type="hidden" id="studentId" name="studentId" value="<?php echo $studentId;?>">
                
            </div>
            <!-- Text field for goalLabel-->
            <div class="flex-formContainer">
                <div class="formElement formLabel">
                <label for="goalLabel">Goal Label</label>
                </div>
                <div class="formElement">
                <input type="text" id="goalLabel<?php echo $studentId ?>" name="goalLabel" value="<?php echo $goalLabel;?>"> 
                </div>
                <div>
                <span class="error"><?php echo $goalLabelErr;?></span>
                </div>
            </div>

            <!-- Text field for goalCategory-->
            <div class="flex-formContainer">
                <div class="formElement formLabel">
                <label for="goalCategory--">Goal Category</label>
                </div>
                <div class="formElement">
                <input type="text" id="goalCategory<?php echo $studentId ?>" name="goalCategory" value="<?php echo $goalCategory;?>">
                </div>
                <div>
                <span class="error"><?php echo $goalCategoryErr;?></span>
                </div>
            </div>

            <!-- Text field for goalText-->
            <div class="flex-formContainer">
                <div class="formElement formLabel">
                <label for="goalText">Goal Description</label>
                </div>
                <div class="formElement">
                <textarea id="goalText<?php echo $studentId ?>" name="goalText" rows="6" cols="40"><?php echo $goalText; ?></textarea>
                </div>
                <div>
                <span class="error"><?php echo $goalTextErr;?></span>
                </div>
            </div>
        
            <!-- Radio buttons to set goalActive -->
            <div class="flex-formContainer">
                <div class="formElement formLabel">
                <label for="goalActive">Goal Status</label>
                </div>
                <div class="formElement">
                <label><input type="radio" name="goalActive" 
                        <?php 
                        if($goalActive === "") echo "checked";
                        if(isset($goalActive) && $goalActive == "0") echo "checked";
                        ?> 
                        value="0">Active</label>         
                </div>
                <div class="formElement">
                <label><input type="radio" name="goalActive" 
                        <?php if(isset($goalActive) && $goalActive == "1") echo "checked";?>
                        value="1">Complete</label>         
                </div>
                <div>
                <span class="error"><?php echo $goalActiveErr;?></span>
                </div>
            </div>


            <div class="flex-formContainer">
                <!--Button to Save goal-->
                <div class="formButton">
                <input type="button" id="saveGoal<?php echo $studentId ?>" class="save goalSubmit" name="saveGoal" value="Save Goal">
                </div>
                <!-- Button to Cancel Objective -->
                <div class="formButton">
                <input type="button" id="cancelGoal<?php echo $studentId ?>" class="cancel goalSubmit" name="cancelGoal" value="Cancel">
                </div>
            </div>

        </form>
    </div>
  </body>
</html>



