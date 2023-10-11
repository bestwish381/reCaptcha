<?php
require_once('config.php');
// Retrieve email from POST request
$email = $_POST['email'];
$host = Config::MYSQL_HOST;
$dbUser = Config::MYSQL_USERNAME;
$dbpassword = Config::MYSQL_PASSWORD;
$dbName = Config::MYSQL_DB_NAME;
// Check if email is valid
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Invalid email address, return error response
    $response = array('error' => 'Invalid email address.');
    echo json_encode($response);
    exit;
}

// Check if email exists in database
$conn = mysqli_connect($host, $dbUser, $dbpassword, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email already exists in database, return success response with exists flag set to true
    $response = array('exists' => true);
    echo json_encode($response);
} else {
    // Email does not exist in database, return success response with exists flag set to false
    $response = array('exists' => false);
    echo json_encode($response);
}