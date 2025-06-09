<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('database.php');

if ($db) {
    echo "DB connection successful!";
} else {
    echo "DB connection failed!";
}
