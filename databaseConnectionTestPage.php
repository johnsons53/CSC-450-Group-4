<!DOCTYPE html>
<html>
 <head>
    <title>Database Connection Test Page</title>
</head>
<body>
    <?php 
    $config = require ' login.php'; 
    $db_hostname = $config['DB_HOSTNAME'];
    $db_username = $config['DB_USERNAME'];
    $db_password = $config['DB_PASSWORD'];
    $db_database = $config['DB_DATABASE'];

    //$servername = 'localhost';
    //$username = 'root';
    //$password = 'root';
    //$dbname = 'iep_portal';

    
    echo "db_hostname : " . $db_hostname . "<br />";
    echo "db_username : " . $db_username . "<br />";
    echo "db_password : " . $db_password . "<br />";
    echo "db_database : " . $db_database . "<br />";

    // Create connection
    $conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Connected successfully. <br />";

    // Test Query
    $sql = "SELECT user_id, user_first_name, user_last_name FROM user";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // show the data in each row
        while ($row = $result->fetch_assoc()) {
            echo "user_id: " . $row["user_id"] . " ... first name: " . $row["user_first_name"] . " ... last name: " . $row["user_last_name"] . "<br />";
        }
    } else {
        echo "0 results <br />";
    }

    // array to hold student user_id values
    $students = [];

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

    // close connection
    $conn->close();

    echo "Connection closed.";


?>

</body>

</html>


