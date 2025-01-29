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

$event_id = $_GET["event_id"];

if (!isset($event_id)) {
    header("location: index.php");
}

$attendee->event_id = $event_id;
$attendees = $attendee->attendees();

$event->id = $event_id;
$event_details = $event->readOne()->fetch(PDO::FETCH_ASSOC);

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="attendees_event_'.$event_id.'.csv"');

// Open output stream for writing the CSV data
$output = fopen('php://output', 'w');

// Write CSV headers
fputcsv($output, ['Event Name', 'Date Time', 'Attendee Name', 'Attendee Email', 'Attendee Phone'], ",",
    "\"", "\\", "\n");

// Write event details and attendees data to CSV
while ($row = $attendees->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $event_details['name'], $event_details['date_time'], $row['name'], $row['email'],
        $row['phone']
    ], ",", "\"", "\\", "\n");
}

// Close output stream
fclose($output);
exit;