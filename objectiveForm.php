<?php
/*
objectiveForm.php - Provider Objective Form
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 04/18/2022
      Revised: 04/25/2022: Removed testing code
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
    <form action="" method="post">
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

         <!-- Button to Save Objective -->
        <div>
            <input type="button" id="saveObjective" class="save refresh objectiveSubmit" name="saveObjective" value="Save Objective">
        </div>

        <!-- Button to Cancel Objective -->
        <div>
            <input type="button" id="cancelObjective" class="cancel objectiveSubmit" name="cancelObjective" value="Cancel">
        </div>

        </form>
    </div>
