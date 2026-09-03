<?php
$con = mysqli_connect("localhost", "root", "", "student_dashboard");

if (!$con) {
    die("Connection error: " . mysqli_connect_error());
}

// ==========================================
// 1. ADD NEW TASK FORM SUBMISSION (POST)
// ==========================================
if (isset($_POST['submit'])) {
    $title        = mysqli_real_escape_string($con, $_POST['task-title']);
    $category     = mysqli_real_escape_string($con, $_POST['task-category']);
    $target_hours = floatval($_POST['task-hours']);
    
    // Convert target hours into total seconds for timer
    $remaining_seconds = intval($target_hours * 3600);

    // Initial spent hours is 0. Target hours stored in remaining_seconds
    $sql = "INSERT INTO tasks (title, category, hours, remaining_seconds) 
            VALUES ('$title', '$category', 0, $remaining_seconds)";

    if (mysqli_query($con, $sql)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Insertion error: " . mysqli_error($con);
    }
}

// ==========================================
// 2. AJAX ENDPOINT: UPDATE TIME FROM FOCUS SESSION
// ==========================================
if (isset($_POST['action']) && $_POST['action'] === 'update_time') {
    header('Content-Type: application/json');

    $task_id           = intval($_POST['task_id']);
    $studied_seconds   = intval($_POST['studied_seconds']);
    $remaining_seconds = intval($_POST['remaining_seconds']);

    // Convert seconds studied during active focus session to fractional hours
    $additional_hours = $studied_seconds / 3600;

    $sql = "UPDATE tasks 
            SET remaining_seconds = $remaining_seconds, 
                hours = hours + $additional_hours 
            WHERE id = $task_id";

    if (mysqli_query($con, $sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($con)]);
    }
    exit();
}

// ==========================================
// 3. AJAX ENDPOINT: DELETE TASK
// ==========================================
if (isset($_POST['action']) && $_POST['action'] === 'delete_task') {
    header('Content-Type: application/json');

    $task_id = intval($_POST['task_id']);
    $sql = "DELETE FROM tasks WHERE id = $task_id";

    if (mysqli_query($con, $sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($con)]);
    }
    exit();
}

// ==========================================
// 4. HELPER QUERY FUNCTIONS
// ==========================================
function get_all_tasks($con) {
    return mysqli_query($con, "SELECT * FROM tasks ORDER BY id DESC");
}

function get_total_hours($con) {
    $result = mysqli_query($con, "SELECT SUM(hours) AS total FROM tasks");
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ? number_format($row['total'], 2) : "0.00";
}

function get_total_tasks_count($con) {
    $result = mysqli_query($con, "SELECT COUNT(*) AS total FROM tasks");
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ? $row['total'] : 0;
}

function get_completed_tasks_count($con) {
    $result = mysqli_query($con, "SELECT COUNT(*) AS completed FROM tasks WHERE remaining_seconds <= 0");
    $row = mysqli_fetch_assoc($result);
    return $row['completed'] ? $row['completed'] : 0;
}

function get_remaining_tasks_count($con) {
    $result = mysqli_query($con, "SELECT COUNT(*) AS remaining FROM tasks WHERE remaining_seconds > 0");
    $row = mysqli_fetch_assoc($result);
    return $row['remaining'] ? $row['remaining'] : 0;
}

// Format seconds into HH:MM:SS format for the table
function format_seconds_to_time($seconds) {
    $hrs = floor($seconds / 3600);
    $mins = floor(($seconds % 3600) / 60);
    $secs = $seconds % 60;
    return sprintf('%02dh %02dm %02ds', $hrs, $mins, $secs);
}
?>