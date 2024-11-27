<?php
// Start the session
session_start();

include '/xampp/htdocs/smartcampus/dbconnection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to add or update fees
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $total_fees = !empty($_POST['total_fees']) ? $_POST['total_fees'] : 0.0;
    $pending_fees = !empty($_POST['pending_fees']) ? $_POST['pending_fees'] : 0.0;

    // Check if the student already has a record in stud_acc
    $checkQuery = "SELECT * FROM stud_acc WHERE student_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      
        $updateQuery = "UPDATE stud_acc SET total_fees = ?, pending_fees = ? WHERE student_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ddi", $total_fees, $pending_fees, $student_id);
        $updateStmt->execute();
        $message = "Fees updated successfully!";
    } else {
       
        $insertQuery = "INSERT INTO stud_acc (student_id, total_fees, pending_fees) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("idd", $student_id, $total_fees, $pending_fees);
        $insertStmt->execute();
        $message = "Fees added successfully!";
    }
}

// Fetch data for display
$sql = "SELECT 
            student.student_id, 
            student.student_username, 
            student.student_name, 
             IFNULL(stud_acc.total_fees, 0.0) AS total_fees, 
            IFNULL(stud_acc.pending_fees, 0.0) AS pending_fees
        FROM student
        LEFT JOIN stud_acc ON student.student_id = stud_acc.student_id";

$result = $conn->query($sql);

// Fetch students for the form dropdown
$students = $conn->query("SELECT student_id, student_username, student_name FROM student");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Accounts</title>
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
        table {
            width: 90%;
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
        form {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f7f7f7;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            font-family: Arial, sans-serif;
        }
        form button {
            background-color: #1565c0;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        form button:hover {
            background-color: #00509e;
        }
        .message {
            text-align: center;
            color: green;
            font-weight: bold;
        }
        
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

@media (max-width: 768px) {
    .navbar {
        flex-direction: column; 
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

    </style>
</head>
<body>
<div class="navbar">
        <h2>Smartcampus</h2>
        <div class="nav-links">
            <a href="\smartcampus/smartcampus.php">Home</a>
            <a href="\smartcampus/User/accountant_dashboard.php">Dashboard</a>
            <a href="./userlogin.php">Logout</a>
        </div>
    </div>
    <h1 style="text-align: center;">Student Accounts</h1>
    
    <!-- Success Message -->
    <?php if (!empty($message)) { echo "<p class='message'>{$message}</p>"; } ?>

    <!-- Student Accounts Table -->
    <table>
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Username</th>
                <th>Name</th>
                <th>Total Fees</th>
                <th>Pending Fees</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['student_id']}</td>
                            <td>{$row['student_username']}</td>
                            <td>{$row['student_name']}</td>
                            <td>{$row['total_fees']}</td>
                            <td>{$row['pending_fees']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Add/Update Fees Form -->
    <form method="POST">
        <h2 style="text-align: center;">Add/Update Fees</h2>
        <label for="student_id">Select Student:</label>
        <select name="student_id" id="student_id" required>
            <option value="">Select a Student</option>
            <?php
            if ($students->num_rows > 0) {
                while ($student = $students->fetch_assoc()) {
                    echo "<option value='{$student['student_id']}'>ID: {$student['student_id']} - {$student['student_username']} ({$student['student_name']})</option>";
                }
            }
            ?>
        </select>
        <label for="total_fees">Total Fees:</label>
        <input type="number" name="total_fees" id="total_fees" step="0.01" placeholder="Enter Total Fees" required>
        <label for="pending_fees">Pending Fees:</label>
        <input type="number" name="pending_fees" id="pending_fees" step="0.01" placeholder="Enter Pending Fees" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>

<?php
$conn->close();
?>
