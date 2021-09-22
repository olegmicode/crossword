<?php
session_start();

use App\Auth\Util;
require_once __DIR__.'/../vendor/autoload.php';

$util = new Util();

$_SESSION["user_id"] = "";
unset($_SESSION["user_id"]);
session_destroy();

$util->clearAuthCookie();
$util->redirect("./");