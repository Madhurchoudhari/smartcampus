<?php
require_once 'vendor/autoload.php';
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use PDO;

include '/xampp/htdocs/smartcampus/dbconnection.php';


try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


$query = "SELECT admin_emailid FROM admin"; 
$stmt = $pdo->prepare($query);
$stmt->execute();
$adminEmails = $stmt->fetchAll(PDO::FETCH_COLUMN); 
if (empty($adminEmails)) {
    die('No admin emails found.');
}

// SMTP transport configuration
$transport = Transport::fromDsn('smtp://madhurchoudhari10@gmail.com:nkjmmkmtupcggxrh@smtp.gmail.com:587?encryption=tls');
$mailer = new Mailer($transport);

// Get customer email from the form
$customerEmail = $_POST['email'];
$adminEmail = 'madhurchoudhari10@gmail.com';  

// Create the admin email message
$email = (new Email())
    ->from('madhurchoudhari10@gmail.com')
    ->to(...$adminEmails)  
    ->subject('Mail from Smartcampus')
    ->text("Name: " . $_POST['name'] . "\nvisiter email: " . $_POST['email'] . "\nSubject: " . $_POST['subject'] . "\nMessage: " . $_POST['messages']);


$customerEmailMessage = (new Email())
    ->from($adminEmail)
    ->to($customerEmail)
    ->subject('Thank you for contacting us')
    ->text("Dear " . $_POST['name'] . ",\n\nThank you for reaching out! We have received your message and will get back to you soon.\n\nBest regards,\nSmartcampus");


try {
    $mailer->send($email);
    $mailer->send($customerEmailMessage);
    echo 'Mail sent successfully!';
} catch (Exception $e) {
    echo 'Failed to send email: ' . $e->getMessage();
}

// Redirect to a confirmation page
header('Location: sent.html');
exit();
?>
