<?php
include '/xampp/htdocs/smartcampus/dbconnection.php';
session_start();

// Ensure the user is a teacher
if ($_SESSION['user_role'] !== 'teacher') {
    header("Location: userlogin.php");
    exit();
}

// Fetch the academic ID from the GET request
if (!isset($_GET['academic_id'])) {
    header("Location: manage_academics.php");
    exit();
}

$academic_id = $_GET['academic_id'];

// Fetch the specific academic details
$stmt = $conn->prepare("SELECT * FROM add_academics WHERE academic_id = ?");
$stmt->bind_param("i", $academic_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Academic record not found.";
    exit();
}

$academic = $result->fetch_assoc();

// Handle the update functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_academic'])) {
        $subject = $_POST['subject'];
        $subject_marks = $_POST['subject_marks'];

        $update_stmt = $conn->prepare("UPDATE add_academics SET subject = ?, subject_marks = ? WHERE academic_id = ?");
        $update_stmt->bind_param("ssi", $subject, $subject_marks, $academic_id);
        $update_stmt->execute();

        // Redirect back to manage_academics.php after updating
        header("Location: manage_academics.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Academic Record</title>
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

        .edit-academic-container {
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
        .edit-academic-container h1 {
            margin-bottom: 20px;
            color: #1565c0;
            font-size: 24px;
        }

        /* Input Fields */
        .edit-academic-container input,
        .edit-academic-container select,
        .edit-academic-container textarea {
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
        .edit-academic-container input:focus,
        .edit-academic-container select:focus,
        .edit-academic-container textarea:focus {
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

        /* Responsive Navbar for Small Screens */
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

        /* Button Styling */
        button[type="submit"],
        a.back-btn {
            display: inline-block;
            width: calc(50% - 10px);
            padding: 10px;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        /* Update Academic Record Button */
        button[type="submit"][name="update_academic"] {
            background-color: #4CAF50;
            color: #fff;
        }

        button[type="submit"][name="update_academic"]:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Back to Dashboard Button */
        a.back-btn {
            background-color: #555;
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

<div class="edit-academic-container">
    <h1>Edit Academic Record</h1>

    <form method="POST">
        <input type="text" id="subject" name="subject" placeholder="Subject Name" value="<?= htmlspecialchars($academic['subject']) ?>" required>

        <input type="number" id="subject_marks" name="subject_marks" placeholder="Subject Marks" value="<?= htmlspecialchars($academic['subject_marks']) ?>" required>

        <button type="submit" name="update_academic">Update Academic</button>
        <a href="./manage_academics.php" class="back-btn"> Dashboard</a>
    </form>
</div>
</body>
</html>
