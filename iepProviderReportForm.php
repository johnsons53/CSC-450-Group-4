<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);


?>
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

      if (array_key_exists("objectiveId", $_POST)) {
          echo "objectiveId found in POST: " . $_POST["objectiveId"];
          echo " :-) <br />";
      } else {
        echo " Did not find objectiveId in POST :-( <br />";
      }
      if (array_key_exists("studentName", $_POST)) {
        echo "studentName found in POST: " . $_POST["studentName"];
        echo " :-) <br />";
    } else {
      echo " Did not find studentName in POST :-( <br />";
    }
    if (array_key_exists("objectiveLabel", $_POST)) {
        echo "objectiveLabel found in POST: " . $_POST["objectiveLabel"];
        echo " :-) <br />";
    } else {
      echo " Did not find objectiveLabel in POST :-( <br />";
    }
    if (array_key_exists("goalId", $_POST)) {
        echo "goalId found in POST: " . $_POST["goalId"];
        echo " :-) <br />";
    } else {
      echo " Did not find goalId in POST :-( <br />";
    }
    if (array_key_exists("goalLabel", $_POST)) {
        echo "goalLabel found in POST: " . $_POST["goalLabel"];
        echo " :-) <br />";
    } else {
      echo " Did not find goalLabel in POST :-( <br />";
    }

      // define variables

      // reportId will be generated when the report data is added to the database, and is probably unnecessary
      
      // objectiveId, studentName, goalLabel, objectiveLabel should be sent from the calling provider page
      $objectiveId = $_POST["objectiveId"];
      $studentName = $_POST["studentName"];
      $goalLabel = $_POST["goalLabel"];
      $objectiveLabel = $_POST["objectiveLabel"];

      // reportDate, reportObserved are set using this page
      $reportId = $reportDate = $reportObserved ="";

      
      

      // error variables for incomplete or unacceptable data
      $report_date_err =
      $report_observed_err = "";

      // test_input if the form has been submitted
      if($_SERVER["REQUEST_METHOD"] == "POST") {
          if (empty($_POST["reportDate"])) {
              $report_date_err = "Report Date is required";
          } else {
            $reportDate = test_input($_POST["reportDate"]);
          }

          if (empty($_POST["reportObserved"])) {
              $report_observed_err = "Report Observed is required";
          } else {
            $reportObserved = test_input($_POST["reportObserved"]);
          }
          $objectiveId = test_input($_POST["objectiveId"]);
          $studentName = test_input($_POST["studentName"]);
          $goalLabel = test_input($_POST["goalLabel"]);
          $objectiveLabel = test_input($_POST["objectiveLabel"]);
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
        <form action="<?php echo htmlspecialchars("iepDashboard.php");?>" method="post">
            
            <!-- Hidden field with reportId-->
            <div>
                <input type="hidden" id="reportId" name="reportId" value="<?php echo $reportId; ?>">
            </div>
            <!-- Hidden field with objectiveId-->
            <div>
                <input type="hidden" id="objectiveId" name="objectiveId" value="<?php echo $objectiveId; ?>">
            </div>
            
            

            <!-- Disabled field to show student name -->
            <div>
                <label for="studentName">Student Name</label>
                <input type="text" id="studentName" name="studentName" value="<?php echo $studentName; ?>" disabled>
            </div>
            <!-- Disabled field for goal label -->
            <div>
                <label for="goalLabel">Goal Label</label>
                <input type="text" id="goalLabel" name="goalLabel" value="<?php echo $goalLabel; ?>" disabled>
            </div>
            <!-- Disabled field for objectiveLabel -->
            <div>
                <label for="objectiveLabel">Objective Label</label>
                <input type="text" id="objectiveLabel" name="objectiveLabel" value="<?php echo $objectiveLabel; ?>" disabled>
            </div>
            
            <!-- Date field for reportDate 
                 Set default value to current date with JavaScript-->
            <div>
                <label for="reportDate">Report Date</label>
                <input type="date"id="reportDate" name="reportDate" value="<?php echo $reportDate; ?>"> 
                <span class="error">* <?php echo $report_date_err;?></span>       
            </div>

            <!-- Number picker for reportObserved -->
            <div>
                <label for="reportObserved">Observed</label>
                <input type="number" id="reportObserved" name="reportObserved" value="<?php echo $reportObserved; ?>">
                <span class="error">* <?php echo $report_observed_err;?></span> 
            </div>

            <!--Submit button to Save report -->
            <div>
                <input type="submit" class="submit" name="insertReport" value="Save Report">
            </div>

            <!-- These to remain on hold for now, since there is no way to choose a report to update or delete -->
            <!-- Submit button to Update report -->
            <div>
                <input type="submit" class="submit" name ="update" value="Update Report">
            </div>

            <!-- Submit button to Delete report -->
            <div>
                <input type="submit" class="submit" name="delete" value="Delete Report">
            </div>


        </form>
    </div>

    <?php
    // function to insertReport report, call if isset(POST[submit])


    // Call insertReport if form is submitted with insertReport button
    if(isset($_POST["insertReport"])) {
        echo "_POST['insertReport'] is set <br />";
        //insertReport();
    } else {
        echo "_POST['insertReport'] is NOT set <br />";
    }

    ?>

    <div id="navigation">
        <a href="iepHome.html" class="navigation">Return to Dashboard</a>
    </div>

  </body>
</html>
<?php $conn->close(); ?>