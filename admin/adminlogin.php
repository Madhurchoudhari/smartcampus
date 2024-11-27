<?php
session_start();
include '/xampp/htdocs/smartcampus/dbconnection.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['admin_username'];
    $password = $_POST['admin_password'];

    // Prepare the SQL statement
    $sql = "SELECT admin_id, admin_name FROM admin WHERE admin_username = ? AND admin_password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
       
        $admin = $result->fetch_assoc();
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['admin_name']; 

        header("Location: admin.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
          * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

      
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #e3f2fd;
            color: #333;
            margin: 0;
        }

        /* Container Styling */
        .container {
            width: 300px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            text-align: center;
        }

        /* Form Title */
        h1 {
            font-size: 1.8rem;
            color: #1565c0;
            margin-bottom: 20px;
        }

        /* Input Fields */
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }

        /* Buttons */
        .button {
            display: inline-block;
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            font-size: 1rem;
            background-color: #1565c0;
            color: white;
            border: none;
            border-radius: 4px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .button:hover {
            background-color: #0d47a1;
        }

        /* Flex container for button layout */
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
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

    </style>
</head>
<body>
<div class="navbar">
         <h2>SmartCampus</h2>
        <div class="nav-links">
        <a href="\smartcampus/smartcampus.php">Home</a>
        <a href="\smartcampus/User/userlogin.php">User Login</a>
    </div>
    </div>

   
    <div class="container">
        <h1>Admin Login</h1>
        
        <form method="post" >
            <input type="text" name="admin_username" placeholder="Username" required>
            <input type="password" name="admin_password" placeholder="Password" required>
            
            <div class="button-group">
                <button type="submit" class="button">Login</button>
                <a href="./adminregistration.html" class="button">Add Admin</a>
               
            </div>
        </form>
    </div>
</body>
</html>
