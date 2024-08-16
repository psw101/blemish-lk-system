<?php
session_start();
require 'dbcon.php';


if (isset($_POST['save_data'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $insert_query = "INSERT INTO supplier(name,email,phone,address) VALUES ('$name', '$email', '$phone', '$address')";
    $insert_query_run = mysqli_query($con, $insert_query);

    if ($insert_query_run) {
        $_SESSION['status'] = "Data inserted successfully !";
        header('Location: test2.php');
    } else {
        $_SESSION['status'] = "Insertion of data failed !";
        header('Location: test2.php');
    }
}

if (isset($_POST['click_view_btn'])) {
    $id = $_POST['user_id'];

    // echo $id;
    $fetch_query = "SELECT * FROM supplier WHERE id='$id'";
    $fetch_query_run = mysqli_query($con, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            echo '
                <h6>Supplier ID : ' . $row['id'] . '</h6>
                <h6>Supplier Name : ' . $row['name'] . '</h6>
                <h6>Email Address : ' . $row['email'] . '</h6>
                <h6>Contact No : ' . $row['phone'] . '</h6>
                <h6>Address : ' . $row['address'] . '</h6>
            ';
            
        }
    } 
    
    else {
        echo '<h4>No record found</h4>';

    }
}


?>