<?php
session_start();

use Dotenv\Dotenv;
use App\Auth\Util;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$util = new Util();

$ALLOWED_EMAILS = explode(',', $_ENV['ALLOWED_EMAILS']);

$errorMessage = "";

$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URL']);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (empty($token['access_token'])) {
        $util->redirect('./');
    }

    $client->setAccessToken($token['access_token']);

    $current_time = time();
    $current_date = date("Y-m-d H:i:s", $current_time);

    $cookie_expiration_time = $current_time + (30 * 24 * 60 * 60);

    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;
    $id = $google_account_info->id;

    $isAllowed = false;
    foreach($ALLOWED_EMAILS as $allowed_email){
        if($allowed_email == $email){
            $isAllowed = true;
        }
    }
    if($isAllowed){
        $_SESSION["user_id"] = $id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;

        $util->redirect("dashboard.php");
    }else {
        $errorMessage = $email . " not allowed !";
    }

}

if(!isset($_GET['code']) || $errorMessage != "") {

    ?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>SeniorsDiscountClub Games - Admin Panel</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
              integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
              crossorigin="anonymous">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

        <style type="text/css">
            html,
            body {
                height: 100%;
            }

            body {
                display: -ms-flexbox;
                display: -webkit-box;
                display: flex;
                -ms-flex-align: center;
                -ms-flex-pack: center;
                -webkit-box-align: center;
                align-items: center;
                -webkit-box-pack: center;
                justify-content: center;
                padding-top: 40px;
                padding-bottom: 40px;
            }

            .form-signin {
                width: 100%;
                max-width: 330px;
                padding: 15px;
                margin: 0 auto;
            }
            #error-message {
                font-size: 12px;
                color: red;
            }
        </style>
    </head>

    <body class="text-center">
    <form class="form-signin">
        <img class="mb-4" src="./assets/img/logo.svg" alt="" height="50">
        <h1 class="h5 font-weight-normal">&nbsp;</h1>
        <a href="<?= $client->createAuthUrl() ?>">
            <img src="./assets/img/btn_google_signin.png" alt="Sign in with Google" title="Sign in with Google"/>
        </a>
        <span id="error-message"><?= $errorMessage ?></span>
        <p class="mt-5 mb-3 text-muted">&copy; SeniorsDiscountClub <?= date('Y') ?></p>
    </form>
    </body>
    </html>

    <?php
}
?>