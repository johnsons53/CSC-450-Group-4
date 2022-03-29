<?php
/*  Student.php - Student class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/22/2022
    Revised: 
*/

ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);      
require_once realpath('User.php');

class Student extends User {
    // change these to protected
    protected $student_id;
    //protected $user_id;
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
    public $guardians = [];
    public $documents = [];


    function __construct($u_id, $name, $password, $first_name, $last_name, $email, $phone, $address, $city, $district, $type,
                    $id, $p_id, $school, $grade, $homeroom, $dob, $eval_date, $next_evaluation, 
                    $iep_date, $next_iep, $eval_status, $iep_status) {
    
        // Carried over from User superclass
        $this->user_id = $u_id;
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
        //$this->store_student_guardians($id);
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
        return $this->goals;
    }
    /* function get_guardians() {
        return $this->guardians;
    } */
    function get_documents() {
        return $this->documents;
    }
    // Methods to store student goals, guardians, documents run queries passing in student id value
    function store_student_goals($id) {
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

        echo "<br />Get Goals for student wtih ID 1: <br />";
        $sql = "SELECT * 
                FROM goal
                WHERE student_id=" . $id;
    
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            echo "number of goals found with student_id= " . $id . " : " . $result->num_rows . "<br />";
            while ($row =$result->fetch_assoc()) {
                // new goal from row data
                $goal = new Goal($row['goal_id'], $row['goal_label'], $row['goal_category'], $row['goal_text'], $row['goal_active']);
                // add goal to goals array
                $this->goals[] = $goal;
                echo "goal added <br />";
                foreach ($this->goals as $value) {
                    echo $value->get_goal_id() . ' ' . $value->get_goal_category() . ' ' . $value->get_goal_label() . ' ' . print_r($value->get_objectives()) .'<br />';
                }
            }
        } else {
            echo "0 results <br />";
        } 
        echo "Goal array created by store_student_goals() function in Student class: <br />";
        print_r($this->goals);
        echo "<br />"; 

        // close connection to database
        $conn->close();

        //echo "Connection closed.<br />";
    }

/*     function store_student_guardians($id) {
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
        // hold guardian user_id values
        $guardian_ids =[];
        echo "<br />Get Guardians for student wtih ID: " . $id .  "<br />";
        $sql = "SELECT user_id 
                FROM student_parent
                WHERE student_id=" . $id . "AND parent_access='1'";
           
        $result = $conn->query($sql);
        // If guardian_ids are found, store their user_id values in guardian_ids
        if ($result->num_rows > 0) {
            echo "number of guardians found with student_id= " . $id . " : " . $result->num_rows . "<br />";
            while ($row =$result->fetch_assoc()) {
                $guardian_ids[] = $row['user_id'];
            }
        } else {
            echo "0 results <br />";
        }
        
        // If guardians are found, create users with their information to be held in guardians array
        if (isset($this->guardians)) {
            // query for each guardian value
            foreach($this->guardians as $value) {
                $sql = "SELECT *
                        FROM user
                        WHERE user_id=" . $value;
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    // user data for guardian found, create new User object and store in guardians array
                    echo "number of guardians found with student_id= " . $id . " : " . $result->num_rows . "<br />";
                    while ($row =$result->fetch_assoc()) {
                        // new guardian from row data
                        $guardian = new Guardian($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], $row['user_last_name'],
                            $row['user_email'], $row['user_phone'], $row['user_address'], $row['user_city'], $row['user_district'], $row['user_type']);
                        // add current user to $users array
                        $this->guardians[] = $guardian;
                        //echo "user_id: " . $row["user_id"] . " ... first name: " . $row["user_first_name"] . " ... last name: " . $row["user_last_name"] . "<br />";
                        echo "guardian added <br />";
                        foreach ($guardians as $value) {
                            echo $value->get_full_name() . "<br />";
                        }
                    }
                } else {
                    echo "No guardians information found for user id: " . $value . "<br />";
                }
            }    
        } else {
            echo "No guardians found for student with id: " . $id . "<br />";
        }
           
        echo "Parents array created by store_student_guardians() function in Student class: <br />";
        print_r($this->guardians);
        echo "<br />"; 
       
        // close connection to database
        $conn->close();
       
    } */

    function store_student_documents($id) {
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
        // Query for documents wtih matching student id        
        echo "<br />Get Documents for student wtih ID: " . $id . "<br />";
        $sql = "SELECT * 
                FROM document
                WHERE student_id=" . $id;
            
        $result = $conn->query($sql);
            
        if ($result->num_rows > 0) {
            echo "number of documents found with student_id= " . $id . " : " . $result->num_rows . "<br />";
            while ($row =$result->fetch_assoc()) {
                // new goal from row data
                $document = new Document($row['document_id'], $row['document_name'], $row['document_date'], $row['user_id'], $row['document_data']);
                // add goal to goals array
                $this->documents[] = $document;
                echo "document added <br />";
                foreach ($this->documents as $value) {
                    echo $value->get_document_id() . ' ' . $value->get_document_name() . ' ' . $value->get_document_date() .'<br />';
                }
            }
        } else {
            echo "0 results <br />";
        } 
        echo "Document array created by store_student_documents() function in Student class: <br />";
        print_r($this->documents);
        echo "<br />"; 
        
        // close connection to database
        $conn->close();
        
        //echo "Connection closed.<br />";
    }
    
}

?>
