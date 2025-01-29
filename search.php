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
$attendees = new Attendee($db);

$search_term = '';
$search_results = ['events' => [], 'attendees' => []];

if (empty($_GET['q'])) {
    header("Location: index.php");
}

$search_term = $_GET['q'];

// Search for events
$event_stmt = $db->prepare("SELECT * FROM events WHERE name LIKE :search_term OR description LIKE :search_term");
$event_stmt->bindValue(':search_term', '%'.$search_term.'%');
$event_stmt->execute();

while ($row = $event_stmt->fetch(PDO::FETCH_ASSOC)) {
    $search_results['events'][] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'description' => $row['description'],
        'date_time' => $row['date_time']
    ];
}

// Search for attendees
$attendee_stmt = $db->prepare("SELECT a.id, a.name, a.email, e.name AS event_name, e.date_time AS event_date_time 
                                   FROM attendees a 
                                   JOIN events e ON a.event_id = e.id 
                                   WHERE a.name LIKE :search_term OR a.email LIKE :search_term");
$attendee_stmt->bindValue(':search_term', '%'.$search_term.'%');
$attendee_stmt->execute();

while ($row = $attendee_stmt->fetch(PDO::FETCH_ASSOC)) {
    $search_results['attendees'][] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'email' => $row['email'],
        'event_name' => $row['event_name'],
        'event_date_time' => $row['event_date_time']
    ];
}
?>

<?php include_once 'partials/header.php'; ?>
    <div class="flex flex-col sm:flex-row gap-4">
        <?php include_once 'partials/sidebar.php'; ?>

        <div class="border rounded border-gray-100 p-4 sm:w-3/4">
            <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                <h1 class="text-2xl font-semibold">Search</h1>
                <a
                        href="/"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
                >
                    Back
                </a>
            </div>
            <div class="space-y-4">
                <?php if ($search_term && empty($search_results['events']) && empty($search_results['attendees'])): ?>
                    <p>No results found for "<?php echo htmlspecialchars($search_term); ?>"</p>
                <?php else: ?>

                    <!-- Events Search Results -->
                    <?php if (!empty($search_results['events'])): ?>
                        <h2 class="text-2xl font-semibold mb-2">Events</h2>
                        <ul>
                            <?php foreach ($search_results['events'] as $event): ?>
                                <li>
                                    <strong><?php echo htmlspecialchars($event['name']); ?></strong>
                                    (<?php echo htmlspecialchars($event['date_time']); ?>):
                                    <?php echo htmlspecialchars($event['description']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <!-- Attendees Search Results -->
                    <?php if (!empty($search_results['attendees'])): ?>
                        <h2 class="text-2xl font-semibold mb-2">Attendees</h2>
                        <ul>
                            <?php foreach ($search_results['attendees'] as $attendee): ?>
                                <li>
                                    <?php echo htmlspecialchars($attendee['name']); ?>
                                    (<?php echo htmlspecialchars($attendee['email']); ?>)
                                    - Event: <?php echo htmlspecialchars($attendee['event_name']); ?>
                                    (<?php echo htmlspecialchars($attendee['event_date_time']); ?>)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
        </div>
    </div>
<?php include_once 'partials/footer.php'; ?>
