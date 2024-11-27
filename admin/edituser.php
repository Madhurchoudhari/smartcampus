<?php
session_start();
include '/xampp/htdocs/smartcampus/dbconnection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.php");
    exit();
}

if (!isset($_GET['user_id'])) {
    header("Location: admin.php");
    exit();
}

$userId = $_GET['user_id'];
$editUser = getUserById($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    updateUser($_POST['user_id'], $_POST['user_name'], $_POST['user_username'], $_POST['user_number'], $_POST['user_emailid'], $_POST['user_password'], $_POST['user_role']);
    header("Location: admin.php");
    exit();
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

function updateUser($id, $name, $username, $number, $email, $password, $role) {
    global $conn;
    $stmt = $conn->prepare("UPDATE user SET user_name=?, user_username=?, user_number=?, user_emailid=?, user_password=?, user_role=? WHERE user_id=?");
    $stmt->bind_param("ssisssi", $name, $username, $number, $email, $password, $role, $id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="./edituser.css">
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
    <div class="edit-user-container">
        <h1>Edit User</h1>
        <form method="post">
            <input type="hidden" name="user_id" value="<?= $editUser['user_id'] ?>">
            <input type="text" name="user_name" value="<?= htmlspecialchars($editUser['user_name']) ?>" placeholder="Name" required>
            <input type="text" name="user_username" value="<?= htmlspecialchars($editUser['user_username']) ?>" placeholder="Username" required>
            <input type="text" name="user_number" value="<?= htmlspecialchars($editUser['user_number']) ?>" placeholder="Phone Number" required>
            <input type="text" name="user_emailid" value="<?= htmlspecialchars($editUser['user_emailid']) ?>" placeholder="Email" required>
            <input type="text" name="user_password" value="<?= htmlspecialchars($editUser['user_password']) ?>" placeholder="Password" required>
            <select name="user_role" required>
                <option value="student" <?= $editUser['user_role'] === 'student' ? 'selected' : '' ?>>Student</option>
                <option value="teacher" <?= $editUser['user_role'] === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                <option value="accountant" <?= $editUser['user_role'] === 'accountant' ? 'selected' : '' ?>>Accountant</option>
                <option value="staffmember" <?= $editUser['user_role'] === 'staffmember' ? 'selected' : '' ?>>Staff Member</option>
            </select>
            <button type="submit" class="update-btn">Update User</button>
        </form>
        <a href="./admin.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
