 <?php 
 session_start();
 //$sessionStudent = unserialize($_SESSION['currentStudent']);
 //echo $sessionStudent->get_full_name();
 
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

        // Select a Provider 
        $sql = "SELECT * 
        FROM user
        INNER JOIN provider USING (user_id)
        WHERE user_id='15'";
    
        $result = $conn->query($sql);
    
        if ($result->num_rows == 1) {
        // show the data in each row
        while ($row = $result->fetch_assoc()) {
            // new Guardian object from row data
            $provider = new Provider($row['user_id'], $row['user_name'], $row['user_password'], $row['user_first_name'], 
                $row['user_last_name'], $row['user_email'], $row['user_phone'], $row['user_address'], 
                $row['user_city'], $row['user_district'], $row['user_type'],
                $row['provider_id'], $row['provider_title']);
            // add current user to $_SESSION array
            $_SESSION['currentUser'] = serialize($provider);
            //$currentUser = $provider;
            
            echo $provider->get_full_name() . " created as PROVIDER <br />";
            //echo "User for this SESSION: " . $_SESSION['currentUser']->get_full_name() . " <br />";
    
        } 
        } else {
        echo "0 results <br />";
        }

        $currentUser = unserialize($_SESSION['currentUser']);
        echo $currentUser->get_full_name() . " is the current user <br />";

    
        // Save array of students
        $students = $currentUser->get_provider_students();
        $_SESSION['currentStudents'] = serialize($students);
    
        // Set default current student to first student in the list
        //$_SESSION['currentStudent'] = serialize($students[0]);
        //$_SESSION['activeStudent'] = serialize($students[0]);
        $myCurrentStudent =$students[0];
 
 ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 <!DOCTYPE html>
 <html>

 <?php
    if(array_key_exists('activeStudentId', $_POST)) {
        echo "activeStudentId: " . $_POST['activeStudentId'];
    }
    // Links to each student for this user
    // Make these buttons that change the value of $current_student
    $studentCount = 0;
    foreach ($students as $value) {
        $studentName = $value->get_full_name();
        $studentId = $value->get_student_id();
        if ($studentCount == 0) {
            echo "<div class=\"tab\">";
        echo "<button class=\"tablinks\" onclick=\"openTab(event, '" . $studentName . "')\" id=\"defaultOpen\" data-studentId=\"" . $studentId . "\" data-studentName=\"" . $studentName . "\">" . $studentName . "</button>";
        echo "</div>";
        } else {
            echo "<div class=\"tab\">";
            echo "<button class=\"tablinks\" data-studentId=\"" . $studentId . "\" data-studentName=\"" . $studentName . "\" onclick=\"openTab(event, '" . $studentName . "')\">" . $studentName . "</button>";
            echo "</div>";
        }
        

        $studentCount++;
    }

    
    foreach ($students as $value) {
        $studentName = $value->get_full_name();
        echo "<div id=\"" . $studentName . "\" class=\"tabcontent\">";

        echo "</div>";
    }
        
?>

<script> document.getElementById("defaultOpen").click(); </script>
<style>
    /* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons that are used to open the tab content */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}  

</style>
<script>
    //jQuery
    $(document).ready(function() {
        $(".tablinks").click(function() {
            $(".tabcontent").load("tabContent.php", {
                activeStudentId: $(this).attr("data-studentId"),
                activeStudentName: $(this).attr("data-studentName")
            });
        });
        //Identify the defaultOpen element
        document.getElementById("defaultOpen").click();
    });

function openTab(evt, tabName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
    
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>    
</html>
