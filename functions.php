<?php
/*
functions.php - library of functions for iepPortal
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Created: 04/06/2022
      Revised: 04/17/2022 Removed refreshReports() function
      Added createStudent(), createUser()
      Revised: 04/22/2022 Added Admin specific functions for loading admin dashboard
*/
function test() {
    echo "Function test() called from functions.php.<br />";
}

/* Generate Student data given valid studentId and conn */
function createStudent($studentId, $conn) {
    try {
        $stmt = $conn->prepare(
            "SELECT *
            FROM student
            INNER JOIN user
            USING (user_id)
            WHERE student_id=?"
        );
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1) {
            while($row = $result->fetch_assoc()) {
                $student = new Student(
                    $row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                    $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                    $row['user_city'], $row['user_district'], $row['user_type'],
                    $row['student_id'], $row['provider_id'], $row['student_school'],
                    $row['student_grade'], $row['student_homeroom'], $row['student_dob'],
                    $row['student_eval_date'], $row['student_next_evaluation'], $row['student_iep_date'],
                    $row['student_next_iep'], $row['student_eval_status'], $row['student_iep_status']
                );
            }  
        }
        // Return $student
        return $student;
    } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
    }
}

/* Generate User data given valid userId, userType and conn */
function createUser($userId, $userType, $conn) {
    switch ($userType) {
        case "admin":
            // New call to database for Admin user data
            $stmt = $conn->prepare(
                "SELECT * 
                FROM user
                INNER JOIN admin
                USING (user_id)
                WHERE user_id=?
                AND admin.admin_active=\"1\"");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows == 1) {
                while($row = $result->fetch_assoc()) {
                    $admin = new Admin(
                        $row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                        $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                        $row['user_city'], $row['user_district'], $row['user_type'],
                        $row['admin_id'], $row['admin_active']
                    );

                    
                }
            }
            return $admin;
            //echo "This is a Admin account"; //Used to check account type
            break;

        case "provider":
            // New call to database for Provider user data
            $stmt = $conn->prepare(
                "SELECT * 
                FROM user
                INNER JOIN provider
                USING (user_id)
                WHERE user_id=?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows == 1) {
                while($row = $result->fetch_assoc()) {

                    $provider = new Provider(
                        $row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                        $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                        $row['user_city'], $row['user_district'], $row['user_type'],
                        $row['provider_id'], $row['provider_title']
                    );

                }
            }                    
            //echo "This is a Provider account"; //Used to check account type
            return $provider;

            break;

        case "user":
            // New call to database for rest of user data
            $stmt = $conn->prepare(
                "SELECT * 
                FROM user
                WHERE user_id=?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows == 1) {
                while($row = $result->fetch_assoc()) {
                    $guardian = new Guardian(
                        $row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                        $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                        $row['user_city'], $row['user_district'], $row['user_type']
                    );
                }
            }                    
            //echo "This is a Guardian account"; //Used to check account type
            return $guardian;

            break;

        case "student":
            // New call to database for Student User data
            $stmt = $conn->prepare(
                "SELECT * 
                FROM user
                INNER JOIN student
                USING (user_id)
                WHERE user_id=?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if($result->num_rows == 1) {
                while($row = $result->fetch_assoc()) {
                    $student = new Student(
                        $row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                        $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                        $row['user_city'], $row['user_district'], $row['user_type'],
                        $row['student_id'], $row['provider_id'], $row['student_school'],
                        $row['student_grade'], $row['student_homeroom'], $row['student_dob'],
                        $row['student_eval_date'], $row['student_next_evaluation'], $row['student_iep_date'],
                        $row['student_next_iep'], $row['student_eval_status'], $row['student_iep_status']
                    );
                }
            } 
            
            //echo "This is a Student account"; //Used to check account type
            return $student;
            break;
        
        default :
            echo "Unable to create current user <br />";
            return;
            break;
    }
}

