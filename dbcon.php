<?php 
    $con = mysqli_connect("localhost","root","","blemish_inventory");

    if(!$con){
        die("Connection Failed :" . mysqli_connect_error());
    }
?>