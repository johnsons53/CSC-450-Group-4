<?php
/*  Goal.php - Goal class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/23/2022
    Revised: 
    03/29/2022: Removed testing alerts
    04/15/2022: Streamlined database connection code 
*/


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
    }

    // Fill objectives array with any available objectives matching the passed goal_id
    function store_objectives($id) {

        global $conn;
        // run query to select all objectives where goal_id matches

        $sql = "SELECT objective_id, objective_label, objective_text, objective_attempts, objective_target, objective_status
                FROM objective
                WHERE goal_id=" . $id;
        //run query
        $result = $conn->query($sql);
        // save each result row as a Objective object in $objectives
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // new Objective object from row data
                $objective = new Objective($row['objective_id'], $row['objective_label'], $row['objective_text'], 
                    $row['objective_attempts'], $row['objective_target'], $row['objective_status']);
                
                $this->objectives[] = $objective;
            }
        } else {
            //echo "0 Objective results <br />";
        } 

    }
}

?>
