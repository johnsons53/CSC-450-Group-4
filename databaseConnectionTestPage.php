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

    $conn->close();

    echo "Connection closed.";


?>

</body>

</html>


