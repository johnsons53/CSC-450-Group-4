<?php
/*
reportForm.php - Provider Report Form
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 04/10/2022
      Revised: 04/17/2022 Modified use of SESSION data;
      cleanup of unnecessary testing code
*/
include_once realpath("initialization.php");

if(array_key_exists("objectiveId", $_POST)) {
    $objectiveId = $_POST['objectiveId'];
} else {
    $objectiveId = "";
}
if(array_key_exists("selectedReportId", $_POST)) {
    $reportId = $_POST['selectedReportId'];
} else {
    $reportId = "";
}
if(array_key_exists("selectedValue", $_POST)) {
    $reportObserved = $_POST['selectedValue'];
} else {
    $reportObserved = "";
}
if(array_key_exists("selectedDate", $_POST)) {
    $reportDate = $_POST['selectedDate'];
} else {
    $reportDate = "";
}

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
}


?>
    <form action="" method="post" class="providerForm">
        
        <!-- Hidden field with reportId-->
        <div>
            <input type="hidden" id="reportId" name="reportId" value="<?php echo $reportId; ?>">
        </div>
        <!-- Hidden field with objectiveId-->
        <div>
            <input type="hidden" id="objectiveId" name="objectiveId" value="<?php echo $objectiveId; ?>">
        </div>
                    
        <!-- Date field for reportDate 
                Set default value to current date with JavaScript-->

        <div class="flex-formContainer">
            <div class="formElement formLabel">
                <label for="reportDate">Report Date</label>
            </div>
            <div class="formElement">
                <input type="date"id="reportDate" name="reportDate" value="<?php echo $reportDate; ?>"> 
            </div>
            <div>
                <span class="error"><?php echo $report_date_err;?></span>       
            </div>
        </div>

        <!-- Number picker for reportObserved -->
        <div class="flex-formContainer">
            <div class="formElement formLabel">
            <label for="reportObserved">Observed</label>
            </div>
            <div class="formElement">
            <input type="number" id="reportObserved" name="reportObserved" value="<?php echo $reportObserved; ?>">
            </div>
            <div>
            <span class="error"><?php echo $report_observed_err;?></span> 
            </div>
        </div>

        <div class="flex-formContainer">
                                <!-- Button to Save report -->

            <div class="formButton">
            <input type="button" id="saveReport" class="save refresh reportSubmit" name="saveReport" value="Save Report">
            </div>
                                <!-- Button to Delete report -->

            <div class="formButton">
            <input type="button" id="deleteReport" class="delete refresh reportSubmit" name="deleteReport" value="Delete Report">
            </div>
                                <!-- Button to Cancel action -->    

            <div class="formButton">
            <input type="button" id="cancelReport" class="cancel reportSubmit" name="cancelReport" value="Cancel">
            </div>
        </div>
    </form>