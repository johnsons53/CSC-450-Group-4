<?php
/*  Provider.php - Provider class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/28/2022
    Revised: 
    03/20/2022: Removed old code and testing alerts
*/
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);      
require_once realpath('User.php');
require_once realpath('Student.php');

class Guardian extends User {
    // Array of students
    public $students = [];

    // constructor
    function __construct($id, $name, $password, $first_name, $last_name, $email, $phone, $address, $city, $district, $type) {

        parent::__construct($id, $name, $password, $first_name, $last_name, $email, $phone, $address, $city, $district, $type);

        // call store_guardian_students()
        $this->store_guardian_students($id);

    }
    function get_guardian_students() {
        return array_values($this->students);
    }

    // method to store students for this provider
    function store_guardian_students($id) {
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
        $stmt = $conn->prepare("SELECT * 
                                FROM user
                                INNER JOIN student USING (user_id)
                                INNER JOIN student_parent USING (student_id)
                                WHERE student_parent.user_id=? AND student_parent.parent_access=\"1\"");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
       
/*         $sql = "SELECT user.*, student.*
                FROM user
                INNER JOIN student USING (user_id)
                INNER JOIN student_parent USING (student_id)
                WHERE student_parent.user_id=" . $id . " AND student_parent.parent_access='1'";
           
        $result = $conn->query($sql); */

        if ($result->num_rows > 0) {
            // show the data in each row
            while ($row = $result->fetch_assoc()) {
                // new User object from row data
                $student = new Student($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                    $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                    $row['user_city'], $row['user_district'], $row['user_type'], 
                    $row['student_id'], $row['provider_id'], $row['student_school'], $row['student_grade'], 
                    $row['student_homeroom'], $row['student_dob'], $row['student_eval_date'], $row['student_next_evaluation'],
                    $row['student_iep_date'], $row['student_next_iep'], $row['student_eval_status'],
                    $row['student_iep_status']);
                // add current user to $users array
                $this->students[] = $student;
            }
        } else {
            //echo "0 Student results for this Guardian<br />";
        }

       
        // close connection to database
        $conn->close();
    }

}

?>
