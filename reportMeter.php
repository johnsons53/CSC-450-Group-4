<?php
    echo "content from reportMeter.php <br />";
    echo $_POST["value"] . " value of value <br />";
    echo $_POST["max"] . " value of max <br />";
    echo $_POST["high"] . " value of high <br />";
    echo $_POST["low"] . " value of low <br />";

    $value = $_POST["value"];
    $max = $_POST["max"];
    $high = $_POST["high"];
    $low = $_POST["low"];
  
    echo "<p>";
    echo "<label for=\"reportMeter\">Report Meter:</label>";
    echo "<meter name=\"reportMeter\" min='0' max='" . $max . "' high='" . $high ."' low='" . $low . "' optimum='" . $max . "' value='" . $value . "'>" . $value . "</meter>";
    echo "</p>"; 

?>