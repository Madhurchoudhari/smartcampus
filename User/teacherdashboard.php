<?php
session_start();
include '/xampp/htdocs/smartcampus/dbconnection.php';

// Check if the user is logged in as a teacher
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'teacher') {
    header("Location: userlogin.php");
    exit();
}

// Fetch all students
function getAllStudents() {
    global $conn;
    $stmt = $conn->prepare("SELECT student_id, student_name,student_emailid FROM student");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_all(MYSQLI_ASSOC);
}

$students = getAllStudents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="./teacherdashboard.css">
</head>
<body>
    <div class="navbar">
        <h2>Smartcampus</h2>
        <div class="nav-links">
            <a href="\smartcampus/smartcampus.php">Home</a>
            <a href="\smartcampus/User/userlogin.php">Logout</a>
        </div>
    </div>
  
    <div class="dashboard-container">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
        <div class="cards">
            <a href="./manage_event.php" class="card">
                <img src="/smartcampus\assets\th.jpeg"alt="Manage Events">
                <h3>Manage Events</h3>
            </a>
               <a href="./manage_academics.php" class="card">
                <img src="/smartcampus\assets\manageacademics.jpeg" alt="Manage Academics">
                <h3>Manage Academics</h3>
            </a>
            <a href="./teacher_salary.php" class="card">
            <img src="/smartcampus/assets/teacher_account.jpeg" alt="Teacher Accounts">
            <h3>Teacher Accounts</h3>
        </a>
        </div>
        <h2>Student List</h2>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Student Email Id</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['student_id']) ?></td>
                        <td><?= htmlspecialchars($student['student_name']) ?></td>
                        <td><?= htmlspecialchars($student['student_emailid']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

      

      
    </div>
</body>
</html>