/*
Goal functions: deleteGoal, insertGoal, updateGoal 
*/
function deleteGoal($conn, $goalId) {
    // Delete selected goal in the database
    $stmt = $conn->prepare("DELETE 
                            FROM goal
                            WHERE goal_id=?");

    // prepare statement, bind parameters
    $stmt->bind_param("i", $goalId);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    return true;

}
function insertGoal($conn, $studentId, $goalLabel, $goalCategory, $goalText, $goalActive) {
    //Insert form data into database using prepared statement and bound parameters
    $stmt = $conn->prepare("INSERT INTO goal (student_id, goal_label, goal_category, goal_text, goal_active) 
                        VALUES (?,?,?,?,?)");

    // prepare statement, bind parameters
    $stmt->bind_param("isssi", $studentId, $goalLabel, $goalCategory, $goalText, $goalActive);

    // execute prepared statement
    $stmt->execute();
    //$result = $stmt->get_result();

    return true; 
}
function updateGoal($conn, $studentId, $goalLabel, $goalCategory, $goalText, $goalActive, $goalId) {
    // Update selected goal in the database
    $stmt = $conn->prepare("UPDATE goal
                            SET student_id=?,
                                goal_label=?,
                                goal_category=?,
                                goal_text=?,
                                goal_active=?
                            WHERE goal_id=?");

    // prepare statement, bind parameters
    $stmt->bind_param("isssii", $studentId, $goalLabel, $goalCategory, $goalText, $goalActive, $goalId);

    // execute prepared statement
    $stmt->execute();
    //$result = $stmt->get_result();

    return true;
}

function refreshGoals($student) {
    // Clear existing goals
    $student->goals = [];
    // Store current goals from database
    $student->store_student_goals($student->get_student_id());

}
/*
Objective functions: deleteObjective, insertObjective, updateObjective 
*/
function deleteObjective($conn, $objectiveId) {
    // Delete selected objective in the database
    $stmt = $conn->prepare("DELETE 
                            FROM objective
                            WHERE objective_id=?");

    // prepare statement, bind parameters
    $stmt->bind_param("i", $objectiveId);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    return true;

}
function insertObjective($conn, $goalId, $objectiveLabel, $objectiveText, $objectiveAttempts, $objectiveTarget, $objectiveStatus) {
    //Insert form data into database using prepared statement and bound parameters
    $stmt = $conn->prepare("INSERT INTO objective (goal_id, objective_label, objective_text, objective_attempts, objective_target, objective_status) 
                        VALUES (?,?,?,?,?,?)");

    // prepare statement, bind parameters
    $stmt->bind_param("issiii", $goalId, $objectiveLabel, $objectiveText, $objectiveAttempts, $objectiveTarget, $objectiveStatus);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    return true; 

}
function updateObjective ($conn, $objectiveId, $goalId, $objectiveLabel, $objectiveText, $objectiveAttempts, $objectiveTarget, $objectiveStatus) {
    // Update selected objective in the database
    $stmt = $conn->prepare("UPDATE objective
                            SET goal_id=?,
                                objective_label=?,
                                objective_text=?,
                                objective_attempts=?,
                                objective_target=?,
                                objective_status=?
                            WHERE objective_id=?");

    // prepare statement, bind parameters
    $stmt->bind_param("issiiii", $goalId, $objectiveLabel, $objectiveText, $objectiveAttempts, $objectiveTarget, $objectiveStatus, $objectiveId);

    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    return true;
}
function refreshObjectives($student) {

    foreach ($student->goals as $g) {
        // clear existing objectives
        $g->objectives = [];
        // Store current objectives
        $g->store_objectives($g->get_goal_id());
        // refresh reports for each objective
        foreach ($g->objectives as $o) {
            $o->refreshObjectives($student);
        }
    }
}
/*
Report functions: deleteReport, insertReport, updateReport 
*/
function deleteReport($conn, $reportId) {
    // Delete selected report in the database
    $stmt = $conn->prepare("DELETE 
                            FROM report
                            WHERE report_id=?");

    // prepare statement, bind parameters
    $stmt->bind_param("i", $reportId);

    // execute prepared statement
    $stmt->execute();
    //$result = $stmt->get_result();
    return true;
}
function insertReport($conn, $objectiveId, $reportDate, $reportObserved) {
    //Insert form data into database using prepared statement and bound parameters
    $stmt = $conn->prepare("INSERT INTO report (objective_id, report_date, report_observed) VALUES (?,?,?)");

    // prepare statement, bind parameters
    $stmt->bind_param("isi", $objectiveId, $reportDate, $reportObserved);

    // execute prepared statement
    $stmt->execute();
    //$result = $stmt->get_result();
    return true;
}

function updateReport($conn, $objectiveId, $reportDate, $reportObserved, $reportId) {
    // Update selected report in the database
    $stmt = $conn->prepare("UPDATE report
                            SET objective_id=?,
                                report_date=?,
                                report_observed=?
                            WHERE report_id=?");

    // prepare statement, bind parameters
    $stmt->bind_param("isii", $objectiveId, $reportDate, $reportObserved, $reportId);

    // execute prepared statement
    $stmt->execute();
    //$result = $stmt->get_result();

    return true;
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    //echo "<script>alert(\"input tested\");</script>";
    return $data;
}

/*
Admin Dashboard Functions
*/

/*
Get all user_id, last and first names from database and return an associative array 
of user_ids and "Lastname, Firstname" pairs
*/
function getUserList($conn) {
    $userList = array();
    // Get user_id, last name, first name for all users
    $stmt = $conn->prepare("SELECT user_last_name, user_first_name, user_id
                            FROM user
                            ORDER By user_last_name");
    // execute prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Add values from $row to userList
            $userList[$row["user_id"]] = $row["user_last_name"] . ", " . $row["user_first_name"];
        }
    }

    return $userList;

}
/*
Get number of unread messages for specified user
*/
function countUnreadMessages($conn, $userId) {
    $messageCount = 0;
    $stmt = $conn->prepare("SELECT message_id
                            FROM message_recipient
                            WHERE user_id=? AND message_read=\"0\"");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $messageCount = $result->num_rows();
    }

    return $messageCount;
}


/*
Display selection list of all users in system, allowing multiple selected users
*/
function userSelectionList($conn) {

    // function returning Lastname, Firstname and user_id of each user from db
    $accounts = getUserList($conn);
    //print_r($accounts);

    // Select input for each available account
    // Would refine further by enabling search, or selecting by school and grade

    if (isset($accounts) && count($accounts) > 0) {
      // select input for accounts  
      echo "<label for=\"userSelect\">Select Recipient(s)</label>";
      echo "<select name=\"userSelect\" class=\"userSelect\" id=\"userSelect\" size=\"5\" multiple>";
        // Options for accountSelect
        foreach($accounts as $a => $a_value) {
            echo "<option class=\"accountOption vNavButton\" value=\"" . $a . "\"><i class=\"fa fa-user-circle\"></i>" . $a_value . "</option>";
        }
      echo "</select>"; // end of select
    }
}
/*
Get contact and account information for given userId
*/
function getUserInfo($conn, $userId) {
    $userInfo = array();

    $stmt = $conn->prepare("SELECT user_first_name, user_last_name, user_type,
                            user_name, user_password, user_email, user_address, user_phone
                            FROM user
                            WHERE user_id=?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        while ($row = $result->fetch_assoc()) {
            // Add values from $row to userInfo
            $userInfo["userFullName"] = $row["user_first_name"] . " " . $row["user_last_name"];
            $userInfo["userType"] = $row["user_type"];
            $userInfo["userPassword"] = $row["user_password"];
            $userInfo["userName"] = $row["user_name"];
            $userInfo["userEmail"] = $row["user_email"];
            $userInfo["userAddress"] = $row["user_address"];
            $userInfo["userPhone"] = $row["user_phone"];
        }
    }

    return $userInfo;
}


?>