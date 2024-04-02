<?php
// error_reporting(1);
include('conn.php');

$name = $checkin = $checkout = $email = '';
$errors = array('name'=>'', 'checkin'=>'', 'checkout'=>'', 'email'=>'');

if(isset($_POST['submit'])){

	if(empty($_POST['name'])){
		$errors['name'] =  'Your name is required <br />';
	} else {
	$name = $_POST['name'];
	if(!preg_match('/^[a-zA-Z\s]+$/', $name)) {
      $errors['name'] = 'Your name can only contain letters and spaces';
    	}
	}

	if(empty($_POST['checkin'])){
		$errors['checkin'] =  'A check-in date is required <br />';
	} else {
		$checkin = $_POST['check-in'];
	}

	if(empty($_POST['checkout'])){
		$errors['checkout'] =  'A check-out date is required <br />';
	} 
	if(($_POST['checkin'])> ($_POST['checkout'])) {
		$errors['checkout'] = 'Your date is not correct';
	}
	else {

		$checkout = $_POST['checkout'];
	}
  
	if(empty($_POST['email'])){
		$errors['email'] =  'An email is required <br />';
	} else {
	$email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = 'Email must be a valid email address ';
    }
   }

if(array_filter($errors)){
// echo 'errors in the form';
	} else{

	 	 $email = mysqli_real_escape_string($conn,$_POST['email']);
		 $name = mysqli_real_escape_string($conn,$_POST['name']);
		 $checkin = mysqli_real_escape_string($conn,$_POST['checkin']);
		 $checkout = mysqli_real_escape_string($conn,$_POST['checkout']);
	 $RoomID = mysqli_real_escape_string($conn,$_POST['RoomID']);

// create sql
	$sql ="INSERT INTO `Bookings` (`BookingID`, `name`, `email`, `checkin`, `checkout`, `RoomID`, `Timestamp`) VALUES (NULL, '$name', '$email', '$checkin', '$checkout', '$RoomID', CURRENT_TIMESTAMP)";

// Save to db and check
	if(mysqli_query($conn, $sql)){
		// success
		echo '<script type="text/javascript">alert("Congratulations, Your Room is Booked. You will receive instructions to pay a deposit");</script>';
	} else {
		// error
		echo 'query error: ' . mysqli_error($conn);
	}


		//echo 'form is valid';
		//header('Location: index.php');
	
	}

}

?>

<!DOCTYPE html> 
<html>


<?php include('templates/header.php'); ?>

<section class="container brown-text">
	<h4 class="center">Make a Booking</h4>
	<form class="white" action="add.php" method="POST">

		<label>Full Name:</label>
		<!-- add a name for a client -->
		<input type ="text" name="name" value="<?php echo $name ?>">
		<div class="red-text"><?php echo $errors['name']; // show errors ?></div>

		<label>Check-in Date:</label>
		<input type ="date" min="<?php
$d=strtotime("now");
echo date("Y-m-d");?>" max="2022-10-20" name="checkin" value="<?php echo $checkin ?>">
		<div class="red-text"><?php echo $errors['check-in']; ?></div>

		<label>Check-Out Date:</label>
		<input type ="date" min="<?php
$tomorrow = new DateTime('tomorrow');
echo $tomorrow->format('Y-m-d');?>" id="checkout" name="checkout" value="<?php echo $checkout ?>">
		<div class="red-text"><?php echo $errors['check-out']; ?></div>

		<label>Email Address:</label>
		<input type ="text" id="email" name="email" value="<?php echo $email ?>">
		<div class= "red-text"><?php echo $errors['email']; ?></div>
		<label for="RoomID">Select Room No. </label>
                       <SELECT style="display: block;" id="RoomID" name="RoomID"class="form-control"> <OPTION VALUE=0>Choose
                            <?php 
 include ('conn.php');
 /* fetch available rooms from the database */
  $result = mysqli_query($conn, "SELECT a.`RoomID` FROM Rooms a LEFT JOIN Bookings b on (a.RoomID=b.RoomID) WHERE b.RoomID IS NULL OR b.checkout < $checkin");
 while($rows = mysqli_fetch_array($result))
 {
  $RoomID=$rows["RoomID"];
 /* show available rooms on the booking form */
  
  echo '<OPTION VALUE="' . $RoomID . '">'.$RoomID.'</OPTION>';
  }
  mysqli_close ($conn); 
 ?>
                            </select>  </div>
 </div>
 </div>
<div class="center">
	<input type="submit" name="submit" value="submit" class="btn brand z-depth-0"></input>
</div>
	</form>

</section>

<?php include('templates/footer.php'); ?>

</html>
