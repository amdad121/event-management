<?php

include_once 'classes/Database.php';
include_once 'classes/Event.php';
include_once 'classes/Attendee.php';

$database = new Database();
$db = $database->getConnection();

$event = new Event($db);
$attendee = new Attendee($db);

$event_data = [];

if (!isset($_GET["event_id"])) {
    die('Event not found');
}

$event->id = htmlspecialchars(strip_tags($_GET["event_id"]));

$attendee->event_id = $event->id;

$attendee_counts = $attendee->attendees()->rowCount();

if ($row = $event->readOne()->fetch(PDO::FETCH_ASSOC)) {
    $event_data = $row;
} else {
    die('Event not found');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Event Management</title>
    <link rel="stylesheet" href="./assets/css/tailwind.css"/>
</head>
<body>
    <div class="max-w-md mx-auto">
        <div class="border border-gray-100 p-4 bg-white rounded mt-10">
            <div class="border-b border-b-gray-100 pb-2 mb-4">
                <h2 class="text-2xl font-semibold"><?php echo $event_data['name']; ?> Event Registration</h2>
                <p><?php echo $event_data['date_time']; ?></p>
            </div>
           <div>
               <?php if ($event_data['user_limit'] > $attendee_counts) : ?>
                   <form class="space-y-4" id="add_attendee" method="POST" action="add_attendee.php">
                       <input type="hidden" name="event_id" value="<?php echo $event_data['id']; ?>">
                       <div>
                           <label class="block text-sm font-medium text-gray-700" for="name">Name</label>
                           <input class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="text" name="name" id="name">
                           <p class="error" style='display: none; color: red;'></p>
                       </div>
                       <div>
                           <label class="block text-sm font-medium text-gray-700" for="email">Email</label>
                           <input class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="email" name="email" id="email">
                           <p class="error" style='display: none; color: red;'></p>
                       </div>
                       <div>
                           <label class="block text-sm font-medium text-gray-700" for="phone">Phone</label>
                           <input class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="text" name="phone" id="phone">
                           <p class="error" style='display: none; color: red;'></p>
                       </div>
                       <button class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer" id="submit_button" type="submit">Submit</button>
                   </form>
                   <div id="success" style="display: none">
                       <h3 id="title"></h3>
                   </div>
               <?php else: ?>
                   <div>
                       <h2 id="title">Registration limit full</h2>
                   </div>
               <?php endif; ?>
           </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
