<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Dashboard</title>
    <link rel="stylesheet" href="./accountant_dashboard.css"> <!-- Link to external CSS -->
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <h2>Smartcampus</h2>
        <div class="nav-links">
            <a href="\smartcampus/smartcampus.php">Home</a>
            <a href="\smartcampus/admin/adminlogin.php">Admin login</a>
            <a href="./userlogin.php">Logout</a>
        </div>
    </div>

    <!-- Welcome Section -->
    <h1>Welcome,  
        <?php
        // PHP to fetch accountant_name from the database
        session_start();
        echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest';
        ?>
    </h1>

    <!-- Cards Section -->
    <div class="cards">
        <a href="./student_account.php" class="card">
            <img src="/smartcampus/assets/student_account.jpeg" alt="Student Accounts">
            <h3>Student Accounts</h3>
        </a>
        <a href="./teacher_account.php" class="card">
            <img src="/smartcampus/assets/teacher_account.jpeg" alt="Teacher Accounts">
            <h3>Teacher Accounts</h3>
        </a>
    </div>
</body>
</html>
