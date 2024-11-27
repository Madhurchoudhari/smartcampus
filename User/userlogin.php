<?php
session_start();
include '/xampp/htdocs/smartcampus/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!empty($username) && !empty($password) && !empty($role)) {
        $stmt = $conn->prepare("SELECT * FROM User WHERE user_username = ? AND user_password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if ($user['user_role'] === $role) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_role'] = $user['user_role'];

                // Redirect based on role
                switch ($role) {
                    case 'student':
                        header("Location: student_dashboard.php");
                        break;
                    case 'teacher':
                        header("Location: teacherdashboard.php");
                        break;
                    case 'staff':
                        header("Location: staff_dashboard.php");
                        break;
                    case 'accountant':
                        header("Location: accountant_dashboard.php");
                        break;
                }
                exit();
            } else {
                $error = "Incorrect role selected for this user.";
            }
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        /* General Reset */
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
        #logincontainer {
            width: 300px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            text-align: center;
        }
/* Input Fields */
form input[type="text"],
form input[type="password"],
form select {
    width: 100%;
    padding: 10px 15px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    font-family: Arial, sans-serif;
    transition: border-color 0.3s, box-shadow 0.3s;
}

form input:focus,
form select:focus {
    border-color: #1565c0;
    box-shadow: 0 0 5px rgba(21, 101, 192, 0.5);
    outline: none;
}

/* Role Dropdown */
form select {
    background-color: #ffffff;
    cursor: pointer;
}

/* Button Styling */
form button {
    width: 100%;
    padding: 12px 20px;
    margin-top: 10px;
    border: none;
    border-radius: 5px;
    background-color: #1565c0;
    color: #ffffff;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

form button:hover {
    background-color: #00509e;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

or Message Styling */
.error-message {
    color: #f44336;
    text-align: center;
    margin-top: 10px;
    font-size: 14px;
    font-weight: bold;
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
         <h2>Smartcampus</h2>
        <div class="nav-links">
        <a href="\smartcampus/smartcampus.php">Home</a>
        <a href="\smartcampus/admin/adminlogin.php">Admin Login</a>
    </div>
    </div>
    <div id="logincontainer">
    <h1>User Login</h1>
    <form method="POST" action="">
       
        <input type="text" name="username" id="username" placeholder="username"required>
        <br>
       
        <input type="password" name="password" id="password" placeholder="password" required>
        <br>
        
        <select name="role" id="role" aria-placeholder="select_role" required>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="staff">Staff</option>
            <option value="accountant">Accountant</option>
        </select>
        <br>
        <button type="submit">Login</button>
    </form>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    </div>
</body>
</html>
