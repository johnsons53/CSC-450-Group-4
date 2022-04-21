<?php
/*  Student.php - Student class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/22/2022
    Revised: 
    29/03/2022: Removed old versions of code, alerts used for testing
    04/15/2022: Streamlined database connection code 
    04/17/2022: Removed old database connection code
    04/19/2022: Bug fix, changed store_student_goals() to select active goals instead of complete goals
    04/20/2022: Adjust store_student_documents to match changes to database document table
*/

class Student extends User {
    protected $student_id;
    protected $provider_id;
    protected $student_school;
    protected $student_grade;
    protected $student_homeroom;
    protected $student_dob;
    protected $student_eval_date;
    protected $student_next_evaluation;
    protected $student_iep_date;
    protected $student_next_iep;
    protected $student_eval_status;
    protected $student_iep_status;

    // student will also have an arrays for goals, guardians, documents 
    public $goals = [];
    //public $guardians = [];
    public $documents = [];


    function __construct($u_id, $name, $password, $first_name, $last_name, $email, $phone, $address, $city, $district, $type,
                    $id, $p_id, $school, $grade, $homeroom, $dob, $eval_date, $next_evaluation, 
                    $iep_date, $next_iep, $eval_status, $iep_status) {
    
        
        parent::__construct($u_id, $name, $password, $first_name, $last_name, $email, $phone, $address, $city, $district, $type);

        // Specific to Student class
        $this->student_id = $id;
        $this->provider_id = $p_id;
        $this->student_school = $school;
        $this->student_grade = $grade;
        $this->student_homeroom = $homeroom;
        $this->student_dob = $dob;
        $this->student_eval_date = $eval_date;
        $this->student_next_evaluation = $next_evaluation;
        $this->student_iep_date = $iep_date;
        $this->student_next_iep = $next_iep;
        $this->student_eval_status = $eval_status;
        $this->student_iep_status = $iep_status;

        // use methods to fill goals, guardians and documents
        $this->store_student_goals($id);
        $this->store_student_documents($id);
    }

    // Getter methods
    function get_student_id() {
        return $this->student_id;
    }
    function get_provider_id() {
        return $this->provider_id;
    }
    function get_student_school() {
        return $this->student_school;
    }
    function get_student_grade() {
        return $this->student_grade;
    }
    function get_student_homeroom() {
        return $this->student_homeroom;
    }
    function get_student_dob() {
        return $this->student_dob;
    }
    function get_student_eval_date() {
        return $this->student_eval_date;
    }
    function get_student_next_evaluation() {
        return $this->student_next_evaluation;
    }
    function get_student_iep_date() {
        return $this->student_iep_date;
    }
    function get_student_next_iep() {
        return $this->student_next_iep;
    }
    function get_student_eval_status() {
        return $this->student_eval_status;
    }
    function get_student_iep_status() {
        return $this->student_iep_status;
    }
    function get_goals() {
        return array_values($this->goals);
    }
    function get_documents() {
        return array_values($this->documents);
    }
/*     Methods to store student goals, guardians, documents run queries passing in student id value */

     function store_student_goals($id) {

        global $conn;

        // run query to select all objectives where goal_id matches
        $sql = "SELECT * 
                FROM goal
                WHERE goal_active='0' AND student_id=" . $id;
    
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            while ($row =$result->fetch_assoc()) {
                // new goal from row data
                $goal = new Goal($row['goal_id'], $row['goal_label'], $row['goal_category'], $row['goal_text'], $row['goal_active']);
                // add goal to goals array
                $this->goals[] = $goal;
            }
        } else {
           //echo "0 results <br />";
        } 

    }

    function store_student_documents($id) {

        global $conn;
        // Query for documents wtih matching student id        
        $sql = "SELECT * 
                FROM document
                WHERE student_id=" . $id;
            
        $result = $conn->query($sql);
            
        if ($result->num_rows > 0) {
            while ($row =$result->fetch_assoc()) {
                // new Document from row data
                $document = new Document($row['document_id'], $row['document_name'], $row['document_date'], $row['user_id'], $row['document_path']);
                // add Document to documents array
                $this->documents[] = $document;
            }
        } else {
            //echo "0 results <br />";
        } 

    }
    
}

?>
