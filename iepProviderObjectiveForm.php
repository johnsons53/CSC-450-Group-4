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
      // define variables and set them to empty for now

      // if creating new objective
      //  objective_id is blank
      // goal_id, student_name come from calling page
      // all other fields blank


      // if modifying existing objective:
        // objective_id, goal_id, student_name from calling page
        // objective_label, objective_description, objective_attempts, objective_target, objective_status 
        // populated with database query using objective_id
      $objective_id = $goal_id = $objective_label = $objective_description = 
      $objective_attempts = $objective_target = $objective_status = "";

      // error variables for incomplete or unacceptable data entry
      $objective_label_err =
      $objective_description_err =
      $objective_attempts_err =
      $objective_target_err =
      $objective_status_err = "";

      if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty($_POST["objective_label"])) {
            $objective_label_err = "Objective Label is required";
        } else {
            $objective_label = test_input($_POST["objective_label"]);
        }

        if (empty($_POST["objective_description"])) {
            $objective_description_err = "Objective Description is required";
        } else {
            $objective_description = test_input($_POST["objective_description"]);
        }

        if (empty($_POST["objective_attempts"])) {
            $objective_attempts_err = "Objective Attempts is required";
        } else {
            $objective_attempts = test_input($_POST["objective_attempts"]);
        }

        if (empty($_POST["objective_target"])) {
            $objective_target_err = "Objective Target is required";
        } else {
            $objective_target = test_input($_POST["objective_target"]);
        }

        if (empty($_POST["objective_status"])) {
            $objective_status_err = "Objective Status is required";
        } else {
            $objective_status = test_input($_POST["objective_status"]);
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            
            <!-- Hidden field with objective_id: if accessed by "Edit Objective" button, send objective_id value
            "New Objective" will leave this blank-->
            <div>
                <input type="hidden" id="objective_id" name="objective_id" value="0">
            </div>
            <!-- Hidden field with goal_id-->
            <div>
                <input type="hidden" id="goal_id" name="goal_id" value="0">
            </div>
            

            <!-- Disabled field to show student name: populate this with student_id from goal entry-->
            <div>
                <label for="student_name">Student Name</label>
                <input type="text" id="student_name" name="student_name" value="First McLast" disabled>
            </div>
            <!-- Disabled field for goal label -->
            <div>
                <label for="goal_label">Goal Label</label>
                <input type="text" id="goal_label" name="goal_label" value="Goal Label" disabled>
            </div>

            <!-- Text field for objective_label -->
            <div>
                <label for="objective_label">Objective Label</label>
                <input type="text" id="objective_label" name="objective_label" value="<?php echo $objective_label; ?>">
                <span class="error">* <?php echo $objective_label_err;?></span>
            </div>
            <!-- Text field for objective_description -->
            <div>
                <label for="objective_description">Objective Desctiption</label>
                <textarea id="objective_description" name="objective_description" rows="6" cols="40"><?php echo $objective_description; ?></textarea>
                <span class="error">* <?php echo $objective_description_err;?></span>
            </div>

            <!-- Number picker for objective_attempts -->
            <div>
                <label for="objective_attempts">Objective Attempts</label>
                <input type="number" id="objective_attempts" name="objective_attempts" value="<?php echo $objective_attempts; ?>">
                <span class="error">* <?php echo $objective_attempts_err;?></span>
            </div>

            <!-- Number picker for objective_target -->
            <div>
                <label for="objective_target">Objective Target</label>
                <input type="number" id="objective_target" name="objective_target" value="<?php echo $objective_target; ?>">
                <span class="error">* <?php echo $objective_target_err;?></span>
            </div>

            <!-- Radio buttons to set objective_status -->
            <div>
                <label for="objective_status">Objective Status</label>
                <label><input type="radio" name="objective_status" 
                <?php if(isset($objective_status) && $objective_status == "0") echo "checked";?> 
                value="0">Active</label>
                <label><input type="radio" name="objective_status" 
                <?php if(isset($objective_status) && $objective_status == "1") echo "checked";?>
                value="1">Complete</label>
                <span class="error">* <?php echo $objective_status_err;?></span>
            </div>
            <!--Submit button to Save goal-->
            <div>
                <input type="submit" class="submit" value="Save Objective">
            </div>


        </form>
    </div>

    <?php 
    echo "<br>";
    echo "<h3>Your Input: </h3>";
    echo "<br>";
    echo "objective_id: ".$objective_id;
    echo "<br>";
    echo "goal_id: ".$goal_id;
    echo "<br>";
    echo "objective_label: ".$objective_label;
    echo "<br>";
    echo "objective_description: ".$objective_description;
    echo "<br>";
    echo "objective_attempts: ".$objective_attempts;
    echo "<br>";
    echo "objective_target: ".$objective_target;
    echo "<br>";
    echo "objective_status: ".$objective_status;
    ?>

    <div id="navigation">
        <a href="iepHome.html" class="navigation">Return to Dashboard</a>
    </div>

  </body>
</html>