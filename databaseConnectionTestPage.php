<!DOCTYPE html>
<html>
 <head>
    <title>Database Connection Test Page</title>
</head>
<body>
    <?php 
    ini_set('display_errors', 1);
    error_reporting(E_ALL|E_STRICT);
    $filepath = realpath('login.php');
    $config = require($filepath);

    require_once realpath('User.php');
    require_once realpath('Admin.php');
    require_once realpath('Document.php');
    require_once realpath('Goal.php');
    require_once realpath('Guardian.php');
    require_once realpath('Provider.php');
    require_once realpath('Report.php');
    require_once realpath('Objective.php');
    require_once realpath('Student.php');

    
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
    /*  Tests for Admin, Guardian, Provider, Student classes.
        To include tests of methods inside each class
    */
    // Admin class:
    echo "<br /> *** <br /><br /> ADMIN CLASS TEST<br /> *** <br /><br />";
    $admins = [];
    $sql = "SELECT * 
            FROM user
            INNER JOIN admin
            ON user.user_id=admin.user_id
            WHERE user.user_type='admin'
            AND admin.admin_active='1'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // show the data in each row
        while ($row = $result->fetch_assoc()) {
            // new User object from row data
            $admin = new Admin($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                $row['user_city'], $row['user_district'], $row['user_type'], 
                $row['admin_id'], $row['admin_active']);
            // add current user to $users array
            $admins[] = $admin;
            echo $admin->get_full_name() . " added as ADMIN<br />";
            
        }
    } else {
        echo "0 results <br />";
    }
    foreach ($admins as $value) {
        echo "Admin information from User class: <br />";
        echo $value->get_user_id() . "<br />";
        echo $value->get_user_name() . "<br />";
        echo $value->get_user_password() . "<br />";
        echo $value->get_user_first_name() . "<br />";
        echo $value->get_user_last_name() . "<br />";
        echo $value->get_user_email() . "<br />";
        echo $value->get_user_phone() . "<br />";
        echo $value->get_user_address() . "<br />";
        echo $value->get_user_city() . "<br />";
        echo $value->get_user_district() . "<br />";
        echo $value->get_user_type() . "<br />";
        echo $value->get_full_name() . "<br />";
        echo "User Sent Messages: ";
        print_r($value->get_sent_messages());
        echo  "<br />";
        echo "User Received Messages: ";
        print_r($value->get_received_messages());
        echo  "<br />";

        echo "Admin information from Admin class: <br />";
        echo "Admin ID: ";
        echo $value->get_admin_id() . "<br />";
        echo "Admin Status: ";
        echo $value->get_admin_active() . "<br />";
    }

     // Student class:
    echo "<br /> *** <br /><br /> STUDENT CLASS TEST<br /> *** <br /><br />";
    $students = [];
    $sql = "SELECT *
            FROM user
            INNER JOIN student
            ON user.user_id=student.user_id
            WHERE user.user_type='student'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // show the data in each row
        while ($row = $result->fetch_assoc()) {
            // new User object from row data
            $student = new Student($conn, $row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                $row['user_city'], $row['user_district'], $row['user_type'], 
                $row['student_id'], $row['provider_id'], $row['student_school'], $row['student_grade'], 
                $row['student_homeroom'], $row['student_dob'], $row['student_eval_date'], $row['student_next_evaluation'],
                $row['student_iep_date'], $row['student_next_iep'], $row['student_eval_status'],
                $row['student_iep_status']);
            // add current user to $users array
            $students[] = $student;
            echo $student->get_full_name() . " added as STUDENT<br />";

        }
    } else {
        echo "0 results <br />";
    } 
    foreach ($students as $value) {
        echo "Student information from User class: <br />";
        echo $value->get_user_id() . "<br />";
        echo $value->get_user_name() . "<br />";
        echo $value->get_user_password() . "<br />";
        echo $value->get_user_first_name() . "<br />";
        echo $value->get_user_last_name() . "<br />";
        echo $value->get_user_email() . "<br />";
        echo $value->get_user_phone() . "<br />";
        echo $value->get_user_address() . "<br />";
        echo $value->get_user_city() . "<br />";
        echo $value->get_user_district() . "<br />";
        echo $value->get_user_type() . "<br />";
        echo $value->get_full_name() . "<br />";
        echo "User Sent Messages: ";
        print_r($value->get_sent_messages());
        echo  "<br />";
        echo "User Received Messages: ";
        print_r($value->get_received_messages());
        echo  "<br />";

        echo "Student information from Student class: <br />";

        echo "Student ID: ";
        echo $value->get_student_id() . "<br />";
        echo "Student case manager ID: ";
        echo $value->get_provider_id() . "<br />";
        echo "Student School: ";
        echo $value->get_student_school() . "<br />";
        echo "Student Grade: ";
        echo $value->get_student_grade() . "<br />";
        echo "Student Homeroom: ";
        echo $value->get_student_homeroom() . "<br />";
        echo "Student DOB: ";
        echo $value->get_student_dob() . "<br />";
        echo "Student Evaluation Date: ";
        echo $value->get_student_eval_date() . "<br />";
        echo "Student Next Evaluation On: ";
        echo $value->get_student_next_evaluation() . "<br />";
        echo "Student IEP date: ";
        echo $value->get_student_iep_date() . "<br />";
        echo "Student Next IEP on: ";
        echo $value->get_student_next_iep() . "<br />";
        echo "Student evaluation status: ";
        echo $value->get_student_eval_status() . "<br />";
        echo "Student IEP status: ";
        echo $value->get_student_iep_status() . "<br />";
        echo "Student Goals: ";
        print_r($value->get_goals()); 
        echo "<br />";
        echo "Student Documents: ";
        print_r($value->get_documents());
        echo  "<br />";
    }

      // Guardian Class test
    echo "<br /> *** <br /><br /> GUARDIAN CLASS TEST<br /> *** <br /><br />";
    $guardians = [];
    $sql = "SELECT * 
            FROM user
            WHERE user_type='user'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // show the data in each row
        while ($row = $result->fetch_assoc()) {
            // new Guardian object from row data
            $guardian = new Guardian($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                $row['user_city'], $row['user_district'], $row['user_type']);
            // add current user to $users array
            $guardians[] = $guardian;
            echo $guardian->get_full_name() . " added as GUARDIAN <br />";

        } 
    } else {
        echo "0 results <br />";
    } 

    foreach ($guardians as $value) {
        echo "Guardian information from User class: <br />";
        echo $value->get_user_id() . "<br />";
        echo $value->get_user_name() . "<br />";
        echo $value->get_user_password() . "<br />";
        echo $value->get_user_first_name() . "<br />";
        echo $value->get_user_last_name() . "<br />";
        echo $value->get_user_email() . "<br />";
        echo $value->get_user_phone() . "<br />";
        echo $value->get_user_address() . "<br />";
        echo $value->get_user_city() . "<br />";
        echo $value->get_user_district() . "<br />";
        echo $value->get_user_type() . "<br />";
        echo $value->get_full_name() . "<br />";
        echo "User Sent Messages: ";
        print_r($value->get_sent_messages());
        echo  "<br />";
        echo "User Received Messages: ";
        print_r($value->get_received_messages());
        echo  "<br />";
        echo "Guardian Students: ";
        print_r ($value->get_guardian_students());
        echo "<br />";
        
    }

    // Provider Class test
    echo "<br /> *** <br /><br /> PROVIDER CLASS TEST<br /> *** <br /><br />";
    $providers = [];
    $sql = "SELECT * 
            FROM user
            INNER JOIN provider USING (user_id)
            WHERE user_type='provider'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // show the data in each row
        while ($row = $result->fetch_assoc()) {

            // new Guardian object from row data
            $provider = new Provider($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                $row['user_city'], $row['user_district'], $row['user_type'],
                $row['provider_id'], $row['provider_title']);
            // add current user to $users array
            $providers[] = $provider;
            echo $provider->get_full_name() . " added as PROVIDER <br />";

        } 
    } else {
        echo "0 results <br />";
    } 

    foreach ($providers as $value) {
        echo "Provider information from User class: <br />";
        echo $value->get_user_id() . "<br />";
        echo $value->get_user_name() . "<br />";
        echo $value->get_user_password() . "<br />";
        echo $value->get_user_first_name() . "<br />";
        echo $value->get_user_last_name() . "<br />";
        echo $value->get_user_email() . "<br />";
        echo $value->get_user_phone() . "<br />";
        echo $value->get_user_address() . "<br />";
        echo $value->get_user_city() . "<br />";
        echo $value->get_user_district() . "<br />";
        echo $value->get_user_type() . "<br />";
        echo $value->get_full_name() . "<br />";
        echo "User Sent Messages: ";
        print_r($value->get_sent_messages());
        echo  "<br />";
        echo "User Received Messages: ";
        print_r($value->get_received_messages());
        echo  "<br />";

        echo "Provider information from Provider class: ";
        echo "Provider ID: ";
        echo $value->get_provider_id() . "<br />";
        echo "Provider Title: ";
        echo $value->get_provider_title() . "<br />";
        echo "Provider Students: ";
        print_r ($value->get_provider_students());
        echo "<br />";
        
    }


    // close connection
    $conn->close();

    echo "Connection closed. <br />";


?>

</body>

</html>


