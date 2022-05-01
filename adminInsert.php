      <!-- adminInsert.php - for adminSettings.php
      Spring 100 CSC 450 Capstone, Group 4
      Author: Andy Yang
      Revision: 04/29/2022 Changeed iepSettings.php to two views, adminSettings.php and userSettings.php
      Date Written: 04/29/2022: Inserts updated information into user databse, redisplays information table
      Revised: 04/30/2022: Splitted insert.php into adminInsert.php and userInsert.php because redisplaying table is different from one another
      
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
            $firstname = mysqli_real_escape_string($conn, $_POST["firstname"]);
            $lastname = mysqli_real_escape_string($conn, $_POST["lastname"]);
            $password = mysqli_real_escape_string($conn, $_POST["password"]);
            $email = mysqli_real_escape_string($conn, $_POST["email"]);
            $address = mysqli_real_escape_string($conn, $_POST["address"]);
            $phone = mysqli_real_escape_string($conn, $_POST["phone"]);

            if ($_POST["temp_id"] != '') {
                $query = "  
          UPDATE user   
          SET user_first_name='$firstname',   
          user_last_name='$lastname',   
          user_password='$password',   
          user_email='$email',   
          user_address='$address',   
          user_phone='$phone'
          WHERE user_id='" . $_POST["temp_id"] . "'";
                $message = 'Information Updated';
            } else {
                $message = 'Update Fail';
            }
            if (mysqli_query($conn, $query)) {
                $output .= '<label class="text-success">' . $message . '</label>';
                $select_query = "SELECT * FROM user ORDER BY user_id ASC";
                $result = mysqli_query($conn, $select_query);
                $output .= '  
                <table class="table table-bordered">  
                     <tr>  
                     <th width="100%">
                     <h3>Accounts</h3>
                 </th>
                 <th width="30%">
                     <h3>Update </h3>
                 </th>
                 <th width="30%">
                     <h3>View</h3>
                 </th>
                     </tr>  
           ';
                while ($row = mysqli_fetch_array($result)) {
                    $output .= '  
                     <tr>  
                          <td><h4>' .  $row["user_first_name"] . ' ' . $row["user_last_name"] . '</h4></td>  
                          <td><input type="button" name="edit" value="Update" id="' . $row["user_id"] . '" class="btn btn-info btn-xs edit_data" /></td>  
                          <td><input type="button" name="view" value="view" id="' . $row["user_id"] . '" class="btn btn-info btn-xs view_data" /></td>  
                     </tr>  
                ';
                }
                $output .= '</table>';
            }
            echo $output;
        }
