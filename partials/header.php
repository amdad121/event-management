<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Event Management</title>
    <link rel="stylesheet" href=".././assets/css/tailwind.css"/>
</head>
<body>
<div class="container mx-auto space-y-4">
    <header>
        <nav class="flex items-center justify-between bg-gray-800 p-3 rounded-b">
            <a class="text-white" href="/">Event Management</a>
            <ul class="flex">
                <li>
                    <form class="relative" method="get" action="../search.php">
                        <label>
                            <input class="w-36 sm:w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-100 pr-16" type="search" name="q" placeholder="Search here" value='<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';?>'>
                        </label>
                        <button class="absolute right-2 top-2 sm:top-1.5 text-gray-100" type="submit">Search</button>
                    </form>
                </li>
                <!--<li>-->
                <!--    <a class="text-white hover:bg-gray-700 rounded px-4 py-2 transition-all duration-100" href="/">Events</a>-->
                <!--</li>-->
                <!--<li>-->
                <!--    <a class="text-white hover:bg-gray-700 rounded px-4 py-2 transition-all duration-100"-->
                <!--       href="../logout.php">Logout</a>-->
                <!--</li>-->
            </ul>
        </nav>
    </header>
    <main class="my-4">