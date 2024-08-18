<?php
// session_start();
// require 'dbcon.php';

// // Insert data start
// if (isset($_POST['save_supp_data'])) {
//     $name = $_POST['name'];
//     $email = $_POST['email'];
//     $phone = $_POST['phone'];
//     $address = $_POST['address'];

//     $insert_query = "INSERT INTO supplier(name,email,phone,address) VALUES ('$name', '$email', '$phone', '$address')";
//     $insert_query_run = mysqli_query($con, $insert_query);

//     if ($insert_query_run) {
//         $_SESSION['status'] = "Data inserted successfully !";
//         header('Location: supplier.php');
//     } else {
//         $_SESSION['status'] = "Insertion of data failed !";
//         header('Location: supplier.php');
//     }
// }
// // Insert data end

// // View data start
// if (isset($_POST['click_view_btn'])) {
//     $id = $_POST['user_id'];

//     // echo $id;
//     $fetch_query = "SELECT * FROM supplier WHERE id='$id'";
//     $fetch_query_run = mysqli_query($con, $fetch_query);

//     if (mysqli_num_rows($fetch_query_run) > 0) {
//         while ($row = mysqli_fetch_array($fetch_query_run)) {
//             echo '
//                 <h6>Supplier ID : ' . $row['id'] . '</h6>
//                 <h6>Supplier Name : ' . $row['name'] . '</h6>
//                 <h6>Email Address : ' . $row['email'] . '</h6>
//                 <h6>Contact No : ' . $row['phone'] . '</h6>
//                 <h6>Address : ' . $row['address'] . '</h6>
//             ';
            
//         }
//     } 
    
//     else {
//         echo '<h4>No record found</h4>';

//     }
// }
// // view data end


// // Edit data start
// if (isset($_POST['click_edit_btn'])) {
//     $id = $_POST['user_id'];
//     $arrayresult = [];

//     // echo $id;
//     $fetch_query = "SELECT * FROM supplier WHERE id='$id'";
//     $fetch_query_run = mysqli_query($con, $fetch_query);

//     if (mysqli_num_rows($fetch_query_run) > 0) {
//         while ($row = mysqli_fetch_array($fetch_query_run)) {
//             array_push($arrayresult, $row);
//             header('content-type: application/json');
//             echo json_encode($arrayresult);
            
//         }
//     } 
    
//     else {
//         echo '<h4>No record found</h4>';

//     }
// }
// // Edit data end


// //update data start
// if(isset($_POST['update_data'])){
//     $id = $_POST['id'];
//     $name = $_POST['name'];
//     $email = $_POST['email'];
//     $phone = $_POST['phone'];
//     $address = $_POST['address'];

//     $update_query = "UPDATE supplier SET name='$name', email='$email', phone='$phone', address='$address' WHERE id='$id'";
//     $update_query_run = mysqli_query($con, $update_query);

//     if($update_query_run){
//         $_SESSION['status'] = "Data updated successfully !";
//         header('Location: supplier.php');
//     }
//     else{
//         $_SESSION['status'] = "Data updation failed !";
//         header('Location: supplier.php');
//     }
// }
// //update data end



// //Read Data start
// if(isset($_POST['click_delete_btn'])){
//     $id = $_POST['user_id'];

//     $delete_query = "DELETE FROM supplier WHERE id='$id'";
//     $delete_query_run = mysqli_query($con, $delete_query);

//     if($delete_query_run){
//         $_SESSION['status'] = "Data deleted successfully !";
        
//     }
//     else{
//         $_SESSION['status'] = "Data deletion failed !";
        
//     }
// }
// //Read Data end

?>