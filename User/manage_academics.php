<?php
include '/xampp/htdocs/smartcampus/dbconnection.php';
session_start();

if ($_SESSION['user_role'] !== 'teacher') {
    header("Location: userlogin.php");
    exit();
}

// Fetch all students
$students = $conn->query("SELECT student_id, student_name, student_emailid FROM student");

// Handle form submission for adding or deleting an academic record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_academic'])) {
        $student_id = $_POST['student_id'];
        $subject = $_POST['subject'];
        $subject_marks = $_POST['subject_marks'];

        // Insert the academic record into the database
        $stmt = $conn->prepare("INSERT INTO add_academics (subject, subject_marks, student_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $subject, $subject_marks, $student_id);
        $stmt->execute();
    } elseif (isset($_POST['delete_academic'])) {
        $academic_id = $_POST['academic_id'];

        // Delete the academic record
        $stmt = $conn->prepare("DELETE FROM add_academics WHERE academic_id = ?");
        $stmt->bind_param("i", $academic_id);
        $stmt->execute();
    }
}

// Fetch all academic records with student details
$academics = $conn->query("
    SELECT 
        add_academics.academic_id,
        add_academics.subject,
        add_academics.subject_marks,
        student.student_id,
        student.student_name,
        student.student_emailid
    FROM add_academics
    JOIN student ON add_academics.student_id = student.student_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Academics</title>
    <link rel="stylesheet" href="./manage_academics.css">
</head>
<body>
<div class="navbar">
    <h2>Smartcampus</h2>
    <div class="nav-links">
        <a href="\smartcampus/smartcampus.php">Home</a>
        <a href="\smartcampus/admin/adminlogin.php">Admin Login</a>
        <a href="./teacherdashboard.php" > Dashboard</a>
    </div>
</div>

<h1>Manage Academics</h1>


<h2>Add Academic Record</h2>
<form method="POST" id="add_event12">
  
    <select name="student_id" aria-placeholder="select_student" required>
        <option value="">Select Student</option>
        <?php while ($student = $students->fetch_assoc()): ?>
            <option value="<?= $student['student_id'] ?>">
                <?= $student['student_name'] ?> (<?= $student['student_id'] ?>)
            </option>
        <?php endwhile; ?>
    </select>

    <input type="text" name="subject" placeholder="Subject Name" required>

   
    <input type="number" name="subject_marks" placeholder="Marks" required>

    <button type="submit" name="add_academic">Add Academic Record</button>
</form>

<!-- Academic Records Table -->
<h2>Academic Records</h2>
<table>
    <thead>
        <tr>
            <th>Academic ID</th>
            <th>Student Name</th>
            <th>Student Email</th>
            <th>Subject</th>
            <th>Marks</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($academic = $academics->fetch_assoc()): ?>
            <tr>
                <td><?= $academic['academic_id'] ?></td>
                <td><?= $academic['student_name'] ?></td>
                <td><?= $academic['student_emailid'] ?></td>
                <td><?= $academic['subject'] ?></td>
                <td><?= $academic['subject_marks'] ?></td>
                <td>
                    <!-- Delete Button -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="academic_id" value="<?= $academic['academic_id'] ?>">
                        <button type="submit" name="delete_academic">Delete</button>
                    </form>

                    <!-- Edit Button -->
                    <form method="GET" action="./edit_academics.php" style="display:inline;">
                        <input type="hidden" name="academic_id" value="<?= $academic['academic_id'] ?>">
                        <button type="submit">Edit</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>
