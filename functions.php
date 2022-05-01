<?php
/*
functions.php - library of functions for iepPortal
      Spring 100 CSC 450 Capstone, Group 4
      Author: Lisa Ahnell
      Date Created: 04/06/2022
      Revised: 04/17/2022 Removed refreshReports() function
      Added createStudent(), createUser()
      Revised: 04/22/2022 Added Admin specific functions for loading admin dashboard
      Revised: 04/30/2022 Edited comments, removed unused functions

*/

/* Generate Student data given valid studentId (not userId) and conn 
    Returns Student object or throws exception if unable to create new Student
*/
function createStudent($studentId, $conn)
{
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
        if ($result->num_rows == 1) {
            while ($row = $result->fetch_assoc()) {
                $student = new Student(
                    $row['user_id'],
                    $row['user_name'],
                    $row['user_password'],
                    $row['user_first_name'],
                    $row['user_last_name'],
                    $row['user_email'],
                    $row['user_phone'],
                    $row['user_address'],
                    $row['user_city'],
                    $row['user_district'],
                    $row['user_type'],
                    $row['student_id'],
                    $row['provider_id'],
                    $row['student_school'],
                    $row['student_grade'],
                    $row['student_homeroom'],
                    $row['student_dob'],
                    $row['student_eval_date'],
                    $row['student_next_evaluation'],
                    $row['student_iep_date'],
                    $row['student_next_iep'],
                    $row['student_eval_status'],
                    $row['student_iep_status']
                );
            }
        }
        // Return $student
        return $student;
    } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
    }
}

/* Generate User data given valid userId, userType and conn 
    Returns object of type Admin, Guardian, Provider or Student 
*/
function createUser($userId, $userType, $conn)
{
    switch ($userType) {
        case "admin":
            // New call to database for Admin user data
            $stmt = $conn->prepare(
                "SELECT * 
                FROM user
                INNER JOIN admin
                USING (user_id)
                WHERE user_id=?
                AND admin.admin_active=\"1\""
            );
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                while ($row = $result->fetch_assoc()) {
                    $admin = new Admin(
                        $row['user_id'],
                        $row['user_name'],
                        $row['user_password'],
                        $row['user_first_name'],
                        $row['user_last_name'],
                        $row['user_email'],
                        $row['user_phone'],
                        $row['user_address'],
                        $row['user_city'],
                        $row['user_district'],
                        $row['user_type'],
                        $row['admin_id'],
                        $row['admin_active']
                    );
                }
            }
            return $admin;
            break;

        case "provider":
            // New call to database for Provider user data
            $stmt = $conn->prepare(
                "SELECT * 
                FROM user
                INNER JOIN provider
                USING (user_id)
                WHERE user_id=?"
            );
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                while ($row = $result->fetch_assoc()) {

                    $provider = new Provider(
                        $row['user_id'],
                        $row['user_name'],
                        $row['user_password'],
                        $row['user_first_name'],
                        $row['user_last_name'],
                        $row['user_email'],
                        $row['user_phone'],
                        $row['user_address'],
                        $row['user_city'],
                        $row['user_district'],
                        $row['user_type'],
                        $row['provider_id'],
                        $row['provider_title']
                    );
                }
            }
            return $provider;

            break;

        case "user":
            // New call to database for rest of user data
            $stmt = $conn->prepare(
                "SELECT * 
                FROM user
                WHERE user_id=?"
            );
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                while ($row = $result->fetch_assoc()) {
                    $guardian = new Guardian(
                        $row['user_id'],
                        $row['user_name'],
                        $row['user_password'],
                        $row['user_first_name'],
                        $row['user_last_name'],
                        $row['user_email'],
                        $row['user_phone'],
                        $row['user_address'],
                        $row['user_city'],
                        $row['user_district'],
                        $row['user_type']
                    );
                }
            }
            return $guardian;

            break;

        case "student":
            // New call to database for Student User data
            $stmt = $conn->prepare(
                "SELECT * 
                FROM user
                INNER JOIN student
                USING (user_id)
                WHERE user_id=?"
            );
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                while ($row = $result->fetch_assoc()) {
                    $student = new Student(
                        $row['user_id'],
                        $row['user_name'],
                        $row['user_password'],
                        $row['user_first_name'],
                        $row['user_last_name'],
                        $row['user_email'],
                        $row['user_phone'],
                        $row['user_address'],
                        $row['user_city'],
                        $row['user_district'],
                        $row['user_type'],
                        $row['student_id'],
                        $row['provider_id'],
                        $row['student_school'],
                        $row['student_grade'],
                        $row['student_homeroom'],
                        $row['student_dob'],
                        $row['student_eval_date'],
                        $row['student_next_evaluation'],
                        $row['student_iep_date'],
                        $row['student_next_iep'],
                        $row['student_eval_status'],
                        $row['student_iep_status']
                    );
                }
            }

            return $student;
            break;

        default:
            echo "Unable to create current user <br />";
            return;
            break;
    }
}

/*
Goal functions: deleteGoal, insertGoal, updateGoal 
*/

/*
Deletes goal from database, given valid $conn and $goalId values
Returns true if successful
*/
function deleteGoal($conn, $goalId)
{
    try {
        // Delete selected goal in the database
        $stmt = $conn->prepare("DELETE 
        FROM goal
        WHERE goal_id=?");

        // prepare statement, bind parameters
        $stmt->bind_param("i", $goalId);

        // execute prepared statement
        $stmt->execute();
        return true;
    } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
        return false;
    }
}

