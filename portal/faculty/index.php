<?php
$path = $_SERVER['DOCUMENT_ROOT'] . '/main';

$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
$host = 'http://' . $_SERVER['HTTP_HOST'];
$hostPath = $host . "/main";
$node_modulesPath = $host . "/node_modules";

session_start();

if (!isset($_SESSION["FacultyId"])) {
    // User isn't logged in, redirect to the login page
    header("Location: ./login/");
    exit;
} else {
    $FacultyId = $_SESSION["FacultyId"];
}

date_default_timezone_set('Asia/Dhaka'); // Set the timezone to Bangladesh

require('../../conn.php');

$sql = "SELECT * FROM Faculty WHERE FacultyId = ?";
$params = array($FacultyId);

// Execute the query
$stmt = sqlsrv_query($conn, $sql, $params);
echo "<section class='body'>";
// Check if any records are found
if ($stmt !== false) {
    // Display Faculty information
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $name = $row["LastName"];
    }
} else {
    echo "Error executing query: " . print_r(sqlsrv_errors(), true);
}
echo "</section>";

// LOGOUT
if (isset($_GET['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to login page after logout
    header("Location: ./login/");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./images/logo.png">
    <title>SUB-Faculty Portal</title>
    <link rel="stylesheet" href="./style/index.css">
</head>

<body>
    <div id="sidebar">
        <div class="logo"><img src="./images/logo_medium.png" alt=""></div>
        <div class="menu-item" data-page="home" hx-get="home" hx-target="main">Home</div>
        <div class="menu-item" data-page="p2" hx-get="p2" hx-target="main">Page 2</div>
        <div class="menu-item" data-page="course_load" hx-get="course_load" hx-target="main">Course Load</div>
        <div class="menu-item" data-page="loaded_courses" hx-get="loaded_courses" hx-target="main">Loaded courses</div>
        <div class="menu-item" data-page="downloads" hx-get="downloads" hx-target="main">Downloads</div>
        <!-- More menu items... -->
    </div>

    <nav>
        <ul>
            <h3>Hello, <?php echo $name; ?></h3>
            <span>
                <?php
                $current_time = date("H"); // Get the current hour in 24-hour format

                if ($current_time >= 5 && $current_time < 12) {
                    echo "Good morning! <i class='fa-sharp fa-light fa-sun-bright'></i>";
                } elseif ($current_time >= 12 && $current_time < 18) {
                    echo "Good afternoon! <i class='fa-solid fa-sun'></i>";
                } elseif ($current_time >= 18 && $current_time < 22) {
                    echo "Good evening! <i class='fa-sharp fa-solid fa-sun-haze'></i>";
                } else {
                    echo "Good night! <i class='fa-solid fa-moon'></i>";
                }
                ?>
            </span>
        </ul>
        <a class="logout-button" href="?logout=true">Logout</a>
    </nav>

    <header>
        <?php require './profile/index.php'; ?>
        <div class="header_content">
            <h1>Lorem ipsum dolor sit amet header.</h1>
        </div>
    </header>

    <!-- Sidebar toggle button -->
    <i id="sidebar-toggle" class="fa-solid fa-chevron-down"></i>

    <main>
        <!-- Page content will be loaded here -->
    </main>


    <script src="<?php echo $node_modulesPath ?>/htmx.org/dist/htmx.min.js"></script>
    <script src="./script/index.js"></script>
    <script src="<?php echo $hostPath ?>/script/downloads.js"></script>
</body>

</html>