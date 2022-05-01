<?php
/*
reportMeter.php - Meter Displaying selected report progress
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 04/10/2022
      Revised: 
      04/17/2022: Cleanup of unnecessary testing code;
      04/30/2022: Fixed label error
*/

    $value = $_POST["value"];
    $max = $_POST["max"];
    $high = $_POST["high"];
    $low = $_POST["low"];
    echo "<p>";
    echo "<h4>Selected Report Progress: </h4>";
    echo "<meter aria-label=\"Report Meter\" id=\"reportMeter\" name=\"reportMeter\" min='0' max='" . $max . "' high='" . $high ."' low='" . $low . "' optimum='" . $max . "' value='" . $value . "'>" . $value . "</meter>";
    echo "</p>"; 

?>