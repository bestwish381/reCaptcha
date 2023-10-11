<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<?php
require_once('Config.php');
$email = @$_GET['e'];
$curl = curl_init();

curl_setopt_array(
    $curl,
    array(
        CURLOPT_URL => Config::severUrl . "/validate.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => "email=$email",
    )
);

$response = curl_exec($curl);
curl_close($curl);

$result = json_decode($response, true);

if ($result['exists']) {
    // Email already exists in database
    if (isset($_POST['submit'])) {
        $captcha = $_POST['g-recaptcha-response'];
        if (!empty($captcha)) {
            $secretKey = Config::secretKey;
            $ip = $_SERVER['REMOTE_ADDR'];
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $captcha . "&remoteip=" . $ip);
            $responseKeys = json_decode($response, true);
            if (intval($responseKeys["success"]) !== 1) {
                echo '<p>Please verify that you are not a robot.</p>';
            } else {
                echo '<p>Thank you for submitting the form.</p>';
            }
        } else {
            echo '<p>Please verify that you are not a robot.</p>';
        }
    }
} else {
    // Email does not exist in database
    echo '<p>Invalid Email.</p>';
    exit;
}
?>
<div class="container content">
    <div class="col-sm-12 col-md-6">
        <form action="submit-form.php" method="POST">
            <label for="exampleFormControlInput1" class="form-label">Email address</label>
            <input type="email" class="form-control mb-3" id="email" name="email" placeholder="<?php echo $email; ?>"
                value="<?php echo $email; ?>" style="display:none">
            <input type="email" class="form-control mb-3" id="emil" name="email" placeholder="<?php echo $email; ?>"
                value="<?php echo $email; ?>" disabled>
            <div class="g-recaptcha my-4" data-sitekey="<?php echo Config::sitekey; ?>"></div>

            <button class="btn btn-primary" type="submit">Submit</button>
        </form>
    </div>
</div>

<style>
    .content {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 70vh;
    }
</style>