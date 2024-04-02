<?php
include_once 'conn.php';
mysqli_query($conn,"DELETE FROM Bookings WHERE BookingID='" . $_GET["BookingID"] . "'");
header("Location:index.php");
?> 