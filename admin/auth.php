<?php

session_start();

use App\Auth\Util;
require_once __DIR__.'/../vendor/autoload.php';

$util = new Util();

$util->checkAuth();