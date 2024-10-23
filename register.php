<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$host = 'smtp.gmail.com';
$username = 'your_email@gmail.com';
$password = 'your_app_password';
$database_host = 'localhost';
$database_user = 'root';
$database_pass = '';
$database_name = 'us';

$conn = new mysqli($database_host, $database_user, $database_pass, $database_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $otp = rand(100000, 999999);

    $stmt = $conn->prepare("INSERT INTO users (email, username, password, otp) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $email, $username, $password, $otp);
    $stmt->execute();
    $stmt->close();

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $username;
        $mail->Password = $password;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('zas@gmail.com', 'David');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP code is: <strong>$otp</strong>";

        $mail->send();
        echo "OTP has been sent to your email.";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

$conn->close();
?>
