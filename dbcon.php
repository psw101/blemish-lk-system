<?php 
    $con = mysqli_connect("localhost","root","","blemishdb");

    if(!$con){
        die("Connection Failed :" . mysqli_connect_error());
    }
?>