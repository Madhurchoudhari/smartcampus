<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smartcampus</title>
    <style>
       
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body Styling */
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #e3f2fd;
            color: #333;
            margin: 0;
        }

        /* Container Styling */
        .container {
            text-align: center;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }

        /* Heading */
        h1 {
            font-size: 2rem;
            color: #1565c0;
            margin-bottom: 20px;
        }

        /* Button Container */
        .log-buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        /* Buttons Styling */
        .log-buttons button {
            background-color: #1565c0;
            color: #ffffff;
            padding: 10px;
            font-size: 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .log-buttons button a {
            color: #ffffff;
            text-decoration: none;
            display: block;
            width: 100%;
            height: 100%;
        }

        /* Button Hover */
        .log-buttons button:hover {
            background-color: #0d47a1;
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
        <a href="./contactus.php">Contact us</a>
       
    </div>
    </div>
    <div class="container">
        <h1>Welcome to Smartcampus!!</h1>
        <div class="log-buttons">
            <button type="submit"><a href="http://localhost:8000/smartcampus/admin/adminlogin.php">Admin Login</a></button>
            <button type="submit"><a href="http://localhost:8000/smartcampus/User/userlogin.php">User Login</a></button>
        </div>
    </div>
</body>
</html>
