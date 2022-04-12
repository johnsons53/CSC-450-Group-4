<?php
echo "<p>This content from deleteGoal.php</p>";
echo "<p>goalId to be deleted: " . $_POST["goalId"] . "</p>";
echo "<p>Confirm delete current goal?</p>";
echo "<input type=\"submit\" class=\"confirmDelete\" name=\"confirmDelete\" data-goalId=\"" . $goalId . "\"value=\"Confirm Delete Goal\">";
echo "<input type=\"submit\" class=\"cancelDeleteGoal\" name=\"cancelDeleteGoal\" data-goalId=\"" . $goalId . "\"value=\"Cancel\">";

?>