<?php

use App\Config\Settings;

require_once __DIR__ . '/../../../vendor/autoload.php';

class Database
{
    public function getConnection()
    {
        return Settings::getConnection('sudoku');
    }
}