<?php

session_start();
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

include_once 'classes/Database.php';
include_once 'classes/Event.php';
include_once 'classes/Attendee.php';

$database = new Database();
$db = $database->getConnection();

$event = new Event($db);
$attendee = new Attendee($db);

if (!isset($_POST['event_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Event id is required']);
}

$event->id = htmlspecialchars(strip_tags($_POST['event_id']));
$attendee->event_id = htmlspecialchars(strip_tags($_POST['event_id']));

$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$phone = trim($_POST["phone"]);

if (empty($name)) {
    $errors['name'] = "Name is required";
}

if (empty($email)) {
    $errors['email'] = "Email is required";
}

if (empty($phone)) {
    $errors['phone'] = "Phone is required";
}

if (empty($errors)) {
    $attendee->name = $name;
    $attendee->email = $email;
    $attendee->phone = $phone;

    if ($attendee->emailExists()) {
        $errors['email'] = "You are already registered";
    }

    if ($event->user_limit > $attendee->attendees()->rowCount()) {
        $errors['email'] = "User limit exceeded";
    }

    if (!empty($errors)) {
        http_response_code(422);
        echo json_encode(['status' => 'error', 'errors' => $errors]);
        exit;
    }

    if ($attendee->create()) {
        echo json_encode([
            'status' => 'success', 'message' => 'Registration successful! Thanks for being with us.'
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error adding attendee']);
    }
} else {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'errors' => $errors]);
}


