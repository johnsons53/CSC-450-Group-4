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

    // Test Query
    $sql = "SELECT * FROM user";
    $result = $conn->query($sql);

    $users = [];

    if ($result->num_rows > 0) {
        // show the data in each row
        while ($row = $result->fetch_assoc()) {
            // new User object from row data
            $user = new User($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], $row['user_last_name'],
            $row['user_email'], $row['user_phone'], $row['user_address'], $row['user_city'], $row['user_district'], $row['user_type']);
            // add current user to $users array
            $users[] = $user;
            //echo "user_id: " . $row["user_id"] . " ... first name: " . $row["user_first_name"] . " ... last name: " . $row["user_last_name"] . "<br />";
            echo "user added <br />";
            foreach ($users as $value) {
                echo $value->get_full_name() . "<br />";
                //echo $value->get_user_id() . "..." . $value->get_user_first_name() . "..." . $value->get_user_type() . "<br />";
            }
        }
    } else {
        echo "0 results <br />";
    }


    // array to hold student user_id values
    $students = [];
    $student_ids = [];

    // student_id and user_id values of a designated parent where parent_access is '1'
    $sql = "SELECT student_parent.student_id, student.user_id
    FROM student_parent
    INNER JOIN student ON student_parent.student_id = student.student_id
    WHERE student_parent.user_id='13'
    AND student_parent.parent_access='1'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        while ($row = $result->fetch_assoc()) {
            echo "student_id: " . $row["student_id"] . " ... user_id: " . $row["user_id"] . "<br />";
            // push user_id values into $students array
            $students[] = $row["user_id"];
            $student_ids[] = $row["student_id"];
        }
    } else {
        echo "0 results <br />";    
    }

    // display contents of $students
    foreach ($students as $value) {
        echo "user_id: " . $value . "<br />";
    }

    // Get and store student names
    $names = [];
    foreach ($students as $value) {
        // query database to get first and last name of student
        $sql = "SELECT user_first_name, user_last_name
                FROM user
                WHERE user_id=" . $value;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "first and last name results found: <br />";
            while ($row = $result->fetch_assoc()) {
                echo "first name: " . $row["user_first_name"] . " last name: " . $row["user_last_name"] . "<br />";
                // push name to $names array
                $names[] = $row["user_first_name"] . " " . $row["user_last_name"];
            }

        } else {
            echo "0 results <br />"; 
        }
    }

    // display contents of $names
    echo "List of student names: <br />";
    foreach ($names as $value) {
        echo "name: " . $value . "<br />";
    }

    // Use student_id to select goals
    $student_goals = [];
    foreach ($student_ids as $value) {
        // get the goals for each student in $students
        $sql = "SELECT *
                FROM goal
                WHERE student_id=" . $value;

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "Goals Found: <br />";
            while ($row = $result->fetch_assoc()) {
                echo "goal_id: " . $row["goal_id"] . " student_id: " . $row["student_id"] . " goal_label: " . $row["goal_label"];
                echo "<br />";

                // push goal_id to $student_goals array
                $student_goals[] = $row["goal_id"];

            }
        } else {
            echo "0 results for goals <br />"; 
        }
    }
    // display contents of $student_goals
    echo "List of all student goals visible to example parent: <br />";
    foreach ($student_goals as $value) {
        echo "goal_id: " . $value . "<br />";
    }

    // select objective by goal_id

    // array to hold objective objects
    $sql = "SELECT * 
            FROM objective
            WHERE objective_id='3'";

    $result = $conn->query($sql);

    $objectives =[];

    if ($result->num_rows > 0) {
        // show the data in each row
        echo "number of objectives found with objective_id=3: " . $result->num_rows . "<br />";
        while ($row = $result->fetch_assoc()) {
            // new Objective object from row data
            $objective = new Objective($row['objective_id'], $row['objective_label'], $row['objective_text'], 
            $row['objective_attempts'], $row['objective_target'], $row['objective_status']);
            // add current objective to objectives array
            echo "Reports array for new Objective before adding to objectives array: <br />";
            print_r($objective->get_reports());
            echo "<br />";

            $objectives[] = $objective;
            //echo "user_id: " . $row["user_id"] . " ... first name: " . $row["user_first_name"] . " ... last name: " . $row["user_last_name"] . "<br />";
            echo "objective added <br />";
            foreach ($objectives as $value) {
                echo "Is the current value an instance of Objective? ";
                var_dump ($value instanceof Objective);
                echo "<br />";
                echo $value->get_objective_id() . " " . $value->get_objective_label() . "<br />";
                echo "Reports array for this objective: <br />";
                print_r($value->get_reports());
                echo "<br />";
            }
        }
    } else {
        echo "0 results <br />";
    }

 
    // Test select goal from database, then create goal object with populated objectives and reports
    echo "<br />Get Goals for student wtih ID 1: <br />";
    $sql = "SELECT * 
            FROM goal
            WHERE student_id='1'";

    $result = $conn->query($sql);
    $goals = [];

    if ($result->num_rows > 0) {
        echo "number of goals found with student_id=1: " . $result->num_rows . "<br />";
        while ($row =$result->fetch_assoc()) {
            // new goal from row data
            $goal = new Goal($row['goal_id'], $row['goal_label'], $row['goal_category'], $row['goal_text'], $row['goal_active']);
            // add goal to goals array
            $goals[] = $goal;
            echo "goal added <br />";
            foreach ($goals as $value) {
                echo $value->get_goal_id() . ' ' . $value->get_goal_category() . ' ' . $value->get_goal_label() . ' ' . print_r($value->get_objectives()) .'<br />';
            }
        }
    } else {
        echo "0 results <br />";
    }

    //Access values stored in $goals
    echo "<br/ > Accessing values from Array of Goals created above: <br />";

    print_r($goals);
    echo "<br />";

    echo "Accessing the objectives arrays for each goal? <br />";
    foreach ($goals as $g) {
        echo "goal_id: ";
        var_dump($g->goal_id);
        echo "<br />";
        foreach($g->objectives as $o) {
            echo "objective_id: ";
            var_dump($o->objective_id);
            echo "<br />";
            foreach($o->reports as $r) {
                echo "report_id: ";
                var_dump($r->report_id);
                echo "<br />";
            }
        }
    }

    // Test for sent and received messages
    // SentMessage test
    echo "<br />Test getting SentMessage data for user_id 12: <br />";
    $sql = "SELECT * 
    FROM user
    WHERE user_id='12'";
    $result = $conn->query($sql);

    $users = [];

    if ($result->num_rows > 0) {
        // show the data in each row
        while ($row = $result->fetch_assoc()) {
            // new User object from row data
            $user = new User($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], $row['user_last_name'],
            $row['user_email'], $row['user_phone'], $row['user_address'], $row['user_city'], $row['user_district'], $row['user_type']);
            // add current user to $users array
            $users[] = $user;
            //echo "user_id: " . $row["user_id"] . " ... first name: " . $row["user_first_name"] . " ... last name: " . $row["user_last_name"] . "<br />";
            echo "user added <br />";
            foreach ($users as $value) {
                echo $value->get_full_name() . "<br />";
                echo "Sent Messages associated with this user: <br />";
                if (isset($value->sent_messages)) {
                    var_dump($value->sent_messages);
                    echo "<br />";
                } else {
                    echo "No sent messages found! <br />";
                }
                
                
                echo "Received Messages associated with this user: <br />";
                if (isset($value->received_messages)) {
                    var_dump($value->received_messages);
                    echo "<br />";
                } else {
                    echo "No received messages found! <br />";
                }
                
                
            }
        }
    } else {
        echo "0 results <br />";
    }
    
    // ReceivedMessage test
    echo "<br />Test getting ReceivedMessage data for user_id 11: <br />";
    $sql = "SELECT * 
    FROM user
    WHERE user_id='11'";
    $result = $conn->query($sql);

    $users = [];

    if ($result->num_rows > 0) {
        // show the data in each row
        while ($row = $result->fetch_assoc()) {
            // new User object from row data
            $user = new User($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], $row['user_last_name'],
            $row['user_email'], $row['user_phone'], $row['user_address'], $row['user_city'], $row['user_district'], $row['user_type']);
            // add current user to $users array
            $users[] = $user;
            //echo "user_id: " . $row["user_id"] . " ... first name: " . $row["user_first_name"] . " ... last name: " . $row["user_last_name"] . "<br />";
            echo "user added <br />";
            foreach ($users as $value) {
                echo $value->get_full_name() . "<br />";
                echo "Sent Messages associated with this user: <br />";
                if (isset($value->sent_messages)) {
                    var_dump($value->sent_messages);
                    echo "<br />";
                } else {
                    echo "No sent messages found! <br />";
                }
                
                
                echo "Received Messages associated with this user: <br />";
                if (isset($value->received_messages)) {
                    var_dump($value->received_messages);
                    echo "<br />";
                } else {
                    echo "No received messages found! <br />";
                }
                
                
            }
        }
    } else {
        echo "0 results <br />";
    }

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
            //echo "user_id: " . $row["user_id"] . " ... first name: " . $row["user_first_name"] . " ... last name: " . $row["user_last_name"] . "<br />";
            echo "Admin added <br />";
            foreach ($admins as $value) {
                echo $value->get_full_name() . "<br />";
                echo $value->get_user_type() . "<br />";
                echo $value->get_admin_id() . "<br />";
                echo $value->get_admin_active() . "<br />";
            }
        }
    } else {
        echo "0 results <br />";
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
            $student = new Student($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                $row['user_city'], $row['user_district'], $row['user_type'], 
                $row['student_id'], $row['provider_id'], $row['student_school'], $row['student_grade'], 
                $row['student_homeroom'], $row['student_dob'], $row['student_eval_date'], $row['student_next_evaluation'],
                $row['student_iep_date'], $row['student_next_iep'], $row['student_eval_status'],
                $row['student_iep_status']);
            // add current user to $users array
            $students[] = $student;
            echo "Student added <br />";
            foreach ($students as $value) {
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
        echo "0 results <br />";
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
            print_r($row);
            echo "<br />";
            // new Guardian object from row data
            $guardian = new Guardian($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                $row['user_city'], $row['user_district'], $row['user_type']);
            // add current user to $users array
            $guardians[] = $guardian;
            echo "Guardian added <br />";
            foreach ($guardians as $value) {
                echo "Guardian full name: ";
                echo $value->get_full_name() . "<br />";
                echo "User type: ";
                echo $value->get_user_type() . "<br />";
                echo "User ID: ";
                echo $value->get_user_id() . "<br />";
                echo "Guardian Students: ";
                print_r ($value->get_guardian_students());
                echo "<br />";
                
            }
        } 
    } else {
        echo "0 results <br />";
    } 

    // Provider Class test


    // close connection
    $conn->close();

    echo "Connection closed. <br />";


?>

</body>

</html>


