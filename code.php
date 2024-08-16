<?php 
    session_start();
    require 'dbcon.php';

    if(isset($_POST['add_supplier'])){
        $name = mysqli_real_escape_string($con,$_POST['name']);
        $email = mysqli_real_escape_string($con,$_POST['email']);
        $phone = mysqli_real_escape_string($con,$_POST['phone']);
        $address = mysqli_real_escape_string($con,$_POST['address']);

        $query = "INSERT INTO supplier (name,email,phone,address) VALUES ('$name','$email','$phone','$address')";
        $query_run = mysqli_query($con, $query);

        if($query_run){
            $_SESSION['message'] = "Supplier Added";
            header("Location: supplier.php");
            exit(0);
        }else{
            $_SESSION['message'] = "Supplier Not Added";
            header("Location: supplier.php");
            exit(0);
        }
    }

    // if(isset($_POST['save_data'])){
    //     $name = $_POST['name'];
    //     $email = $_POST['email'];
    //     $phone = $_POST['phone'];
    //     $address = $_POST['address'];

    //     $insert_query = "INSERT INTO supplier(name,email,phone,address) VALUES ('$name', '$email', '$phone', '$address')";
    //     $insert_query_run = mysqli_query($con, $insert_query);

    //     if($insert_query_run){
    //         $_SESSION['status'] = "Data inserted successfully !";
    //         header('Location: test.php');
    //         exit(0);
    //     }

    //     else{
    //         $_SESSION['status'] = "Insertion of data failed !";
    //         header('Location: test.php');
    //         exit(0);
    //     }
    // }

    if(isset($_POST['click_view_btn'])){
        $id = $_POST['user_id'];

        // echo $id;
        $fetch_query = "SELECT * FROM supplier WHERE id='$id'";
        $fetch_query_run = mysqli_query($con, $fetch_query);

        if(mysqli_num_rows($fetch_query_run)>0){
            while($row = mysqli_fetch-array($fetch_query_run)){
                echo '
                    <h6>' .$row['id']. '</h6>
                    <h6>' .$row['name']. '</h6>
                    <h6>' .$row['email']. '</h6>
                    <h6>' .$row['number']. '</h6>
                    <h6>' .$row['address']. '</h6>
                    
                ';
            }
        }

        else{
            echo '<h4>No record found</h4>';
        }

    }
?>