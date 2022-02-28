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
// define variables from form and set to empty values
// This is not the final version, since some values will be sent when the page
// loads.
$goal_id_err =
$student_id_err =
$student_first_name_err =
$student_last_name_err =
$goal_label_err =
$goal_category_err =
$goal_description_err = "";


// Goal ID, Student ID, student first name and student last name should
// be sent from previous page
$goal_id = "0";
$student_id = "0";
$student_first_name = "First";
$student_last_name = "McLast";

// These values can be modified by this form
$goal_label = 
$goal_category = 
$goal_description = "";

// Has the form been submitted? If not, can display blank values, if so, 
// validate form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Test inputs to make sure there are values when form is submitted
    if (empty($_POST["goal_id"])) {
        $goal_id_err = "Goal ID is required";
    } else {
        $goal_id = test_input($_POST["goal_id"]);
    }
    
    if (empty($_POST["student_id"])) {
        $student_id_err = "Student ID is required";
    } else {
        $student_id = test_input($_POST["student_id"]);
    }
    
    if (empty($_POST["student_first_name"])) {
        $student_first_name_err = "Student First Name is required";
    } else {
        $student_first_name = test_input($_POST["student_first_name"]);
        // Validate name input
        if (!preg_match("/^[a-zA-Z-' ]*$/",$student_first_name)) {
            $student_first_name_err = "Only letters and white space allowed";
        }
    }
    
    if (empty($_POST["student_last_name"])) {
        $student_last_name_err = "Student Last Name is required";
    } else {
        $student_last_name = test_input($_POST["student_last_name"]);
        // Validate name input
        if (!preg_match("/^[a-zA-Z-' ]*$/",$student_last_name)) {
            $student_last_name_err = "Only letters and white space allowed";
        }
    }
    
    if (empty($_POST["goal_label"])) {
        $goal_label_err = "Goal Label is required";
    } else {
        $goal_label = test_input($_POST["goal_label"]);
    }
    
    if (empty($_POST["goal_category"])) {
        $goal_category_err = "Goal Category is required";
    } else {
        $goal_category = test_input($_POST["goal_category"]);
    }
    
    if (empty($_POST["goal_descrip[tion"])) {
        $goal_description_err = "Goal Description is required";
    } else {
        $goal_description = test_input($_POST["goal_description"]);
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" 
        method="post">
            
            <!-- Hidden field with goal_id-->
            <div>
                <input type="hidden" id="goal_id" name="goal_id" value="<?php echo $goal_id;?>">
                <span class="error"><?php echo $goal_id_err;?></span>
            </div>
            <!-- Hidden field with student_id-->
            <div>
                <input type="hidden" id="student_id" name="student_id" value="<?php echo $student_id;?>">
                <span class="error"><?php echo $student_id_err;?></span>
            </div>

            <!-- Disabled field to show student first name -->
            <!-- TODO: Combine student first name and last name into single field, populated with data from user table -->
            <div>
                <label for="student_first_name">Student First Name</label>
                <input type="text" id="student_first_name" name="student_first_name" value="<?php echo $student_first_name;?>" disabled>
                <span class="error"><?php echo $student_first_name_err;?></span>
            </div>

            <!-- Disabled field to show student last name -->
            <div>
                <label for="student_last_name">Student Last Name</label>
                <input type="text" id="student_last_name" name="student_last_name" value="<?php echo $student_last_name;?>" disabled>
                <span class="error"><?php echo $student_last_name_err;?></span>
            </div>
            <!-- Text field for goal_label-->
            <div>
                <label for="goal_label">Goal Label</label>
                <input type="text" id="goal_label" name="goal_label" value="<?php echo $goal_label;?>">
                <span class="error"><?php echo $goal_label_err;?></span>
            </div>
            <!-- Text field for goal_category-->
            <div>
                <label for="goal_category--">Goal Category</label>
                <input type="text" id="goal_category" name="goal_category" value="<?php echo $goal_category;?>">
                <span class="error"><?php echo $goal_category_err;?></span>
            </div>
            <!-- Text field for goal_description-->
            <div>
                <label for="goal_description">Goal Description</label>
                <textarea id="goal_description" name="goal_description" rows="6" cols="40"><?php echo $goal_description; ?></textarea>
                <span class="error"><?php echo $goal_description_err;?></span>
            </div>
            <!--Submit button to Save goal-->
            <!-- Go back to provider dashboard and reload on submit? -->
            <div>
                <input type="submit" class="submit" value="Save Goal">
            </div>


        </form>
    </div>

    <?php
    echo "<h2>Contents of _POST:</h2>";
    echo $_POST;
    echo "<br>";

    echo "<h2>Your Input:</h2>";
    echo $goal_id;
    echo "<br>";
    echo $student_id;
    echo "<br>";
    echo $student_first_name;
    echo "<br>";
    echo $student_last_name;
    echo "<br>";
    echo $goal_label;
    echo "<br>";
    echo $goal_category;
    echo "<br>";
    echo $goal_description;
    ?>

  </body>
</html>

