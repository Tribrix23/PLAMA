<?php
$database_host = 'aws-0-ap-southeast-1.pooler.supabase.com';
$database_user = 'postgres.wxrqvsfsczllgbfwkylx';
$database_password = '[YOUR-PASSWORD]';
$database_name = 'postgres';
$database_port = '6543';

$conn_string = "host=$database_host port=$database_port dbname=$database_name user=$database_user password=$database_password";
$conn = pg_connect($conn_string);

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = pg_prepare($conn, "get_user", "SELECT password FROM users WHERE email = $1");
    $result = pg_execute($conn, "get_user", array($email));

    if ($result && pg_num_rows($result) > 0) {
        $row = pg_fetch_assoc($result);
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            echo "Login Success";
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Invalid email or password.";
    }
}

pg_close($conn);
?>
