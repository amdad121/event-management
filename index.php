<?php

session_start();
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

include_once 'classes/Database.php';
// include_once 'classes/Event.php';
// include_once 'classes/Attendee.php';

$database = new Database();
$db = $database->getConnection();

// $event = new Event($db);
// $attendee = new Attendee($db);

// Pagination variables
$limit = 2;  // Number of events per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Sorting variables
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'created_at';  // Default sorting by date
$sort_order = isset($_GET['sort_order']) && $_GET['sort_order'] === 'asc' ? 'ASC' : 'DESC';  // Default order is DESC

// Filtering variables
$filter_term = isset($_GET['filter']) ? $_GET['filter'] : '';

// Fetch total event count for pagination
$total_event_stmt = $db->prepare("SELECT COUNT(*) AS total FROM events WHERE name LIKE :filter_term");
$total_event_stmt->bindValue(':filter_term', '%' . $filter_term . '%');
$total_event_stmt->execute();
$total_events = $total_event_stmt->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_events / $limit);

// Fetch filtered, sorted, and paginated events
$event_stmt = $db->prepare("SELECT * FROM events WHERE name LIKE :filter_term ORDER BY $sort_by $sort_order LIMIT :limit OFFSET :offset");
$event_stmt->bindValue(':filter_term', '%' . $filter_term . '%');
$event_stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$event_stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$event_stmt->execute();
$events = $event_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include_once 'partials/header.php'; ?>
    <div class="flex flex-col sm:flex-row gap-4">
        <?php include_once 'partials/sidebar.php'; ?>

        <div class="border rounded border-gray-100 p-4 sm:w-3/4">
            <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                <h1 class="text-2xl font-semibold">Events</h1>
                <a
                        href="add_event.php"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Create Event
                </a>
            </div>
            <div>
                <?php if (isset($_SESSION['success'])) : ?>
                    <div class="py-2 px-4 bg-green-200 rounded text-green-800 mb-4"><?php echo $_SESSION['success']; ?></div>
                <?php endif; unset($_SESSION['success']); ?>
                <div>
                    <div class="flex justify-between items-center flex-col sm:flex-row gap-2 mb-4">
                        <!-- Filter -->
                        <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <label>
                                <input class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="text" name="filter" placeholder="Filter by event name" value="<?php echo htmlspecialchars($filter_term); ?>">
                            </label>
                            <button class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer" type="submit">Filter</button>
                        </form>

                        <!-- Sort -->
                        <form method="GET" action="">
                            <input type="hidden" name="filter" value="<?php echo htmlspecialchars($filter_term); ?>">
                            <label for="sort_by">Sort by:</label>
                            <select class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="sort_by" id="sort_by" onchange="this.form.submit()">
                                <option value="name" <?php if ($sort_by == 'name') echo 'selected'; ?>>Name</option>
                                <option value="date_time" <?php if ($sort_by == 'date_time') echo 'selected'; ?>>Date</option>
                                <option value="created_at" <?php if ($sort_by == 'created_at') echo 'selected'; ?>>Created At</option>
                            </select>
                            <label>
                                <select class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="sort_order" id="sort_order" onchange="this.form.submit()">
                                    <option value="asc" <?php if ($sort_order == 'ASC') echo 'selected'; ?>>Ascending</option>
                                    <option value="desc" <?php if ($sort_order == 'DESC') echo 'selected'; ?>>Descending</option>
                                </select>
                            </label>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                       <table class="table-auto w-full">
                           <thead>
                               <tr>
                                   <th class="px-4 py-2 border border-gray-100">Name</th>
                                   <th class="px-4 py-2 border border-gray-100">Description</th>
                                   <th class="px-4 py-2 border border-gray-100">Date</th>
                                   <th class="px-4 py-2 border border-gray-100">User Limit</th>
                                   <th class="px-4 py-2 border border-gray-100">Registration Link</th>
                                   <th class="px-4 py-2 border border-gray-100">Attendees</th>
                                   <th class="px-4 py-2 border border-gray-100">Action</th>
                               </tr>
                           </thead>
                           <tbody>
                               <?php foreach ($events as $event): ?>
                                   <tr>
                                       <td class="border border-gray-100 px-4 py-2"><?php echo $event['name']; ?></td>
                                       <td class="border border-gray-100 px-4 py-2"><?php echo $event['description']; ?></td>
                                       <td class="border border-gray-100 px-4 py-2"><?php echo $event['date_time']; ?></td>
                                       <td class="border border-gray-100 px-4 py-2"><?php echo $event['user_limit']; ?></td>
                                       <td class="border border-gray-100 px-4 py-2">
                                           <a class="hover:underline" href="event_registration.php?event_id=<?php echo $event['id']; ?>" target="_blank" rel="noopener noreferrer">
                                               Form
                                               <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 inline-flex stroke-current">
                                                   <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                               </svg>
                                           </a>
                                       </td>
                                       <td class="border border-gray-100 px-4 py-2">
                                           <a class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-block" href="attendees.php?event_id=<?php echo $event['id']; ?>">Attendees</a>
                                       </td>
                                       <td class="border border-gray-100 px-4 py-2">
                                           <div class="flex gap-2 items-center justify-center">
                                               <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block" href="edit_event.php?id=<?php echo $event['id']; ?>">Edit</a>
                                               <a class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-block" href="delete_event.php?id=<?php echo $event['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                                           </div>
                                       </td>
                                   </tr>
                               <?php endforeach; ?>
                           </tbody>
                       </table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-center mt-4 gap-px">
                        <?php if ($total_pages > 1): ?>
                            <?php if ($page > 1): ?>
                                <a class="px-2 py-1 inline-block border rounded border-gray-100 hover:bg-gray-100" href="?page=<?php echo $page - 1; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>&filter=<?php echo urlencode($filter_term); ?>">Previous</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a class="px-2 py-1 inline-block border rounded border-gray-100 hover:bg-gray-100" href="?page=<?php echo $i; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>&filter=<?php echo urlencode($filter_term); ?>"><?php echo $i; ?></a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a class="px-2 py-1 inline-block border rounded border-gray-100 hover:bg-gray-100" href="?page=<?php echo $page + 1; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>&filter=<?php echo urlencode($filter_term); ?>">Next</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include_once 'partials/footer.php'; ?>