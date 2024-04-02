<html>
<?php session_start(); ?>

 <head>
  <link rel="stylesheet" type="text/css" href="Login.CSS">
      </head>
<form action="Login.php" method="post">
  <div class="imgcontainer">
    <img src="avatar_admin.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
    <label for="uname"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="Username" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="Password" required>

    <button type="submit" name="submit">Login</button>
   
  </div>

  <div class="container" style="background-color:#f1f1f1">
    <button type="button" class="cancelbtn">Cancel</button>
    <span class="psw">Forgot <a href="#">password?</a></span>
  </div>
</form>
</html>
<?php
 // Starting Session
//$error=''; // Variable To Store Error Message

include_once ('conn.php');

//check if form is submitted
if (isset($_POST['submit'])) {
    $Username = mysqli_real_escape_string($conn, $_POST['Username']);
    $Password = mysqli_real_escape_string($conn, $_POST['Password']);
    $Password=md5($Password); // **Encrypted Password**     
        if( $Username == "" || $Password == ""){

           // echo "Please fill in all fields";
            }
            else 
            {       
            $select = "SELECT * FROM Login WHERE Username = '$Username' and Password = '$Password'";
            //echo $select;exit;
            $result = mysqli_query($conn, $select);
                    //echo $result;exit;
                while($row = mysqli_fetch_array( $result )){        

                $_SESSION['Username'] = $row['Username'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['Role'] = $row['Role'];
                $_SESSION['Email'] = $row['Email'];
                //print_r($row);exit;

                    if($row['Role'] == 'Admin') { // check the value of the 'status' in the db
                    //go to admin area

                    header("Location: dashboard.php");

                    } elseif($row['Role'] == 'test') {
                    //go to user area

                    header("Location: index.php");
                    }
else{

                        echo "wrong Email and Password";
                    }
                 }  
          
            }
}
?>