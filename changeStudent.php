
<?php
    $newStudentID = $_POST['newStudentID'];
    echo "POST[newStudentID]: " . $_POST['newStudentId'];
    echo "New Student ID: " . $newStudentID . "<br />";
    $newStudent = new Student();
    foreach ($students as $value) {
        if ($value->get_student_id() == $newStudentID) {
            $newStudent = $value;
        }
    }
    print_r($newStudent);
?>
