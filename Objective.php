<?php
/*  Objective.php - Objective class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/23/2022
    Revised: 
    03/29/2022: Removed alert statements used for testing
    04/15/2022: Streamlined database connection code 
*/


class Objective {
    // change these to public
    public $objective_id;
    public $objective_label;
    public $objective_text;
    public $objective_attempts;
    public $objective_target;
    public $objective_status;
    // array to hold report objects
    public $reports = [];


    function __construct($id, $label, $text, $attempts, $target, $status) {
        $this->objective_id = $id;
        $this->objective_label = $label;
        $this->objective_text = $text;
        $this->objective_attempts = $attempts;
        $this->objective_target = $target;
        $this->objective_status = $status;
        
        $this->store_reports($id);
        
    }

    // Getter methods
    function get_objective_id() {
        return $this->objective_id;
    }
    function get_objective_label() {
        return $this->objective_label;
    }
    function get_objective_text() {
        return $this->objective_text;
    }
    function get_objective_attempts() {
        return $this->objective_attempts;
    }
    function get_objective_target() {
        return $this->objective_target;
    }
    function get_objective_status() {
        return $this->objective_status;
    }
    function get_reports() {
        return array_values($this->reports);
    }

    // Fill reports array with any available reports matching the passed objective_id
    function store_reports($id) {
         // connection to database

        global $conn;
        // run query to select all reports where objective_id matches

        $stmt = $conn->prepare("SELECT report_id, report_date, report_observed 
                                FROM report
                                WHERE objective_id=?
                                ORDER BY report_date DESC");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        // save each result row as a Report object in $reports
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // new Report object from row data
                $report = new Report($row['report_id'], $row['report_date'], $row['report_observed']);
                $this->reports[] = $report;
            }

        } else {
            //echo "0 Report results <br />";
        } 

    }
}

?>
