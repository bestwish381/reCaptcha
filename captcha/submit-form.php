<?php
require_once('Config.php');
$captcha = $_POST['g-recaptcha-response'];
if (!empty($captcha)) {
    $secretKey = Config::secretKey;
    $ip = $_SERVER['REMOTE_ADDR'];
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $ip);
    $responseKeys = json_decode($response, true);
    if (intval($responseKeys["success"]) != 1) {
        echo '<p>Please verify that you are not a robot.</p>';
    } else {
        header("Location: puzzle.php?e=" . urlencode($_POST['email'])); 
        exit();
    }
} else {
    echo '<p>Please verify that you are not a robot.</p>';
}
?>