<?php
include '/xampp/htdocs/smartcampus/dbconnection.php';
session_start();

// Ensure the user is a teacher
if ($_SESSION['user_role'] !== 'teacher') {
    header("Location: userlogin.php");
    exit();
}

// Fetch the event ID from the GET request
if (!isset($_GET['event_id'])) {
    header("Location: manage_event.php");
    exit();
}

$event_id = $_GET['event_id'];

// Fetch the specific event details
$stmt = $conn->prepare("SELECT * FROM add_event WHERE event_id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Event not found.";
    exit();
}

$event = $result->fetch_assoc();

// Handle the update functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_event'])) {
        $event_name = $_POST['event_name'];
        $event_date = $_POST['event_date'];
        $event_place = $_POST['event_place'];

        $update_stmt = $conn->prepare("UPDATE add_event SET event_name = ?, event_date = ?, event_place = ? WHERE event_id = ?");
        $update_stmt->bind_param("sssi", $event_name, $event_date, $event_place, $event_id);
        $update_stmt->execute();

        // Redirect back to manage_event.php after updating
        header("Location: manage_event.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <style>
      

      * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Body Styling */
body {
    background-color: #f5f7fa;
    margin: 0;
    padding-top: 60px; /* For fixed navbar spacing */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
}

/* Headings */
h1, h2 {
    text-align: center;
    color: #333;
    margin: 20px 0;
}
.edit-event-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background-color: #f7f7f7;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    font-family: Arial, sans-serif;
}

/* Title Styling */
.edit-event-container h1 {
    margin-bottom: 20px;
    color: #1565c0;
    font-size: 24px;
}

/* Input Fields */
.edit-event-container input,
.edit-event-container select,
.edit-event-container textarea {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    font-family: Arial, sans-serif;
    transition: border-color 0.3s, box-shadow 0.3s;
}

/* Focus Effect */
.edit-event-container input:focus,
.edit-event-container select:focus,
.edit-event-container textarea:focus {
    border-color: #1565c0;
    box-shadow: 0 0 5px rgba(21, 101, 192, 0.5);
    outline: none;
}

/* Navbar Styling */
.navbar {
    width: 100%;
    background-color: #1565c0;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between; 
    align-items: center;
    position: fixed; 
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.navbar h2 {
    color: #ffffff;
    font-size: 24px;
    font-weight: bold;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.navbar .nav-links {
    display: flex;
    gap: 20px; /* Space between links */
}

.navbar .nav-links a {
    color: #ffffff;
    text-decoration: none;
    padding: 8px 15px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 5px;
    transition: background-color 0.3s, transform 0.2s;
}

.navbar .nav-links a:hover {
    background-color: #00509e; /* Lighter Blue on hover */
    transform: translateY(-2px); /* Subtle hover effect */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Responsive Navbar for Small Screens */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column; /* Stack items vertically */
        align-items: flex-start;
    }

    .navbar h2 {
        margin-bottom: 10px;
        font-size: 20px;
    }

    .navbar .nav-links {
        flex-direction: column;
        width: 100%;
        gap: 10px;
    }

    .navbar .nav-links a {
        width: 100%;
        text-align: left;
        padding: 10px 20px;
    }
}
/* Button Styling */
button[type="submit"],
a.back-btn {
    display: inline-block;
    width: calc(50% - 10px); /* Adjust width as needed */
    padding: 10px;
    margin-top: 10px;
    text-align: center;
    text-decoration: none; /* Remove underline for the anchor tag */
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

/* Update Event Button */
button[type="submit"][name="update_event"] {
    background-color: #4CAF50; /* Green */
    color: #fff;
}

button[type="submit"][name="update_event"]:hover {
    background-color: #45a049;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Back to Dashboard Button */
a.back-btn {
    background-color: #555; /* Dark Gray */
    color: #fff;
}

a.back-btn:hover {
    background-color: #333;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

    </style>
</head>
<body>
<div class="navbar">
    <h2>Smartcampus</h2>
    <div class="nav-links">
        <a href="/smartcampus/smartcampus.php">Home</a>
        <a href="/smartcampus/admin/adminlogin.php">Admin Login</a>
        <a href="/smartcampus/User/userlogin.php">User Login</a>
    </div>
</div>
<div class="edit-event-container">
<h1>Edit Event</h1>

<form method="POST">
   
    <input type="text" id="event_name" name="event_name" placeholder="eventname" value="<?= htmlspecialchars($event['event_name']) ?>" required>

    
    <input type="date" id="event_date" name="event_date" placeholder="eventdate" value="<?= htmlspecialchars($event['event_date']) ?>" required>

   
    <input type="text" id="event_place" name="event_place" placeholder="eventplace" value="<?= htmlspecialchars($event['event_place']) ?>" required>

    <button type="submit" name="update_event">Update Event</button>
    <a href="./manage_event.php" class="back-btn">Back to Dashboard</a>
</form>
       </div>
</body>
</html>
