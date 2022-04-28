<?php
/*
goalForm.php - Provider Goal Form
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 04/19/2022
      Revised: 04/25/2022 : Removed testing code
      04/26/2022 : Added table to display form elements neatly
      04/27/2022 : Adjusted form data validation
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

            <div class="flex-formContainer">
            <div>
                <h4>Please fill in all fields</h4>       
            </div>
        </div>
            <!-- Text field for goalLabel-->
            <div class="flex-formContainer">
                <div class="formElement formLabel">
                <label for="goalLabel">Goal Label</label>
                </div>
                <div class="formElement">
                <input type="text" id="goalLabel<?php echo $studentId ?>" name="goalLabel" value="<?php echo $goalLabel;?>"> 
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
            </div>

            <!-- Text field for goalText-->
            <div class="flex-formContainer">
                <div class="formElement formLabel">
                <label for="goalText">Goal Description</label>
                </div>
                <div class="formElement">
                <textarea id="goalText<?php echo $studentId ?>" name="goalText" rows="6" cols="40"><?php echo $goalText; ?></textarea>
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



