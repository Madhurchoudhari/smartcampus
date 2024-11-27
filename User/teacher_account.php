<?php
// Start session
session_start();

// Include database connection
include '/xampp/htdocs/smartcampus/dbconnection.php';

// Check if the user is logged in as an accountant
if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please log in as an accountant.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_id = $_POST['teacher_id'];
  
    $teacher_salary = $_POST['teacher_salary'];

    // Check if the teacher ID already exists
    $check_sql = "SELECT * FROM teacher_acc WHERE teacher_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
       
        $update_sql = "UPDATE teacher_acc SET teacher_salary = ? WHERE teacher_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("di", $teacher_salary, $teacher_id);
        $update_stmt->execute();
        $update_stmt->close();
        $message = "Teacher salary updated successfully.";
    } else {
        
        $insert_sql = "INSERT INTO teacher_acc (teacher_id, teacher_name, teacher_salary) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("isd", $teacher_id, $teacher_name, $teacher_salary);
        $insert_stmt->execute();
        $insert_stmt->close();
        $message = "Teacher added successfully.";
    }

    $stmt->close();
}

// Fetch all teacher account details
$sql = "SELECT ta.teacher_id, ta.teacher_name, ta.teacher_salary, u.user_username AS teacher_username
        FROM teacher_acc AS ta
        INNER JOIN user AS u ON ta.teacher_id = u.user_id
        WHERE u.user_role = 'teacher'";
$result = $conn->query($sql);


$teacher_list_sql = "SELECT user_id AS teacher_id, user_username AS teacher_username, user_name AS teacher_name 
                     FROM user 
                     WHERE user_role = 'teacher'";
$teacher_list_result = $conn->query($teacher_list_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Account Management</title>
    <style>
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
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}
        /* Styling same as previous version */
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
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { text-align: center; color: #333; }
        form { margin-bottom: 20px; }
        form input, form select, form button { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 4px; }
        form button { background-color: #1565c0; color: white; border: none; cursor: pointer; }
        form button:hover { background-color: #0d47a1; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { padding: 12px; text-align: left; border: 1px solid #ddd; }
        table th { background-color: #1565c0; color: white; }
        table tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>
<div class="navbar">
        <h2>SmartCampus</h2>
        <div class="nav-links">
            <a href="/smartcampus/smartcampus.php">Home</a>
            <a href="/smartcampus/User/accountant_dashboard.php">Dashboard</a>
            <a href="/smartcampus/User/userlogin.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <h1>Teacher Account Management</h1>

        <?php if (isset($message)): ?>
            <p style="color: green; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <!-- Form to Add or Update Teacher Salary -->
        <form method="post" action="">
            <label for="teacher_id">Select Teacher</label>
            <select id="teacher_id" name="teacher_id" required>
                <option value="" disabled selected>Select a teacher</option>
                <?php while ($teacher = $teacher_list_result->fetch_assoc()): ?>
                    <option value="<?php echo $teacher['teacher_id']; ?>">
                        <?php echo htmlspecialchars($teacher['teacher_username'] . " - " . $teacher['teacher_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="teacher_salary">Teacher Salary</label>
            <input type="number" id="teacher_salary" name="teacher_salary" step="0.01" required>

            <button type="submit">Save</button>
        </form>

        <!-- Table to Display All Teachers -->
        <table>
            <thead>
                <tr>
                    <th>Teacher ID</th>
                    <th>Teacher Username</th>
                  
                    <th>Teacher Salary</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['teacher_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['teacher_username']); ?></td>
                          
                            <td><?php echo htmlspecialchars($row['teacher_salary']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No teachers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
