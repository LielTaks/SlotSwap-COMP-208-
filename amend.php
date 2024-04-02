<?php 
session_start(); 
if($_SESSION["Role"]=='Admin'){
 // header('Location: index.php');
} else {
 header('Location: index.php');
}
//include "templates/header.php"; 
//intentinaly left blank
?><?php include_once 'conn.php';
if(count($_POST)>0) {
mysqli_query($conn,"UPDATE Bookings set  BookingID='" . $_POST['BookingID'] . "', name='" . $_POST['name'] . "', email='" . $_POST['email'] . "', RoomID='" . $_POST['RoomID'] . "', checkin='" . $_POST['checkin'] . "' ,checkout='" . $_POST['checkout'] . "' WHERE BookingID='" . $_POST['BookingID'] . "'");
$message = "Booking Modified Successfully";

}
$result = mysqli_query($conn,"SELECT * FROM Bookings WHERE BookingID='" . $_GET['BookingID'] . "'");
$row= mysqli_fetch_array($result);
//echo var_dump($result);
?><!DOCTYPE html> 
<html>


<?php include('templates/head-dash.php'); ?>

<section class="container brown-text">
	<h4 class="center">Update Booking Records</h4>
	<form class="white" action="" method="POST">
BookingID: <br>
<input type="hidden" name="BookingID" class="txtField" value="<?php echo $row['BookingID']; ?>">
<input type="text" name="BookingID"  value="<?php echo $row['BookingID']; ?>">
<br>
		<label>Full Name:</label>
		<input type ="text" name="name" value="<?php echo $row['name']; ?>">
		<div class="red-text"><?php //echo $errors['name']; ?></div>

		<label>Check-in Date:</label>
		<input type ="date" min="<?php
$d=strtotime("now");
echo date("Y-m-d");?>" max="2022-10-20" name="checkin" value="<?php echo $row['checkin'];  ?>">
		<div class="red-text"><?php //echo $errors['check-in']; ?></div>

		<label>Check-Out Date:</label>
		<input type ="date" min="<?php
$tomorrow = new DateTime('tomorrow');
echo $tomorrow->format('Y-m-d');?>" id="checkout" name="checkout" value="<?php echo $row['checkout'];  ?>">
		<div class="red-text"><?php //echo $errors['check-out']; ?></div>

		<label>Email Address:</label>
		<input type ="text" id="email" name="email" value="<?php echo $row['email'];  ?>">
		<div class= "red-text"><?php //echo $errors['email']; ?></div>
		<div class="form-group">
       <label for="courseid">Select Room No. </label>
                       <SELECT style="display: block;" name="RoomID"class="form-control"> <OPTION VALUE=0>Choose
                            <?php 
 include ('conn.php');
  $result = mysqli_query($conn, "SELECT a.`RoomID` FROM Rooms a LEFT JOIN Bookings b on (a.RoomID=b.RoomID) WHERE b.RoomID IS NULL");
 while($rows = mysqli_fetch_array($result))
 {
  $RoomID=$rows["RoomID"];
 
  
  echo '<OPTION VALUE="' . $RoomID . '">'.$RoomID.'</OPTION>';
  }
  mysqli_close ($conn); //stänger connectio till DB system;
 ?>
                            </select>  </div>
		
 </div>
<div class="center">
	<input type="submit" name="submit" value="submit" class="btn brand z-depth-0"></input>
</div>
	</form>

</section>

<?php include('templates/footer.php'); ?>

</html>
