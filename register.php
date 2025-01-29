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
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $passwordConfirmation = trim($_POST['passwordConfirmation']);

    if (empty($username)) {
        $errors['username'] = "Username is required";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters";
    } elseif (strlen($password) > 20) {
        $errors['password'] = "Password must be less than 20 characters";
    }

    if ($password != $passwordConfirmation) {
        $errors['password'] = "Passwords do not match";
    }

    if (empty($errors)) {
        $user = new User($db);
        $user->email = $email;

        if ($user->emailExists()) {
            $errors['email'] = "Email already exists";
        } else {
            $user->username = $username;
            $user->password = $password;

            if ($user->register()) {
                $_SESSION['success'] = 'Your are successfully registered. Please login';
                header("location: login.php");
            } else {
                echo "Something went wrong";
            }
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
            <h2 class="text-2xl font-semibold mb-4 text-center">Registration</h2>
            <form class="space-y-4" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="username">Username *</label>
                    <input class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="text" name="username" id="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    <?php if (isset($errors['username'])): ?>
                    <p class="text-red-600"><?php echo $errors['username']; ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="email">Email *</label>
                    <input class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="email" name="email" id="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <p class="text-red-600"><?php echo $errors['email']; ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="password">Password *</label>
                    <input class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="password" name="password" id="password" required>
                    <?php if (isset($errors['password'])): ?>
                        <p class="text-red-600"><?php echo $errors['password']; ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="passConfirmation">Password Confirmation *</label>
                    <input class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" type="password" name="passwordConfirmation" id="passConfirmation" required>
                    <?php if (isset($errors['passwordConfirmation'])): ?>
                        <p class="text-red-600"><?php echo $errors['passwordConfirmation']; ?></p>
                    <?php endif; ?>
                </div>
                <button class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer" type="submit">Register</button>
            </form>
            <div class="mt-4">Already have an account? <a href="login.php" class="hover:underline">Login here</a>.</div>
        </div>
    </div>
</div>
</body>
</html>
