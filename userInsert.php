      <!-- userInsert.php - for adminSettings.php
      Spring 100 CSC 450 Capstone, Group 4
      Author: Andy Yang
      Revision: 04/29/2022 Changeed iepSettings.php to two views, adminSettings.php and userSettings.php
      Created: 04/29/2022: Inserts updated information into user databse, redisplays information table
      Revised: 04/30/2022: Splitted insert.php into adminInsert.php and userInsert.php because redisplaying table is different from one another
      Revised: 05/1/2022: Syntax and comment touchup

      Resources:
      https://www.webslesson.info/2016/09/php-ajax-display-dynamic-mysql-data-in-bootstrap-modal.html
      https://www.webslesson.info/2016/10/php-ajax-update-mysql-data-through-bootstrap-modal.html
    -->

      <?php
        include_once realpath("initialization.php");
        global $conn;

        // See if currentUserId and type exist in Session
        try {
            $currentUserId = $_SESSION["currentUserId"];
        } catch (Exception $e) {
            echo "Message: " . $e->getMessage();
        }

        try {
            $currentUserType = $_SESSION["currentUserType"];
        } catch (Exception $e) {
            echo "Message: " . $e->getMessage();
        }

        // Initialize currentUser as new User of correct type
        // Pass $currentUserId, $currentUserType, $conn into createUser() function
        try {
            $currentUser = createUser($currentUserId, $currentUserType, $conn);
        } catch (Exception $e) {
            echo "Message: " . $e->getMessage();
        }

        $currentUserId = $currentUser->get_user_id();

        $query = "SELECT * FROM user WHERE user_id = $currentUserId";
        $result = mysqli_query($conn, $query);

        if (!empty($_POST)) {
            $output = '';
            $message = '';
            $email = mysqli_real_escape_string($conn, $_POST["email"]);
            $address = mysqli_real_escape_string($conn, $_POST["address"]);
            $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
            if ($_POST["currentAccount"] != '') {
                $query = "  
          UPDATE user   
          SET user_email='$email',   
          user_address='$address',   
          user_phone='$phone'
          WHERE user_id='" . $_POST["currentAccount"] . "'";
                $message = 'Information Updated';
            } else {
                $message = 'Update Fail';
            }

            // Redisplays user's information onto table in userSettings.php
            if (mysqli_query($conn, $query)) {
                $output .= '<label class="text-success">' . $message . '</label>';
                $select_query = "SELECT * FROM user WHERE user_id = $currentUserId";
                $result = mysqli_query($conn, $select_query);
                $output .= '  
                <table class="table table-bordered">  
                    <tr>  
                          <th width="100%"><h3>User Settings</h3></th>  
                          <th width="30%"><h3>Update Information</h3></th>
                          <th width="30%"><h3>Account Information</h3></th>
                    </tr>  
           ';
                while ($row = mysqli_fetch_array($result)) {
                    $output .= '  
                     <tr>  
                          <td><h4>' . $row["user_first_name"] . ' ' . $row["user_last_name"] . '</h4></td>  
                          <td><input type="button" name="edit" value="Update" id="' . $row["user_id"] . '" class="btn btn-info btn-xs update_data" /></td>  
                          <td><input type="button" name="view" value="View" id="' . $row["user_id"] . '" class="btn btn-info btn-xs view_data" /></td>  
                     </tr>  
                ';
                }
                $output .= '</table>';
            }
            echo $output;
        }
