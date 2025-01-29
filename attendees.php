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

$event->id = $event_id;

$attendee->event_id = $event_id;

$attendees = $attendee->attendees();

if (!$event->readOne()->fetch(PDO::FETCH_ASSOC)) {
    die('Event not found');
}
?>

<?php include_once 'partials/header.php'; ?>
    <div class="flex flex-col sm:flex-row gap-4">
        <?php include_once 'partials/sidebar.php'; ?>

        <div class="border rounded border-gray-100 p-4 sm:w-3/4">
            <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                <h1 class="text-2xl font-semibold">Events</h1>
                <div class="space-x-2">
                    <a
                            href="export_attendees.php?event_id=<?php echo $event_id; ?>"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Export to CSV
                    </a>
                    <a href="/" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border border-gray-100">#</th>
                            <th class="px-4 py-2 border border-gray-100">Name</th>
                            <th class="px-4 py-2 border border-gray-100">Email</th>
                            <th class="px-4 py-2 border border-gray-100">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $attendees->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr>
                            <td class="border border-gray-100 px-4 py-2"><?php echo $row['id']; ?></td>
                            <td class="border border-gray-100 px-4 py-2"><?php echo $row['name']; ?></td>
                            <td class="border border-gray-100 px-4 py-2"><?php echo $row['email']; ?></td>
                            <td class="border border-gray-100 px-4 py-2"><?php echo $row['phone']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php include_once 'partials/footer.php'; ?>