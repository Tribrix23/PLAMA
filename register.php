<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$host = 'smtp.gmail.com';
$email_username = 'your_email@gmail.com';
$email_password = 'your_app_password';
$database_host = 'aws-0-ap-southeast-1.pooler.supabase.com';
$database_user = 'postgres.wxrqvsfsczllgbfwkylx';
$database_password = 'Davidperez1234'; 
$database_name = 'postgres';
$database_port = '6543';

$conn_string = "host=$database_host port=$database_port dbname=$database_name user=$database_user password=$database_password";
$conn = pg_connect($conn_string);

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = pg_prepare($conn, "insert_user", "INSERT INTO users (email, username, password) VALUES ($1, $2, $3)");
    $result = pg_execute($conn, "insert_user", array($email, $username, $password));

    if ($result) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $email_username;
            $mail->Password = $email_password;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('zas@gmail.com', 'David');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Registration Successful';
            $mail->Body = "Thank you for registering!";

            $mail->send();
            echo "Registration successful! A confirmation email has been sent.";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error inserting user: " . pg_last_error($conn);
    }
}

pg_close($conn);
?>
