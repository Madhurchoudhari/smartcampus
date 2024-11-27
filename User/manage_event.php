<?php
include '/xampp/htdocs/smartcampus/dbconnection.php';
session_start();

if ($_SESSION['user_role'] !== 'teacher') {
    header("Location: userlogin.php");
    exit();
}

// Fetch all students
$students = $conn->query("SELECT student_id, student_name, student_emailid FROM student");

// Handle form submission for adding an event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_event'])) {
        $student_id = $_POST['student_id'];
        $event_name = $_POST['event_name'];
        $event_date = $_POST['event_date'];
        $event_place = $_POST['event_place'];

        // Insert the event into the database
        $stmt = $conn->prepare("INSERT INTO add_event (event_name, event_date, event_place, student_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $event_name, $event_date, $event_place, $student_id);
        $stmt->execute();
    } elseif (isset($_POST['delete_event'])) {
        $event_id = $_POST['event_id'];

        // Delete the event
        $stmt = $conn->prepare("DELETE FROM add_event WHERE event_id = ?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
    }
}

// Fetch all events with student details
$events = $conn->query("
    SELECT 
        add_event.event_id, 
        add_event.event_name, 
        add_event.event_date, 
        add_event.event_place, 
        student.student_id, 
        student.student_name, 
        student.student_emailid 
    FROM add_event 
    JOIN student ON add_event.student_id = student.student_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Events</title>
    <link rel="stylesheet" href="./manage_event.css">
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

<h1>Manage Events</h1>

<!-- Add Event Form -->
<h2>Add Event</h2>
<form method="POST" id="add_event12">
    <label for="student_id">Select Student:</label>
    <select name="student_id" required>
        <option value="">Select Student</option>
        <?php while ($student = $students->fetch_assoc()): ?>
            <option value="<?= $student['student_id'] ?>">
                <?= $student['student_name'] ?> (<?= $student['student_id'] ?>)
            </option>
        <?php endwhile; ?>
    </select>

    <label for="event_name">Event Name:</label>
    <input type="text" name="event_name" placeholder="Event Name" required>

    <label for="event_date">Event Date:</label>
    <input type="date" name="event_date" required>

    <label for="event_place">Event Place:</label>
    <input type="text" name="event_place" placeholder="Event Place" required>

    <button type="submit" name="add_event">Add Event</button>
</form>

<!-- Event List -->
<h2>Event List</h2>
<table>
    <thead>
        <tr>
            <th>Event ID</th>
            <th>Student Name</th>
            <th>Student Email</th>
            <th>Event Name</th>
            <th>Event Date</th>
            <th>Event Place</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($event = $events->fetch_assoc()): ?>
            <tr>
                <td><?= $event['event_id'] ?></td>
                <td><?= $event['student_name'] ?></td>
                <td><?= $event['student_emailid'] ?></td>
                <td><?= $event['event_name'] ?></td>
                <td><?= $event['event_date'] ?></td>
                <td><?= $event['event_place'] ?></td>
                <td>
                    <!-- Delete Button -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                        <button type="submit" name="delete_event">Delete</button>
                    </form>

                    <!-- Edit Button -->
                    <form method="GET" action="./edit_event.php" style="display:inline;">
                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                        <button type="submit">Edit</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
