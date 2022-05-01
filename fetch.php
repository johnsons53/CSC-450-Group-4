    <?php

    include_once realpath("initialization.php");
    global $conn;

    if (isset($_POST["temp_id"])) {
        $query = "SELECT * FROM user WHERE user_id = '" . $_POST["temp_id"] . "'";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_array($result);
        echo json_encode($row);
    }
    ?>