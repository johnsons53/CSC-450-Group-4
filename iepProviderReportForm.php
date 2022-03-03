<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- iepProviderReportForm.html - IEP Portal Provider Add Report form
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Created: 02/21/2022
      Revised: 02/28/22 : Convert to PHP, add basic form handling
    -->
    <title>IEP Portal: Create Objective Report</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>

  <body>
      <?php
      // define variables

      // report_id will be generated when the report data is added to the database, and is probably unnecessary
      
      // objective_id, student_name, goal_label, objective_label should be sent from the calling provider page

      // report_date, report_observed are set using this page
      $report_id = $objective_id = $student_name = $goal_label = 
      $objective_label = $report_date = $report_observed = "";

      // error variables for incomplete or unacceptable data
      $report_date_err =
      $report_observed_err = "";

      // test_input if the form has been submitted
      if($_SERVER["REQUEST_METHOD"] == "POST") {
          if (empty($_POST["report_date"])) {
              $report_date_err = "Report Date is required";
          } else {
            $report_date = test_input($_POST["report_date"]);
          }

          if (empty($_POST["report_observed"])) {
              $report_observed_err = "Report Observed is required";
          } else {
            $report_observed = test_input($_POST["report_observed"]);
          }
      }

      // test data function for this page
      function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

      ?>
    <header>
      <!-- Insert logo image here -->
      <h1>Objective Report</h1>
    </header>
    <div id="providerReportForm">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            
            <!-- Hidden field with report_id-->
            <div>
                <input type="hidden" id="report_id" name="report_id" value="<?php echo $report_id; ?>">
            </div>
            <!-- Hidden field with objective_id-->
            <div>
                <input type="hidden" id="objective_id" name="objective_id" value="<?php echo $objective_id; ?>">
            </div>
            
            

            <!-- Disabled field to show student name -->
            <div>
                <label for="student_name">Student Name</label>
                <input type="text" id="student_name" name="student_name" value="<?php echo $student_name; ?>" disabled>
            </div>
            <!-- Disabled field for goal label -->
            <div>
                <label for="goal_label">Goal Label</label>
                <input type="text" id="goal_label" name="goal_label" value="<?php echo $goal_label; ?>" disabled>
            </div>
            <!-- Disabled field for objective_label -->
            <div>
                <label for="objective_label">Objective Label</label>
                <input type="text" id="objective_label" name="objective_label" value="<?php echo $objective_label; ?>" disabled>
            </div>
            
            <!-- Date field for report_date 
                 Set default value to current date with JavaScript-->
            <div>
                <label for="report_date">Report Date</label>
                <input type="date"id="report_date" name="report_date" value="<?php echo $report_date; ?>"> 
                <span class="error">* <?php echo $report_date_err;?></span>       
            </div>

            <!-- Number picker for report_observed -->
            <div>
                <label for="report_observed">Observed</label>
                <input type="number" id="report_observed" name="report_observed" value="<?php echo $report_observed; ?>">
                <span class="error">* <?php echo $report_observed_err;?></span> 
            </div>

            <!--Submit button to Save report -->
            <div>
                <input type="submit" class="submit" value="Save Report">
            </div>

            <!-- These to remain on hold for now, since there is no way to choose a report to update or delete -->
            <!-- Submit button to Update report -->
            <div>
                <input type="submit" class="submit" value="Update Report">
            </div>

            <!-- Submit button to Delete report -->
            <div>
                <input type="submit" class="submit" value="Delete Report">
            </div>


        </form>
    </div>

    <?php

    // handling for submit button clicks
        echo "<br>";
        echo "<h2>Your Input:</h2>";
        echo "<br>";
        echo "report_id: ".$report_id;
        echo "<br>";
        echo "objective_id: ".$objective_id;
        echo "<br>";
        echo "student_name: ".$student_name;
        echo "<br>";
        echo "goal_label: ".$goal_label;
        echo "<br>";
        echo "objective_label: ".$objective_label;
        echo "<br>";
        echo "report_date: ".$report_date;
        echo "<br>";
        echo "report_observed: ".$report_observed;
    ?>

    <div id="navigation">
        <a href="iepHome.html" class="navigation">Return to Dashboard</a>
    </div>

  </body>
</html>