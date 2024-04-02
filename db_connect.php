<?php 

$conn = mysqli_connect('localhost','Liel','Kugara2021', 'KugaraBookings');

 if(!$conn){
 	echo 'Connection error: ' . mysqli_connect_error();
 } 

?>