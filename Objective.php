<?php
/*  Objective.php - Objective class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/23/2022
    Revised: 
*/

ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);      
require_once realpath('Report.php');

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
    // Should be able to get rid of this one
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
        //return $this->reports;
    }
    // Setter methods--probably don't actually want to include these.

    // Fill reports array with any available reports matching the passed objective_id
    function store_reports($id) {
        // connection to database
        $filepath = realpath('login.php');
        $config = require($filepath);
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
        // run query to select all reports where objective_id matches

        $sql = "SELECT report_id, report_date, report_observed
                FROM report
                WHERE objective_id=" . $id;
        //run query
        $result = $conn->query($sql);
        // save each result row as a Report object in $reports
        if ($result->num_rows > 0) {
            echo "number of reports found for objective_id " . $id . ":" . $result->num_rows . "<br />";
            while ($row = $result->fetch_assoc()) {
                //echo "report id: " . $row['report_id'] . "<br />";
                //echo "report date: " . $row['report_date'] . "<br />";
                //echo "report observed: " . $row['report_observed'] . "<br />";
                // new Report object from row data
                $report = new Report($row['report_id'], $row['report_date'], $row['report_observed']);
                
                $this->reports[] = $report;
                echo "report added <br />";
                //echo "report data: <br />";
                
            }
            foreach ($this->reports as $value) {
                echo $value->get_report_id() . " " . $value->get_report_observed() . " " . $value->get_report_date() . "<br />";
                //echo $value->get_user_id() . "..." . $value->get_user_first_name() . "..." . $value->get_user_type() . "<br />";
            }
        } else {
            echo "0 Report results <br />";
        }
        echo "Reports array created by store_reports() function in Objective class: <br />";
        print_r($this->reports);
        echo "<br />"; 
        // close connection to database
        $conn->close();

        //echo "Connection closed.<br />";
    }
}

?>
