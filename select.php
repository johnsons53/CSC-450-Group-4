      <!-- select.php - for adminSettings.php
      Spring 100 CSC 450 Capstone, Group 4
      Author: Andy Yang
      Revision: 04/29/2022 Changeed iepSettings.php to two views, adminSettings.php and userSettings.php
      Date Written: 04/29/2022: Selects user information to display in Modal
      
      Resources:
      https://www.webslesson.info/2016/09/php-ajax-display-dynamic-mysql-data-in-bootstrap-modal.html
      https://www.webslesson.info/2016/10/php-ajax-update-mysql-data-through-bootstrap-modal.html
    -->

      <?php
      global $conn;

        include_once realpath("initialization.php");

        if (isset($_POST["temp_id"])) {
            $output = '';
            $query = "SELECT * FROM user WHERE user_id = '" . $_POST["temp_id"] . "'";
            $result = mysqli_query($conn, $query);
            $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">';
            while ($row = mysqli_fetch_array($result)) {
                $output .= '  
                <tr>  
                     <td width="30%"><label>Name</label></td>  
                     <td width="70%">' . $row["user_first_name"] . ' ' . $row["user_last_name"] . '</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Account Type</label></td>  
                     <td width="70%">' . $row["user_type"] . '</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Username</label></td>  
                     <td width="70%">' . $row["user_name"] . '</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Current Password</label></td>  
                     <td width="70%">' . $row["user_password"] . '</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Current Email</label></td>  
                     <td width="70%">' . $row["user_email"] . '</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Current Address</label></td>  
                     <td width="70%">' . $row["user_address"] . '</td>  
                </tr> 
                <tr>  
                     <td width="30%"><label>Current Phone Number</label></td>  
                     <td width="70%">' . $row["user_phone"] . '</td>  
                </tr> 
                
                ';
            }
            $output .= "</table></div>";
            echo $output;
        }
