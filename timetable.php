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
            position: relative;
            border: 1px solid #ccc;
            padding: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            overflow: hidden; /* Hide overflow text */
            white-space: nowrap; /* Prevent text wrapping */
            text-overflow: ellipsis; /* Display ellipsis for overflowing text */
            cursor: pointer; /* Add cursor pointer */
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
            z-index: 9999;
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
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
    <button id="alterTimetableBtn"><a href="Modify.php" style="text-decoration: none; color: inherit;">Alter Timetable</a></button>
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
                        echo "<div class='slot' data-student-id='" . $userInfo['student_id'] . "' data-username='" . $username . "' data-lab-title='" . $entry['activity'] . "' data-day='" . $day . "' data-time='" . $hour . "'>
                                <span class='edit-btn'>Edit</span>
                                " . $entry['activity'] . "
                            </div>";
                        $found = true;
                        break;
                    }
                }
                // If no entry found, display an empty slot
                if (!$found) {
                    echo "<div class='slot' data-student-id='" . $userInfo['student_id'] . "' data-username='" . $username . "' data-day='" . $day . "' data-time='" . $hour . "'>
                            <span class='edit-btn'>Edit</span>
                        </div>";
                }
            }
        }
        ?>
    </div>
</div>

<!-- Modal for editing lab activity -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <form id="editForm">
            <input type="hidden" id="editStudentId" name="student_id">
            <input type="hidden" id="editUsername" name="username">
            <input type="hidden" id="editDay" name="day">
            <input type="hidden" id="editTime" name="time">
            <label for="editLabTitle">New Lab Title:</label>
            <input type="text" id="editLabTitle" name="lab_title">
            <button type="submit">Save</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.edit-btn').click(function () {
            var slot = $(this).parent('.slot');
            $('#editStudentId').val(slot.data('student-id'));
            $('#editUsername').val(slot.data('username'));
            $('#editDay').val(slot.data('day'));
            $('#editTime').val(slot.data('time'));
            $('#editLabTitle').val(slot.data('lab-title'));
            $('#editModal').fadeIn();
        });

        $('#editForm').submit(function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: 'update_timetable.php', // Create this PHP file to handle the update
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Close modal and refresh timetable
                        $('#editModal').fadeOut();
                        window.location.reload();
                    } else {
                        // Handle error
                        alert('Error: ' + response.message);
                    }
                },
                error: function () {
                    // Handle error
                    alert('Error: Unable to update timetable');
                }
            });
        });

       // $('.modal').click(function () {
 //           $(this).fadeOut();
 //       });
    });
</script>
</body>
</html>
