<?php

session_start();
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

include_once 'classes/Database.php';
include_once 'classes/Event.php';

$database = new Database();
$db = $database->getConnection();

$event = new Event($db);

if (!isset($_GET["id"])) {
    exit();
}

$event->id = htmlspecialchars(strip_tags($_GET["id"]));

if ($event->delete()) {
    header("location: index.php");
} else {
    echo "Something went wrong";
}
