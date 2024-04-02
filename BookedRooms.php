<?php
session_start(); 
if($_SESSION["Role"]=='Admin'){
 // header('Location: index.php');
} else {
 header('Location: index.php');
}

include('Config/db_connect.php');


 $sql = "SELECT `name`, `email`, `usdNight`, `Facilities`, `Rooms`.`RoomID` FROM `Bookings`, `Rooms` WHERE `Bookings`.`RoomID` = `Rooms`.`RoomID`;";

 $result = mysqli_query($conn, $sql);

 $Rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);

mysqli_close($conn);



?>

<!DOCTYPE html> 
<html>

<?php include('templates/head-dash.php'); ?>
<?php $title = "Rooms Booked"; ?>
<h4 class="center grey-text"><?php echo $title ?></h4>

<div class="container">

<div class="row">
    
            <?php foreach ($Rooms as $Room){ ?>
       
    <div class="col s6 md3">
        <div class="card z-depth-0">
                <div class="card-content center">
                    <label>Room Number:</label>
                    <h6><?php echo htmlspecialchars($Room['RoomID']); ?></h6>
                    <label>name of Occupants:</label>
                    <div><?php echo htmlspecialchars($Room['name']); ?></div>
                    <label>Price in USD per night:</label>
                     <div><?php echo htmlspecialchars($Room['usdNight']);?></div>
                     <label>Room Facilities:</label>
                     <div><?php echo htmlspecialchars($Room['Facilities']); ?></div>

                </div>  
            <div class="card-action center">
            <a class = "brand-text" href = "#">Room Booked</a>
            </div>
        </div>
    </div>
             <?php  }?> 
</div>

</div>

<?php include('templates/footer.php'); ?>

</html>