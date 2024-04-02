<?php session_start(); 
if($_SESSION["Role"]=='Admin'){
 // header('Location: index.php');
} else {
 header('Location: index.php');
}
// include('templates/head-dash.php'); 
include('search.php'); 
?>