/*
Insert goal into database, given valid $conn and goal values
Returns true if successful
*/
function insertGoal($conn, $studentId, $goalLabel, $goalCategory, $goalText, $goalActive)
{
    try {

        //Insert form data into database using prepared statement and bound parameters
        $stmt = $conn->prepare("INSERT INTO goal (student_id, goal_label, goal_category, goal_text, goal_active) 
                            VALUES (?,?,?,?,?)");

        // prepare statement, bind parameters
        $stmt->bind_param("isssi", $studentId, $goalLabel, $goalCategory, $goalText, $goalActive);

        // execute prepared statement
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
        return false;
    }
}

/*
Update goal in database, given valid $conn and goal values
Returns true if successful
*/
function updateGoal($conn, $studentId, $goalLabel, $goalCategory, $goalText, $goalActive, $goalId)
{
    try {
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
    } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
        return false;
    }
}

/*
Objective functions: deleteObjective, insertObjective, updateObjective 
*/

/*
Deletes objective from database, given valid $conn and $objectiveId values
Returns true if successful
*/
function deleteObjective($conn, $objectiveId)
{
    try {
        // Delete selected objective in the database
        $stmt = $conn->prepare("DELETE 
                                FROM objective
                                WHERE objective_id=?");

        // prepare statement, bind parameters
        $stmt->bind_param("i", $objectiveId);

        // execute prepared statement
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
        return false;
    }
}

/*
Insert objective into database, given valid $conn and objective values
Returns true if successful
*/
function insertObjective($conn, $goalId, $objectiveLabel, $objectiveText, $objectiveAttempts, $objectiveTarget, $objectiveStatus)
{
    try {
        //Insert form data into database using prepared statement and bound parameters
        $stmt = $conn->prepare("INSERT INTO objective (goal_id, objective_label, objective_text, objective_attempts, objective_target, objective_status) 
                        VALUES (?,?,?,?,?,?)");

        // prepare statement, bind parameters
        $stmt->bind_param("issiii", $goalId, $objectiveLabel, $objectiveText, $objectiveAttempts, $objectiveTarget, $objectiveStatus);

        // execute prepared statement
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
        return false;
    }
}

/*
Update objective in database, given valid $conn and objective values
Returns true if successful
*/
function updateObjective($conn, $objectiveId, $goalId, $objectiveLabel, $objectiveText, $objectiveAttempts, $objectiveTarget, $objectiveStatus)
{
    try {

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
    } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
        return false;
    }
}

/*
Report functions: deleteReport, insertReport, updateReport 
*/

/*
Deletes report from database, given valid $conn and $reportId values
Returns true if successful
*/
function deleteReport($conn, $reportId)
{
    try {
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
    } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
        return false;
    }
}

/*
Insert report into database, given valid $conn and report values
Returns true if successful
*/
function insertReport($conn, $objectiveId, $reportDate, $reportObserved)
{
    try {
        //Insert form data into database using prepared statement and bound parameters
        $stmt = $conn->prepare("INSERT INTO report (objective_id, report_date, report_observed) VALUES (?,?,?)");

        // prepare statement, bind parameters
        $stmt->bind_param("isi", $objectiveId, $reportDate, $reportObserved);

        // execute prepared statement
        $stmt->execute();
        //$result = $stmt->get_result();
        return true;
    } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
        return false;
    }
}

/*
Update report in database, given valid $conn and report values
Returns true if successful
*/
function updateReport($conn, $objectiveId, $reportDate, $reportObserved, $reportId)
{
    try {
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
    } catch (Exception $e) {
        echo "Message: " . $e->getMessage();
        return false;
    }
}

/*
Sanitize given data, and return version with dangerous content escaped
*/
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/*
Admin Dashboard Functions
*/

/*
Get all user_id, last and first names from database and return an associative array 
of user_ids and "Lastname, Firstname" pairs
*/
function getUserList($conn)
{
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
Get all user_id, last and first names from database and return an associative array 
of user_ids and "Lastname, Firstname" pairs
EXCLUDING the current user
*/
function getUserListModified($conn, $currentUserId)
{
    $userList = array();
    // Get user_id, last name, first name for all users
    $stmt = $conn->prepare("SELECT user_last_name, user_first_name, user_id
                            FROM user
                            WHERE user_id <> " . $currentUserId . 
                            " ORDER By user_last_name");
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
        $messageCount = $result->num_rows;
    }


    return $messageCount;
}


/*
Display selection list of all users in system, allowing multiple selected users
*/
function userSelectionList($conn, $currentUserId, $otherUserId) {

    // function returning Lastname, Firstname and user_id of each user from db
    $accounts = getUserListModified($conn, $currentUserId);
    //print_r($accounts);

    // Select input for each available account
    // Would refine further by enabling search, or selecting by school and grade

    if (isset($accounts) && count($accounts) > 0) {
      // select input for accounts  
      //echo "<label for=\"userSelect\">Select Recipient(s)</label>";
      //echo "<select name=\"userSelect\" class=\"userSelect\" id=\"userSelect\" size=\"5\" multiple>";
        // Options for accountSelect
        $counter = 0;
        foreach($accounts as $a => $a_value) {
            if ($a == $otherUserId) {
                echo "<option class=\"accountOption vNavButton\" selected='selected' value=\"" . $a . "\"><i class=\"fa fa-user-circle\"></i>" . $a_value . "</option>";
            }
            else {
                echo "<option class=\"accountOption vNavButton\" value=\"" . $a . "\"><i class=\"fa fa-user-circle\"></i>" . $a_value . "</option>";
            }

            /*if ($counter == 0) {
                echo "<option class=\"accountOption vNavButton\" selected='selected' value=\"" . $a . "\"><i class=\"fa fa-user-circle\"></i>" . $a_value . "</option>";
            }
            else {
                
            }*/
            $counter += 1;
        }
      //echo "</select>"; // end of select
    }
}

/*
Get contact and account information for given userId
Return an array of user specific data.
*/
function getUserInfo($conn, $userId)
{
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
