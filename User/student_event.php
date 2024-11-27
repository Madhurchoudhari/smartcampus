<?php
include '/xampp/htdocs/smartcampus/dbconnection.php';
session_start();

// Ensure the user is a student
if ($_SESSION['user_role'] !== 'student') {
    header("Location: userlogin.php");
    exit();
}


$student_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT event_id, event_name, event_date, event_place 
                        FROM add_event
                        WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Events</title>
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
            padding-top: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        /* Table Styling */
        table {
            width: 80%;
            margin-top: 20px;
            border-collapse: collapse;
            text-align: left;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #1565c0;
            color: white;
        }

        td {
            background-color: #f9f9f9;
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
            gap: 20px;
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
            background-color: #00509e;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Button Styling */
        a.back-btn {
            margin-top: 20px;
            display: inline-block;
            padding: 10px;
            background-color: #555;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a.back-btn:hover {
            background-color: #333;
        }
    </style>
</head>
<body>
<div class="navbar">
    <h2>Smartcampus</h2>
    <div class="nav-links">
        <a href="\smartcampus/smartcampus.php">Home</a>
        <a href="\smartcampus/User/userlogin.php">Logout</a>
    </div>
</div>

<h1>Assigned Events</h1>

<?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Event ID</th>
                <th>Event Name</th>
                <th>Event Date</th>
                <th>Event Place</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['event_id']) ?></td>
                    <td><?= htmlspecialchars($row['event_name']) ?></td>
                    <td><?= htmlspecialchars($row['event_date']) ?></td>
                    <td><?= htmlspecialchars($row['event_place']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No events assigned.</p>
<?php endif; ?>

<a href="\smartcampus/User/student_dashboard.php" class="back-btn">Dashboard</a>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
