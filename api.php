<?php
$con = mysqli_connect("localhost", "root", "", "student_dashboard");


if (!$con) {
    die("connexion error :" . mysqli_connect_error());
}


// In api.php — change these lines:
if (isset($_POST['submit'])) {
    $title    = $_POST['task-title'];
    $category = $_POST['task-category'];
    $hours    = $_POST['task-hours'];

    $sql = "INSERT INTO tasks (title, category, hours) VALUES ('$title', '$category', '$hours')";

    if (mysqli_query($con, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "insertion error :" . mysqli_error($con);
    }
}

function get_all_tasks($con) {
    return mysqli_query($con, "SELECT * FROM tasks ORDER BY id DESC");
}

function get_total_hours($con) {
    $result = mysqli_query($con, "SELECT SUM(hours) AS total FROM tasks");
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ? $row['total'] : 0;
}
?>