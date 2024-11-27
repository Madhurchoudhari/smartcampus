<?php
require_once 'vendor/autoload.php';

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;


$transport = Transport::fromDsn('smtp://madhurchoudhari10@gmail.com:nkjmmkmtupcggxrh@smtp.gmail.com:587?encryption=tls');

$mailer = new Mailer($transport);
$adminEmail = 'madhurchoudhari10@gmail.com';
$customerEmail = $_POST['email'];

$email = (new Email())
    ->from('madhurchoudhari10@gmail.com')  
    ->to('madhurchoudhari19@gmail.com')  
    ->subject('Mail from Smartcampus')
    ->text("Name: " . $_POST['name'] . "\nEmail: " . $_POST['emailaddress'] .  "\nmessage: " . $_POST['message']);
    

 $customerEmailMessage = (new Email())
    ->from($adminEmail)  
    ->to($customerEmail)  
    ->subject('Thank you for contacting us')
    ->text("Dear " . $_POST['name'] . ",\n\nThank you for reaching out! We have received your message and will get back to you soon.\n\nBest regards,\nSmartcampus");
            

try {
    $mailer->send($email);
    echo 'Mail sent successfully!';
} catch (Exception $e) {
    echo 'Failed to send email: ' . $e->getMessage();
}
header('Location: sent.html');
 
?>



