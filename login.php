<?php

session_start();
if (isset($_SESSION["user_id"])) {
    header("location: index.php");
    exit;
}

include_once './classes/Database.php';
include_once './classes/User.php';

$database = new Database();
$db = $database->getConnection();

$errors = [];

if ($_POST) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    }

    if (empty($errors)) {
        $user = new User($db);
        $user->email =$email;
        $user->password = $password;

        if ($user->login()) {
            session_start();
            $_SESSION['user_id'] = $user->id;
            header("Location: index.php");
            exit;
        } else {
            $errors['email'] = "Invalid login credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Event Management</title>
    <link rel="stylesheet" href="./assets/css/tailwind.css" />
</head>
<body>
    <div>
        <div class="flex items-center justify-center h-screen">
            <div class="max-w-md w-full p-4 border border-gray-100 rounded">
                <h1 class="text-center font-semibold text-3xl mb-5">Event Management</h1>
                <h2 class="text-2xl font-semibold mb-4 text-center">Login</h2>
                <?php if (isset($_SESSION['success'])) : ?>
                    <div class="py-2 px-4 bg-green-200 rounded text-green-800 mb-4"><?php echo $_SESSION['success']; ?></div>
                <?php endif; unset($_SESSION['success']); ?>
                <form class="space-y-4" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="email">Email</label>
                        <input class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="email" name="email" id="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <p class="text-red-600"><?php echo $errors['email']; ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="password">Password</label>
                        <input class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="password" name="password" id="password" required>
                        <?php if (isset($errors['password'])): ?>
                            <p class="text-red-600"><?php echo $errors['password']; ?></p>
                        <?php endif; ?>
                    </div>
                    <button class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer" type="submit">Login</button>
                </form>
                <div class="mt-4">Don't have an account? <a href="register.php" class="hover:underline">Register here</a>.</div>
            </div>
        </div>
    </div>
</body>
</html>