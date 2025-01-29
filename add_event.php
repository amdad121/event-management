<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include_once 'classes/Database.php';
include_once 'classes/Event.php';

$database = new Database();
$db = $database->getConnection();

$errors = [];

if ($_POST) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date_time = trim($_POST['date_time']);
    $user_limit = trim($_POST['user_limit']);

    if (empty($name)) {
        $errors['name'] = "Name is required";
    }

    if (empty($description)) {
        $errors['description'] = "Description is required";
    }

    if (empty($date_time)) {
        $errors['date_time'] = "Date is required";
    }

    if (empty($user_limit)) {
        $errors['user_limit'] = "User Limit is required";
    }

    if (empty($errors)) {
        $event = new Event($db);
        $event->name = $name;
        $event->description = $description;
        $event->date_time = $date_time;
        $event->user_limit = $user_limit;

        $event->user_id = $_SESSION['user_id'];

        if ($event->create()) {
            $_SESSION['success'] = 'Event successfully added.';
            header("Location: index.php");
        } else {
            echo "something went wrong";
        }
    }
}
?>

<?php include_once 'partials/header.php'; ?>
    <div class="flex flex-col sm:flex-row gap-4">
        <?php include_once 'partials/sidebar.php'; ?>
        <div class="border rounded border-gray-100 p-4 sm:w-3/4">
            <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                <h1 class="text-2xl font-semibold">Create Event</h1>
                <a href="/" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back
                </a>
            </div>
            <div>
                <form class="space-y-4" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="name">Name</label>
                        <input class="mt-1 block w-full max-w-sm px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                        <?php if (isset($errors['name'])): ?>
                            <p class="text-red-600"><?php echo $errors['name']; ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="description">Description</label>
                        <textarea class="mt-1 block w-full max-w-sm px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="description" id="description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <p class="text-red-600"><?php echo $errors['description']; ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="date_time">Date</label>
                        <input class="mt-1 block w-full max-w-sm px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="datetime-local" name="date_time" id="date_time" value="<?php echo isset($_POST['date_time']) ? htmlspecialchars($_POST['date_time']) : ''; ?>" required>
                        <?php if (isset($errors['date_time'])): ?>
                            <p class="text-red-600"><?php echo $errors['date_time']; ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="user_limit">User Limit</label>
                        <input class="mt-1 block w-full max-w-sm px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="number" name="user_limit" id="user_limit" value="<?php echo isset($_POST['user_limit']) ? htmlspecialchars($_POST['user_limit']) : ''; ?>" required>
                        <?php if (isset($errors['user_limit'])): ?>
                            <p class="text-red-600"><?php echo $errors['user_limit']; ?></p>
                        <?php endif; ?>
                    </div>
                    <br>
                    <button class="w-full max-w-sm flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer" type="submit">Add Event</button>
                </form>
            </div>
        </div>
    </div>
<?php include_once 'partials/footer.php'; ?>
