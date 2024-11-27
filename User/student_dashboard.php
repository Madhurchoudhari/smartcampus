<?php
session_start();
if ($_SESSION['user_role'] !== 'student') {
    header("Location: userlogin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <style>
        /* General Reset */
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
    padding-top: 60px; /* For fixed navbar spacing */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* Headings */
h1, h2 {
    text-align: center;
    color: #333;
    margin: 20px 0;
}

/* Logout Button */
a {
    display: inline-block;
    padding: 10px 20px;
    background-color: #1565c0;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.2s ease;
    
}

a:hover {
  
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    h1 {
        font-size: 24px;
    }

    p {
        font-size: 16px;
    }

    a {
        font-size: 14px;
    }
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
.cards {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-top: 30px;
}
.card {
    width: 200px;
    text-align: center;
    background-color: white;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: transform 0.2s;
}
.card:hover {
    transform: scale(1.05);
}
.card img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
}
.card h3 {
    margin-top: 10px;
    color: #34495e;
    text-decoration: none;
}

#logout{
    margin-top: 20px;
}
    </style>
</head>
<body>
<div class="navbar">
         <h2>Smartcampus</h2>
        <div class="nav-links">
        <a href="\smartcampus/smartcampus.php">Home</a>
        <a href="./adminlogin.php">Admin Login</a>
        
    </div>
    </div>
    
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
    <div class="cards">
            <a href="./student_event.php" class="card">
                <img src="/smartcampus\assets\th.jpeg"alt="Manage Events">
                <h3>Events</h3>
            </a>
               <a href="./student_academics.php" class="card">
                <img src="/smartcampus\assets\manageacademics.jpeg" alt="Manage Academics">
                <h3> Academics</h3>
            </a>
            <a href="./student_fees.php" class="card">
            <img src="/smartcampus/assets/student_account.jpeg" alt="Student Accounts">
            <h3>Student Account</h3>
        </a>
        </div>
        <div id="logout"><a href="userlogin.php">Logout</a></div>
        
    
</body>
</html>
