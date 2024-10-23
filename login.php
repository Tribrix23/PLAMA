<?php
$database_host = 'localhost';
$database_user = 'root';
$database_password = '';
$database_name = 'us';

$conn = new mysqli($database_host, $database_user, $database_password, $database_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        echo "Login Success";
    } else {
        echo "Invalid email or password.";
    }
    $stmt->close();
}

$conn->close();
?>
