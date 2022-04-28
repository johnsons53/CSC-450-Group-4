<?php
/*
reportForm.php - Provider Report Form
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 04/10/2022
      Revised: 04/17/2022 Modified use of SESSION data;
      cleanup of unnecessary testing code
      04/27/2022 : Adjusted form data validation
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
        <div class="flex-formContainer">
            <div>
                <h4>Please fill in all fields</h4>       
            </div>
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
        </div>

        <!-- Number picker for reportObserved -->
        <div class="flex-formContainer">
            <div class="formElement formLabel">
            <label for="reportObserved">Observed</label>
            </div>
            <div class="formElement">
            <input type="number" min="0" id="reportObserved" name="reportObserved" value="<?php echo $reportObserved; ?>">
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