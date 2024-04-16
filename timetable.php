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

// Fetch activity data from the database
$sql = "SELECT DISTINCT activity FROM timetable WHERE user_id = :user_id";
$stmt = $db->prepare($sql);
$stmt->execute(array(':user_id' => $userInfo['id']));
$activities = $stmt->fetchAll(PDO::FETCH_COLUMN);
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

        /* Hide the edit button by default */
        .edit-btn {
            display: none;
        }

        /* Show the edit button on hover */
        .slot:hover .edit-btn {
        display: inline-block;
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
        // Define days of the week and hours
        $daysOfWeek = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
        $hours = array('9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM');

        // Display timetable grid
        echo "<div class='time'></div>"; // Empty slot for spacing
        foreach ($daysOfWeek as $day) {
            echo "<div class='day'><h3>$day</h3></div>";
        }

        // Loop through hours and days to display timetable slots
        foreach ($hours as $hour) {
            echo "<div class='time'>$hour</div>"; // Display the hours on the y-axis
            foreach ($daysOfWeek as $day) {
                $found = false;
                foreach ($timetableEntries as $entry) {
                    if ($entry['day'] === $day && $entry['time'] === $hour) {
                        // Display slot with activity and data attribute for available subjects
                        echo "<div class='slot with-text' data-student-id='" . $userInfo['student_id'] . "' data-username='" . $username . "' data-lab-title='" . $entry['activity'] . "' data-available-subjects='" . $entry['activity'] . "' data-day='" . $day . "' data-time='" . $hour . "'>
                                <span class='edit-btn'>Edit</span>
                                " . $entry['activity'] . "
                            </div>";
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    // Display empty slot
                    echo "<div class='slot without-text' data-student-id='" . $userInfo['student_id'] . "' data-username='" . $username . "' data-day='" . $day . "' data-time='" . $hour . "'>
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
            <select id="editLabTitle" name="lab_title">
                <!-- Options will be dynamically added by JavaScript -->
            </select>
            <button type="submit">Save</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Function to populate dropdown based on selected slot
        function populateDropdown(activity) {
            $('#editLabTitle').empty(); // Clear previous options
            var availableSubjects = $('.slot.with-text[data-lab-title="' + activity + '"]').data('available-subjects').split(',');
            availableSubjects.forEach(function (subject) {
                $('#editLabTitle').append('<option value="' + subject + '">' + subject + '</option>');
            });
        }

        // Hide "Edit" button for slots without text
        $('.slot.without-text .edit-btn').hide();

        // Enable click event only for slots with text
        $('.slot.with-text').click(function () {
            var slot = $(this);
            $('#editStudentId').val(slot.data('student-id'));
            $('#editUsername').val(slot.data('username'));
            $('#editDay').val(slot.data('day'));
            $('#editTime').val(slot.data('time'));
            var activity = slot.data('lab-title');
            $('#editLabTitle').val(activity); // Set default value
            populateDropdown(activity);
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

        $('.modal').dblclick(function () {
            $(this).fadeOut();
        });
    });
</script>
</body>
</html>
