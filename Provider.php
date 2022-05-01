<?php
/*  Provider.php - Provider class definition
    Spring 100 CSC 450 Capstone, Group 4
    Author: Lisa Ahnell
    Date Written: 03/22/2022
    Revised: 
    03/29/2022: Removed old code and testing alerts
    04/15/2022: Streamlined database connection code
*/


class Provider extends User {
    protected $provider_id;
    protected $provider_title;

    // Array of students
    public $students = [];

    function __construct($u_id, $name, $password, $first_name, $last_name, $email, $phone, $address, $city, $district, $type,
                $id, $title) {

        parent::__construct($u_id, $name, $password, $first_name, $last_name, $email, $phone, $address, $city, $district, $type);

        // unique to Provider class
        $this->provider_id = $id;
        $this->provider_title = $title;

        // call store_provider_students()
        $this->store_provider_students($id);

    }

    // Getter methods
    function get_provider_id() {
        return $this->provider_id;
    }
    function get_provider_title() {
        return $this->provider_title;
    }
    function get_provider_students() {
        return array_values($this->students);
    }

    // method to store students for this provider
    function store_provider_students($id) {
        // connection to database
        global $conn;

        $stmt = $conn->prepare("SELECT *
                                FROM user
                                INNER JOIN student USING (user_id)
                                WHERE student.provider_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // students found, store in students array
            while ($row =$result->fetch_assoc()) {
                // new Student from row data
                $student = new Student($row['user_id'], $row['user_name'], $row['user_password'], 
                    $row['user_first_name'], $row['user_last_name'], $row['user_email'], 
                    $row['user_phone'], $row['user_address'], $row['user_city'], $row['user_district'], 
                    $row['user_type'],
                    $row['student_id'], $row['provider_id'], $row['student_school'], $row['student_grade'], 
                    $row['student_homeroom'], $row['student_dob'], $row['student_eval_date'], 
                    $row['student_next_evaluation'], $row['student_iep_date'], $row['student_next_iep'], 
                    $row['student_eval_status'], $row['student_iep_status']);
                // add current user to $students array
                $this->students[] = $student;                
            }
        } else {
            //echo "0 Students found for Provider with provider_id: ". $id . "<br />";
        }       

    }

}

?>
