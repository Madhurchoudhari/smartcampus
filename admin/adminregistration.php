<?php
include '/xampp/htdocs/smartcampus/dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_name = $_POST['admin_name'];
    $admin_username = $_POST['admin_username'];
    $admin_emailid=$_POST['admin_emailid'];
    $admin_password = $_POST['admin_password'];

    

   
    $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_username = ?");
    $stmt->bind_param("s", $admin_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "This username is already taken. Please choose a different one.";
        echo  " <button> <a href='./adminregistration.html'>Back to Register</a></button>";
    } else {
        
        
        $stmt = $conn->prepare("INSERT INTO admin (admin_name, admin_username,admin_emailid, admin_password) VALUES (?, ?, ?,?)");
        $stmt->bind_param("ssss", $admin_name, $admin_username,$admin_emailid,$admin_password);
        if ($stmt->execute()) {
            echo "Admin registered successfully!";
            header("Location: adminlogin.php"); 
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $stmt->close();
    $conn->close();
}
?>
