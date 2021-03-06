<?php
/*  Report.php - Report class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/23/2022
    Revised: 
    03/29/2022: Removed extraneous comments
*/

ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);      

class Report {
    // change these to public
    public $report_id;
    public $report_date;
    public $report_observed;


    function __construct($id, $date, $observed) {
        $this->report_id = $id;
        $this->report_date = $date;
        $this->report_observed = $observed;

    }

    // Getter methods
    function get_report_id() {
        return $this->report_id;
    }
    function get_report_date() {
        return $this->report_date;
    }
    function get_report_observed() {
        return $this->report_observed;
    }
}

?>
