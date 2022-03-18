<?php
/* classes.php - class definitions
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Written: 03/14/2022
      Revised: 
      */

class Report {
    public $report_id;
    public $objective_id;
    public $report_date;
    public $report_observed;

    function __construct($r_id, $o_id, $date, $observed) {
        this->$report_id = $r_id;
        this->$objective_id = $o_id;
        this->$report_date = $date;
        this->$report_observed = $observed;
    }

    function get_report_id() {
        return this->$report_id;
    }
    function get_objective_id() {
        return this->$objective_id;
    }
    function get_report_date() {
        return this->$report_date;
    }
    function get_report_observed() {
        return this->$report_observed;
    }
}

class Objective {
    $objective_id;
    $goal_id;
    $objective_label;
    $objective_text;
    $objective_attempts;
    $objective_target;
    $objective_status;
    // an array of Report objects
    $reports = [];

    function __construct($o_id, $g_id, $label, $text, $attempts, $target, $status) {
        this->$objective_id = $o_id;
        this->$goal_id = $g_id;
        this->$objective_label = $label;
        this->$objective_text = $text;
        this->$objective_attempts = $attempts;
        this->$objective_target = $target;
        this->$objective_status = $status;
    }
    // method to assign an array of Reports to $reports
    function set_objective_reports($reports) {
        foreach($reports as $r) {
            this->$reports
        }
    }

    // getter methods to go here
}

class Goal {
    $goal_id;
    $student_id;
    $goal_label;
    $goal_category;
    $goal_text;
    $goal_active;
    $objectives;

}

class Student {
    $student_id;
    $user_id;
    $provider_id;
    $student_school;
    $student_grade;
    $student_homeroom;
    $student_dob;
    $student_eval_date;
    $student_next_evaluation;
    $student_iep_date;
    $student_next_iep;
    $student_eval_status;
    $student_iep_status;
    $goals;

}

class Parent {
    $user_id;
    $students;

}

class Provider {
    $provider_id;
    $user_id;
    $provider_title;
    $students;

}

class Admin {

}

class User {
    $user_id;
    $user_name;
    $user_password;
    $user_first_name;
    $user_last_name;
    $user_email;
    $user_phone;
    $user_address;
    $user_city;
    $user_district;
    $user_type;

}

class Message_recipient {

}
 
class Message {

}

class Document {

}
?>