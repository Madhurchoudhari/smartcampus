<?php
session_start();
include '/xampp/htdocs/smartcampus/dbconnection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}
$error_message = "";

$users = getAllUsers();

$editUser = null; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addUser'])) {
       
        $username = $_POST['user_username'];
        if (isUsernameTaken($username)) {
            $error_message = "Username is already taken. Please choose a different one.";
        } else {
            addUser($_POST['user_name'], $username, $_POST['user_number'], $_POST['user_emailid'], $_POST['user_password'], $_POST['user_role']);
            $users = getAllUsers(); // Refresh user list
        }
    } elseif (isset($_POST['updateUser'])) {
        updateUser($_POST['user_id'], $_POST['user_name'], $_POST['user_username'], $_POST['user_number'], $_POST['user_emailid'], $_POST['user_password'], $_POST['user_role']);
    } elseif (isset($_POST['deleteUser'])) {
        deleteUser($_POST['user_id']);
    } elseif (isset($_POST['editUser'])) {
        $editUser = getUserById($_POST['user_id']);
    }
    
    $users = getAllUsers();
}

function isUsernameTaken($username) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM user WHERE user_username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}
function addUser($name, $username, $number, $email, $password, $role) {
    global $conn;

    // Add user to the main `user` table
    $stmt = $conn->prepare("INSERT INTO user (user_name, user_username, user_number, user_emailid, user_password, user_role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisss", $name, $username, $number, $email, $password, $role);
    $stmt->execute();
    $stmt->close();

    // Get the last inserted user ID
    $lastUserId = $conn->insert_id;

    // Add user to the respective role-specific table
    switch ($role) {
        case 'student':
            $stmt = $conn->prepare("INSERT INTO student (student_id, student_name, student_username, student_emailid) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $lastUserId, $name, $username, $email);
            break;

        case 'teacher':
            $stmt = $conn->prepare("INSERT INTO teacher (teacher_id, teacher_name, teacher_username, teacher_password, teacher_emailid, teacher_number) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssi", $lastUserId, $name, $username, $password, $email, $number);
            break;

        case 'accountant':
            $stmt = $conn->prepare("INSERT INTO accountant (accountant_id, accountant_name, accountant_username, accountant_password, accountant_number, accountant_emailid) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssis", $lastUserId, $name, $username, $password, $number, $email);
            break;

        case 'staffmember':
            $stmt = $conn->prepare("INSERT INTO staffmember (staff_id, staff_name, staff_username, staff_emailid) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $lastUserId, $name, $username, $email);
            break;

        default:
            return; 
    }

    // Execute the role-specific query
    $stmt->execute();
    $stmt->close();
}


function updateUser($id, $name, $username, $number, $email, $password, $newRole) {
    global $conn;

    // Get the current role
    $stmt = $conn->prepare("SELECT user_role FROM user WHERE user_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentRole = $result->fetch_assoc()['user_role'];
    $stmt->close();

    // Update user in the main `user` table
    $stmt = $conn->prepare("UPDATE user SET user_name=?, user_username=?, user_number=?, user_emailid=?, user_password=?, user_role=? WHERE user_id=?");
    $stmt->bind_param("ssisssi", $name, $username, $number, $email, $password, $newRole, $id);
    $stmt->execute();
    $stmt->close();

    // If the role has changed, move the user to the new role's table
    if ($currentRole !== $newRole) {
        // Delete from the old role's table
        $stmt = $conn->prepare("DELETE FROM {$currentRole} WHERE {$currentRole}_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Insert into the new role's table
        addUser($name, $username, $number, $email, $password, $newRole);
    } else {
        // Update the role-specific table
        switch ($newRole) {
            case 'student':
                $stmt = $conn->prepare("UPDATE student SET student_name=?, student_username=?, student_emailid=? WHERE student_id=?");
                $stmt->bind_param("sssi", $name, $username, $email, $id);
                break;

            case 'teacher':
                $stmt = $conn->prepare("UPDATE teacher SET teacher_name=?, teacher_username=?, teacher_password=?, teacher_emailid=?, teacher_number=? WHERE teacher_id=?");
                $stmt->bind_param("ssssii", $name, $username, $password, $email, $number, $id);
                break;

            case 'accountant':
                $stmt = $conn->prepare("UPDATE accountant SET accountant_name=?, accountant_username=?, accountant_password=?, accountant_number=?, accountant_emailid=? WHERE accountant_id=?");
                $stmt->bind_param("ssissi", $name, $username, $password, $number, $email, $id);
                break;

            case 'staffmember':
                $stmt = $conn->prepare("UPDATE staff SET staff_name=?, staff_username=?, staff_emailid=? WHERE staff_id=?");
                $stmt->bind_param("sssi", $name, $username, $email, $id);
                break;
        }

        $stmt->execute();
        $stmt->close();
    }
}


function deleteUser($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM user WHERE user_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

function getUserById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM user WHERE user_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function getAllUsers() {
    global $conn;
    $result = $conn->query("SELECT * FROM user");
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
<link rel="stylesheet" href="./admin.css">
</head>
<body>
    
     

    
     <div class="navbar">
         <h2>Smartcampus</h2>
        <div class="nav-links">
        <a href="\smartcampus/smartcampus.php">Home</a>
        <a href="./adminlogin.php">Admin Login</a>
        <a href="\smartcampus/User/userlogin.php">User Login</a>
    </div>
    </div>

    <h1>Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></h1>
    <h2>Add User</h2>
    <?php if (!empty($error_message)): ?>
        <div class="error-message">
            <?= htmlspecialchars($error_message) ?>
        </div>
    <?php endif; ?>

    
    <form id =formmain method="post">
        <input type="text" name="user_name" placeholder="Name" required>
        <input type="text" name="user_username" placeholder="Username" required>
        <input type="text" name="user_number" placeholder="Number" required>
        <input type="text" name="user_emailid" placeholder="Email" required>
        <input type="password" name="user_password" placeholder="Password" required>
        <select name="user_role" required>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="accountant">Accountant</option>
            <option value="staffmember">Staff Member</option>
        </select>
        <button id="btn1" type="submit" name="addUser">Add User</button>
    </form>

    <h2>User List</h2>
    <table class="user-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Password</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['user_id'] ?></td>
                    <td><?= $user['user_name'] ?></td>
                    <td><?= $user['user_username'] ?></td>
                    <td><?= $user['user_number'] ?></td>
                    <td><?= $user['user_emailid'] ?></td>
                    <td><?= $user['user_password'] ?></td>
                    <td><?= ucfirst($user['user_role']) ?></td>
                    <td>
                        <div>
                        <form method="get" action="./edituser.php" style="display:inline;">
                           <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                          <button type="submit">Edit</button>
                        </form>

                        <form method="post" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                            <button type="submit" name="deleteUser">Delete</button>
                        </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="./adminlogin.php" class="logout-btn">Logout</a>
</body>
</html>
