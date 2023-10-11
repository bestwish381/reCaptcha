<?php

require_once('config.php');

$host = Config::MYSQL_HOST;
$dbUser = Config::MYSQL_USERNAME;
$dbpassword = Config::MYSQL_PASSWORD;
$dbName = Config::MYSQL_DB_NAME;

$conn = mysqli_connect($host, $dbUser, $dbpassword, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    domain VARCHAR(50) NOT NULL,
    hashvalue VARCHAR(32) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$result = mysqli_query($conn, $sql);

session_start();
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $domain = $_POST['domain'];
    $hashvalue = md5($email);

    // Check if email already exists in table
    $sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Username or email already exists";
    } else {
        $sql = "INSERT INTO users (username, email, domain, hashvalue) VALUES ('$username', '$email', '$domain', '$hashvalue')";
        if (mysqli_query($conn, $sql)) {
            // Registration successful
            $_SESSION['success'] = "Registration successful";
        } else {
            // Registration failed
            $_SESSION['error'] = "Registration failed: " . mysqli_error($conn);
        }
    }
    mysqli_close($conn);
}
?>

<html>

<head>
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container content">
        <div class="col-sm-12 col-md-6">
            <h2>Create User</h2>
            <?php
            // Show success or error message if set in session variable
            if (isset($_SESSION['success'])) {
                echo "<p style='color: green'>" . $_SESSION['success'] . "</p>";
                unset($_SESSION['success']);
            }

            if (isset($_SESSION['error'])) {
                echo "<p style='color: red'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
            ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">User Name:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="domain">Domain Name:</label>
                    <input type="text" class="form-control" id="domain" name="domain" required>
                </div>
                <button type="submit" class="btn btn-primary" name="register">Submit</button>
            </form>
        </div>
    </div>
</body>

<style>
    .content {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 70vh;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
</style>

</html>