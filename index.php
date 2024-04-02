<?php

session_start();
//notes
include('Config/db_connect.php');


$sql = "SELECT * FROM `Rooms` WHERE NOT EXISTS(SELECT 1 FROM `Bookings` WHERE `Bookings`.`RoomID`=`Rooms`.`RoomID`);";

 $result = mysqli_query($conn, $sql);

 $Rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_free_result($result);

mysqli_close($conn);



?>

<!DOCTYPE html> 
<html>


<?php include('templates/header.php'); ?>

<h4 class="center grey-text">Available Rooms</h4>

<div class="container">

<div class="row">
    
            <?php foreach ($Rooms as $Room){ ?>
       
    <div class="col s6 md3">
        <div class="card z-depth-0">
                <div class="card-content center">
                    <label>Room Number:</label>
                    <h6><?php echo htmlspecialchars($Room['RoomID']); ?></h6>
                    <label>Number of Occupants:</label>
                    <div><?php echo htmlspecialchars($Room['nOccupants']); ?></div>
                    <label>Price in USD per night:</label>
                     <div><?php echo htmlspecialchars($Room['usdNight']); ?></div>
                     <label>Room Facilities:</label>
                     <div><?php echo htmlspecialchars($Room['Facilities']); ?></div>
                   <!-- <label>Room Image:</label>
                     <div><?php  //img src =($Room['Photos']); ?></div> -->   
                </div>  
            <div class="card-action center">
            <a class = "brand-text" href = "add.php">Select Room</a>
            </div>
        </div>
    </div>
             <?php  }?> 
</div>

</div>

<?php include('templates/footer.php'); ?>

</html>
