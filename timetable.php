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
            grid-template-columns: 120px auto; /* Adjust column widths */
            grid-gap: 20px;
        }

        #timetable {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-gap: 10px;
        }

        .times {
            display: grid;
            grid-template-rows: repeat(9, 1fr); /* Adjust row heights */
            grid-gap: 2px; /* Add gap between time slots */
            margin-top: 30px; /* Add margin to align with timetable */
        }

        .time {
            border-bottom: 1px solid #ccc;
            padding: 5px 10px;
            background-color: #fff;
            text-align: right; /* Align time text to the right */
        }

        .day {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .day h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 18px;
            color: #333;
        }

        .slot {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
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
        <div class="times">
            <?php
            // Define array for hours from 9 AM to 5 PM
            $hours = array('9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM');

            // Display the hours on the y-axis
            foreach ($hours as $hour) {
                echo "<div class='time'>$hour</div>";
            }
            ?>
        </div>
        <div id="timetable">
            <?php
            // Define days of the week
            $daysOfWeek = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');

            // Loop through days of the week
            foreach ($daysOfWeek as $day) {
                // Filter timetable entries for the current day
                $dayEntries = array_filter($timetableEntries, function($entry) use ($day) {
                    return $entry['day'] === $day;
                });

                // Display timetable entries for the current day
                echo "<div class='day'>";
                echo "<h3>$day</h3>";

                // Loop through hours and display timetable slots
                foreach ($hours as $hour) {
                    // Check if there is an entry for the current hour and day
                    $entryFound = false;
                    foreach ($dayEntries as $entry) {
                        if ($entry['time'] === $hour) {
                            echo "<div class='slot'>" . $entry['activity'] . "</div>";
                            $entryFound = true;
                            break;
                        }
                    }
                    // If no entry found, display an empty slot
                    if (!$entryFound) {
                        echo "<div class='slot'></div>";
                    }
                }
                echo "</div>"; // Close day
            }
            ?>
        </div>
    </div>
</body>
</html>
