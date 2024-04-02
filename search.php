<?php 
session_start(); 
if($_SESSION["Role"]=='Admin'){
 // header('Location: index.php');
} else {
 header('Location: index.php');
}
include ('templates/head-dash.php'); ?>

<table class="table">
    <div class="box">
            <div class="box-header">
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Check-in date</th>
      <th scope="col">Check-out date</th>
       <th scope="col">Room Number</th>
      <th scope="col">Checkout</th>
      <th scope="col">Edit</th>
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
       <td> <a href="delete.php?BookingID=<?php echo $row['BookingID']; // delete booking?>">Checkout | Cancel</a>
                    <td> <a href="amend.php?BookingID=<?php echo $row['BookingID']; // edit booking ?>">Amend | Modify</a>
              </td>
  <?php } echo "</table>";

    
?>
<!-- call the search library bower component: An open library to expadite search in tables (https://datatables.net) for [jQuery](http://jquery.com/)-->
<script src="./bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="./bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="./bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="./bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="./bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="./bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="./dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="./dist/js/demo.js"></script>
<!-- javascript embed in jquery -->
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>

<?php include ('template/footer.php'); ?>