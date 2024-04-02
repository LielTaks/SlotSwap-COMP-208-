<?php 
session_start(); 
if($_SESSION["Role"]=='Admin'){
 // header('Location: index.php');
} else {
 header('Location: index.php');
}
include ('templates/head-dash.php'); ?>

<table class="table">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Check-in date</th>
      <th scope="col">Check-out date</th>
       <th scope="col">Room Number</th>
      <th scope="col">Checkout</th>
    </tr>
  </thead>
  <tbody>
  	<?php 
error_reporting(1);
  	include ('conn.php'); 
 $table = mysqli_query($conn,"SELECT * FROM `Bookings`");
 		while ($row=mysqli_fetch_array($table)) {
 			// code...
 		;	

 		?>
 		
    <tr>
      <td><?php echo $row['name'];?></td>
      <td><?php echo $row['email'];?></td>
      <td><?php echo $row['checkin'];?></td>
      <td><?php echo $row['checkout'];?></td>
      <td><?php echo $row['RoomID'];?></td>
       <td> <a href="delete.php?BookingID=<?php echo $row['BookingID']; ?>">Checkout | Cancel</a>

  <?php } echo "</table>";

    
?>


<?php include ('template/footer.php'); ?>