<?php
/*  Provider.php - Provider class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/28/2022
    Revised: 
*/
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);      
require_once realpath('User.php');
require_once realpath('Student.php');

class Guardian extends User {
    // change these to protected

    // Array of students
    public $students = [];


    function __construct($id, $name, $password, $first_name, $last_name, $email, $phone, $address, $city, $district, $type) {

        // carried over from User superclass
        $this->user_id = $id;
        $this->user_name = $name;
        $this->user_password = $password;
        $this->user_first_name = $first_name;
        $this->user_last_name = $last_name;
        $this->user_email = $email;
        $this->user_phone = $phone;
        $this->user_address = $address;
        $this->user_city = $city;
        $this->user_district = $district;
        $this->user_type = $type;

        // call store_guardian_students()
        $this->store_guardian_students($id);

    }
    function get_guardian_students() {
        return array_values($this->students);
        //return $this->students;
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
       
        // hold student_id and corresponding user_id values
        //$current_students =[];
        echo "<br />Get Students for Guardian wtih ID: " . $id .  "<br />";
        $sql = "SELECT user.*, student.*
                FROM user
                INNER JOIN student USING (user_id)
                INNER JOIN student_parent USING (student_id)
                WHERE student_parent.user_id=" . $id . " AND student_parent.parent_access='1'";
        /* $sql = "SELECT student_parent.student_id, student.user_id
                FROM student_parent
                INNER JOIN student
                ON student_parent.student_id=student.student_id
                WHERE student_parent.user_id=" . $id. "AND student_parent.parent_access='1'"; */
        /*$stmt = $conn->prepare("SELECT user.*, student.*
                                FROM user
                                INNER JOIN student USING (user_id)
                                INNER JOIN student_parent USING (student_id)
                                WHERE student_parent.user_id=? AND student_parent.parent_access='1'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();*/
           
        $result = $conn->query($sql);

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
                echo "Student added <br />";
                foreach ($this->students as $value) {
                    echo "CODE FROM GUARDIAN CLASS METHOD <br />";
                    echo "Student full name: ";
                    echo $value->get_full_name() . "<br />";
                    echo "User type: ";
                    echo $value->get_user_type() . "<br />";
                    echo "Student ID: ";
                    echo $value->get_student_id() . "<br />";
                    echo "Student Grade: ";
                    echo $value->get_student_grade() . "<br />";
                    echo "Student Evaluation Date: ";
                    echo $value->get_student_eval_date() . "<br />";
                }
            }
        } else {
            echo "0 Student results for this Guardian<br />";
        }

        // If students are found, store their student_id values in current_students
        /* if ($result->num_rows > 0) {
            echo "number of students found parent with user_id= " . $id . " : " . $result->num_rows . "<br />";
            while ($row =$result->fetch_assoc()) {
                $current_students[$row['user_id']] = $row['student_id'];
            }
        } else {
            echo "0 results <br />";
        } */
        
        // If students are found, create Students with their information to be held in students array
        /* if (isset($this->current_students)) {
            // query for each parent value
            foreach($this->current_students as $s => $s_value) {
                $sql = "SELECT *
                        FROM user
                        WHERE user_id=" . $s;

                $sql_2 = "SELECT *
                        FROM student
                        WHERE student_id=" . $s_value;
                $result = $conn->query($sql);
                $result2 = $conn->query($sql_2);
                // Did they return the same number of results? are they non zero?
                if (($result->num_rows == $result2->num_rows) && ($result->num_rows > 0)) {
                    // user data for student found, create new User object and store in current_students array
                    while ($row =$result->fetch_assoc() && $row2=$result2->fetch_assoc()) {
                        // new Student from row data
                        $student = new Student($row['user_id'], $row['user_name'], $row['user_password'], 
                                $row['user_first_name'], $row['user_last_name'], $row['user_email'], 
                                $row['user_phone'], $row['user_address'], $row['user_city'], $row['user_district'], 
                                $row['user_type'],
                                $row2['student_id'], $row2['provider_id'], $row2['student_school'], $row2['student_grade'], 
                                $row2['student_homeroom'], $row2['student_dob'], $row2['student_eval_date'], 
                                $row2['student_next_evaluation'], $row2['student_iep_date'], $row2['student_next_iep'], 
                                $row2['student_eval_status'], $row2['student_iep_status']);
                        // add current user to $users array
                        $this->students[] = $student;
                        echo "student added <br />";
                        foreach ($students as $value) {
                            echo $value->get_full_name() . "<br />";
                        }
                    }
                } else {
                    echo "No students information found for student with user id: " . $s_value . "<br />";
                }
            }    
        } else {
            echo "No students found for parent with id: " . $id . "<br />";
        } */
           
        echo "Students array created by store_guardian_students() function in Guardian class: <br />";
        print_r($this->students);
        echo "<br />"; 
       
        // close connection to database
        $conn->close();
    }

}

?>
