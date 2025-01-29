<div class="border border-gray-100 rounded bg-gray-600 sm:w-1/4">
    <ul class="flex flex-col gap-px py-2">
        <li>
            <a class="text-white block hover:bg-gray-800 rounded px-4 py-2 transition-all duration-100 <?php echo ($_SERVER['PHP_SELF'] == '/' || basename($_SERVER['PHP_SELF'])) == 'index' ?'bg-gray-800' : ''?>" href="/"
            >Events</a
            >
        </li>
        <li>
            <a class="text-white block hover:bg-gray-800 rounded px-4 py-2 transition-all duration-100 <?php echo basename($_SERVER['PHP_SELF']) == 'logout' ?'bg-gray-800' : ''?>" href="../logout.php">Logout</a>
        </li>
    </ul>
</div>