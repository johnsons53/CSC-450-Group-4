<?php
/*
objectiveForm.php - Provider Objective Form
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 04/18/2022
      Revised: 04/25/2022: Removed testing code
            04/26/2022 : Added table to display form elements neatly

*/
include_once realpath("initialization.php");

// Check POST values for form data
if (array_key_exists("objectiveId", $_POST)) {
    $objectiveId = $_POST["objectiveId"];
} else {
    $objectiveId = "";
}
if (array_key_exists("goalId", $_POST)) {
    $goalId = $_POST["goalId"];
} else {
}
if (array_key_exists("objectiveLabel", $_POST)) {
    $objectiveLabel = $_POST["objectiveLabel"];
} else {
    $objectiveLabel = "";
} 
if (array_key_exists("objectiveText", $_POST)) {
    $objectiveText = $_POST["objectiveText"];
} else {
    $objectiveText = "";
}
if (array_key_exists("objectiveAttempts", $_POST)) {
    $objectiveAttempts = $_POST["objectiveAttempts"];
} else {
    $objectiveAttempts = "";
}
if (array_key_exists("objectiveTarget", $_POST)) {
    $objectiveTarget = $_POST["objectiveTarget"];
} else {
    $objectiveTarget = "";
}
if (array_key_exists("objectiveStatus", $_POST)) {
    $objectiveStatus = $_POST["objectiveStatus"];
} else {
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

?>

    <div id="providerObjectiveForm">
    <form action="" method="post" class="providerForm">
        <!-- Hidden field with objectiveId: if accessed by "Edit Objective" button, send objectiveId value
        "New Objective" will leave this blank-->
        <div>
            <input type="hidden" id="objectiveId" name="objectiveId" value="<?php echo $objectiveId; ?>">
        </div>
        <!-- Hidden field with goalId-->
        <div>
            <input type="hidden" id="goalId" name="goalId" value="<?php echo $goalId; ?>">
        </div>

        <!-- Text field for objectiveLabel -->
        <div class="flex-formContainer">
            <div class="formElement formLabel">
            <label for="objectiveLabel">Objective Label</label>
            </div>
            <div class="formElement">
            <input type="text" id="objectiveLabel" name="objectiveLabel" value="<?php echo $objectiveLabel; ?>">
            </div>
            <div>
            <span class="error"><?php echo $objectiveLabelErr;?></span>
            </div>
        </div>

        <!-- Text field for objectiveText -->
        <div class="flex-formContainer">
            <div class="formElement formLabel">
            <label for="objectiveText">Objective Desctiption</label>
            </div>
            <div class="formElement">
            <textarea id="objectiveText" name="objectiveText" rows="6" cols="40"><?php echo $objectiveText; ?></textarea>
            </div>
            <div>
            <span class="error"><?php echo $objectiveTextErr;?></span>
            </div>
        </div>

        <!-- Number picker for objectiveAttempts -->
        <div class="flex-formContainer">
            <div class="formElement formLabel">
            <label for="objectiveAttempts">Objective Attempts</label>
            </div>
            <div class="formElement">
            <input type="number" id="objectiveAttempts" name="objectiveAttempts" value="<?php echo $objectiveAttempts; ?>"> 
            </div>
            <div>
            <span class="error"><?php echo $objectiveAttemptsErr;?></span>
            </div>
        </div>

        <!-- Number picker for objectiveTarget -->
        <div class="flex-formContainer">
            <div class="formElement formLabel">
            <label for="objectiveTarget">Objective Target</label>
            </div>
            <div class="formElement">
            <input type="number" id="objectiveTarget" name="objectiveTarget" value="<?php echo $objectiveTarget; ?>"> 
            </div>
            <div>
            <span class="error"><?php echo $objectiveTargetErr;?></span>
            </div>
        </div>

        <!-- Radio buttons to set objectiveStatus -->
        <div class="flex-formContainer">
            <div class="formElement formLabel">
            <label for="objectiveStatus">Objective Status</label>
            </div>
            <div class="formElement">
            <label><input type="radio" name="objectiveStatus" 
                <?php 
                if($objectiveStatus === "") echo "checked";
                if(isset($objectiveStatus) && $objectiveStatus == "0") echo "checked";
                ?> 
                value="0">Active</label>            
            </div>
            <div class="formElement">
            <label><input type="radio" name="objectiveStatus" 
                <?php if(isset($objectiveStatus) && $objectiveStatus == "1") echo "checked";?>
                value="1">Complete</label>           
            </div>
            <div>
            <span class="error"><?php echo $objectiveStatusErr;?></span>
            </div>
        </div>

        <div class="flex-formContainer">
            <div class="formButton">
            <input type="button" id="saveObjective" class="save refresh objectiveSubmit" name="saveObjective" value="Save Objective">
            </div>
            <div class="formButton">
            <input type="button" id="cancelObjective" class="cancel objectiveSubmit" name="cancelObjective" value="Cancel"> 
            </div>
        </div>


        </form>
    </div>
