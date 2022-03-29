<?php
/*  Goal.php - Goal class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/23/2022
    Revised: 
*/

ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);      
require_once realpath('Objective.php');

class Goal {
    // change these to public
    public $goal_id;
    public $goal_label;
    public $goal_category;
    public $goal_text;
    public $goal_active;
    // array to hold objective objects
    public $objectives = [];


    function __construct($id, $label, $category, $text, $active) {
        $this->goal_id = $id;
        $this->goal_label = $label;
        $this->goal_category = $category;
        $this->goal_text = $text;
        $this->goal_active = $active;
        
        $this->store_objectives($id);
    }

    // Getter methods
    function get_goal_id() {
        return $this->goal_id;
    }
    // Should be able to get rid of this one
    function get_goal_label() {
        return $this->goal_label;
    }
    function get_goal_category() {
        return $this->goal_category;
    }
    function get_goal_text() {
        return $this->goal_text;
    }
    function get_goal_active() {
        return $this->goal_active;
    }
    function get_objectives() {
        return array_values($this->objectives);
        //return $this->objectives;
    }

    // Setter methods--probably don't actually want to include these.

    // Fill objectives array with any available objectives matching the passed goal_id
    function store_objectives($id) {
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
        // run query to select all objectives where goal_id matches

        $sql = "SELECT objective_id, objective_label, objective_text, objective_attempts, objective_target, objective_status
                FROM objective
                WHERE goal_id=" . $id;
        //run query
        $result = $conn->query($sql);
        // save each result row as a Objective object in $objectives
        if ($result->num_rows > 0) {
            echo "number of objectives found for goal_id " . $id . ":" . $result->num_rows . "<br />";
            while ($row = $result->fetch_assoc()) {
                //echo "objective id: " . $row['objective_id'] . "<br />";
                //echo "objective label: " . $row['objective_label'] . "<br />";
                //echo "objective text: " . $row['objective_text'] . "<br />";
                //echo "objective attempts: " . $row['objective_attempts'] . "<br />";
                //echo "objective target: " . $row['objective_target'] . "<br />";
                //echo "objective status: " . $row['objective_status'] . "<br />";
                // new Objective object from row data
                $objective = new Objective($row['objective_id'], $row['objective_label'], $row['objective_text'], 
                    $row['objective_attempts'], $row['objective_target'], $row['objective_status']);
                
                $this->objectives[] = $objective;
                echo "objective added <br />";
                //echo "objective data: <br />";
                foreach ($this->objectives as $value) {
                    echo $value->get_objective_id() . " " . $value->get_objective_label() . "<br />";
                }
            }
        } else {
            echo "0 Objective results <br />";
        } 
        echo "Objectives array created by store_objectives() function in Goal class: <br />";
        print_r($this->objectives);
        echo "<br />"; 

        // close connection to database
        $conn->close();

        //echo "Connection closed.<br />";
    }
}

?>
