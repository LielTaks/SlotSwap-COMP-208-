<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Fetch user information including student ID from the database
$username = $_SESSION['username'];
$sql = "SELECT id, student_id FROM users WHERE username = :username";
$stmt = $db->prepare($sql);
$stmt->execute(array(':username' => $username));
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

// Set student ID in session
$_SESSION['student_id'] = $userInfo['student_id'];

// Fetch timetable entries for the logged-in user from the database
$sql = "SELECT * FROM timetable WHERE user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->execute(array(':user_id' => $userInfo['id']));
$timetableEntries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Timetable</title>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
    }

    #toolbar {
        background-color: #1976D2;
        color: white;
        padding: 10px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    #sidebar {
        background-color: #fff;
        width: 200px;
        height: 100vh;
        padding: 20px;
        float: right;
        box-shadow: -2px 0 4px rgba(0, 0, 0, 0.1);
    }

    #sidebar h2, #sidebar h3, #sidebar p {
        margin-bottom: 15px;
    }

    #content {
        padding: 20px;
        overflow: auto;
        display: grid;
        grid-template-columns: 120px repeat(5, 200px); /* Adjusted column widths */
        grid-gap: 20px;
    }

    #timetable {
        display: grid;
        grid-template-columns: 120px repeat(5, 200px); /* Adjusted column widths */
        grid-template-rows: 60px repeat(9, 1fr); /* 10 rows for 9:00 AM - 5:00 PM */
        grid-gap: 1px; /* Add gap between grid items */
    }

    .time, .day {
        border: 1px solid #ccc;
        padding: 10px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .day h3 {
        margin: 0;
        font-size: 18px;
        color: #333;
        text-align: center;
    }

    .slot {
        border: 1px solid #ccc;
        padding: 10px;
        background-color: #f9f9f9;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        overflow: hidden; /* Hide overflow text */
        white-space: nowrap; /* Prevent text wrapping */
        text-overflow: ellipsis; /* Display ellipsis for overflowing text */
    }
</style>
</head>
<body>
<div id="toolbar">
    <h1>Timetable</h1>
</div>

<div id="sidebar">
    <h2>Welcome, <?php echo $_SESSION['username']; ?></h2>
    <h3>ID: <?php echo $_SESSION['student_id']; ?></h3>
    <p>Welcome to SlotSwap! This website is designed to help students manage their labs and tutorials more effectively.</p>
</div>

<div id="content">
    <div id="timetable">
        <?php
        // Define days of the week
        $daysOfWeek = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');

        // Display day headers
        echo "<div class='time'></div>"; // Empty slot for spacing
        foreach ($daysOfWeek as $day) {
            echo "<div class='day'><h3>$day</h3></div>";
        }

        // Define array for hours from 9 AM to 5 PM
        $hours = array('9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM');

        // Loop through hours
        foreach ($hours as $hour) {
            // Display the hours on the y-axis
            echo "<div class='time'>$hour</div>";

            // Loop through days of the week
            foreach ($daysOfWeek as $day) {
                // Find timetable entry for the current day and hour
                $found = false;
                foreach ($timetableEntries as $entry) {
                    if ($entry['day'] === $day && $entry['time'] === $hour) {
                        echo "<div class='slot'>" . $entry['activity'] . "</div>";
                        $found = true;
                        break;
                    }
                }
                // If no entry found, display an empty slot
                if (!$found) {
                    echo "<div class='slot'></div>";
                }
            }
        }
        ?>
    </div>
</div>
</body>
</html>
