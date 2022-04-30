      <!-- insert.php - for adminSettings.php
      Spring 100 CSC 450 Capstone, Group 4
      Author: Andy Yang
      Revision: 04/29/2022 Changeed iepSettings.php to two views, adminSettings.php and userSettings.php
      Date Written: 04/29/2022: Inserts updated information into user databse, redisplays information table
      
      Resources:
      https://www.webslesson.info/2016/09/php-ajax-display-dynamic-mysql-data-in-bootstrap-modal.html
      https://www.webslesson.info/2016/10/php-ajax-update-mysql-data-through-bootstrap-modal.html
    -->

      <?php
        include_once realpath("initialization.php");
        global $conn;

        if (!empty($_POST)) {
            $output = '';
            $message = '';
            $email = mysqli_real_escape_string($conn, $_POST["email"]);
            $address = mysqli_real_escape_string($conn, $_POST["address"]);
            $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
            if ($_POST["temp_id"] != '') {
                $query = "  
          UPDATE user   
          SET user_email='$email',   
          user_address='$address',   
          user_phone='$phone'
          WHERE user_id='" . $_POST["temp_id"] . "'";
                $message = 'Information Updated';
            } else {
                $message = 'something went wrong';
            }
            if (mysqli_query($conn, $query)) {
                $output .= '<label class="text-success">' . $message . '</label>';
                $select_query = "SELECT * FROM user ORDER BY user_id DESC";
                $result = mysqli_query($conn, $select_query);
                $output .= '  
                <table class="table table-bordered">  
                     <tr>  
                          <th width="100%">Employee Name</th>  
                          <th width="30%">Update Information</th>
                          <th width="30%">Account Information</th>
                     </tr>  
           ';
                while ($row = mysqli_fetch_array($result)) {
                    $output .= '  
                     <tr>  
                          <td>' . $row["user_first_name"] . ' ' . $row["user_last_name"] . '</td>  
                          <td><input type="button" name="edit" value="Update" id="' . $row["user_id"] . '" class="btn btn-info btn-xs edit_data" /></td>  
                          <td><input type="button" name="view" value="view" id="' . $row["user_id"] . '" class="btn btn-info btn-xs view_data" /></td>  
                     </tr>  
                ';
                }
                $output .= '</table>';
            }
            echo $output;
        }
