<?php
// Start session
session_start();

// Include database connection
include '/xampp/htdocs/smartcampus/dbconnection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please log in to view your fees details.");
}

// Get the logged-in student ID
$logged_in_student_id = $_SESSION['user_id'];

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student fees details for the logged-in student
$sql = "SELECT 
            student.student_name AS student_name, 
            IFNULL(stud_acc.total_fees, 0.0) AS total_fees, 
            IFNULL(stud_acc.pending_fees, 0.0) AS pending_fees 
        FROM student
        LEFT JOIN stud_acc ON student.student_id = stud_acc.student_id
        WHERE student.student_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $logged_in_student_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the student data
$student_data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Fees Details</title>
    <style>
        /* General Reset */
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

        /* Navbar */
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
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .navbar .nav-links a:hover {
            background-color: #00509e;
            transform: translateY(-2px);
        }

        /* Table Styling */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #1565c0;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #ddd;
        }

        /* Headings */
        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <h2>SmartCampus</h2>
        <div class="nav-links">
            <a href="/smartcampus/smartcampus.php">Home</a>
            <a href="/smartcampus/User/student_dashboard.php">Dashboard</a>
            <a href="/smartcampus/User/userlogin.php">Logout</a>
        </div>
    </div>

    <!-- Page Content -->
    <h1>My Fees Details</h1>
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Total Fees</th>
                <th>Pending Fees</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($student_data): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student_data['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($student_data['total_fees']); ?></td>
                    <td><?php echo htmlspecialchars($student_data['pending_fees']); ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="3">No fees data available for your account.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Close statement and connection
$stmt->close();
$conn->close();
?>
