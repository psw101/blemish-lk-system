<?php
session_start();
require('dbcon.php');

/* --------------------------------------------------------------------------------code.php file start-------------------------------------------------------------------- */

// Insert data start
if (isset($_POST['save_supp_data'])) {
    $productname = $_POST['productname'];
    $productdes = $_POST['productdes'];
    $productcategory = $_POST['category'];
    $sellprice = $_POST['productprice'];
    

    $insert_query = "INSERT INTO product(product_name,product_des,categories_id,sellPrice) VALUES ('$productname', '$productdes', '$productcategory', '$sellprice')";
    $insert_query_run = mysqli_query($con, $insert_query);

    if ($insert_query_run) {
        $_SESSION['status'] = "Data inserted successfully!";
    } else {
        $_SESSION['status'] = "Insertion of data failed!";
    }

    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
// Insert data end

// View data start
if (isset($_POST['click_view_btn'])) {
    $user_id = $_POST['user_id'];

    // Fetch user data based on $user_id from the database
    $query = "SELECT * FROM product WHERE product_id = '$user_id'";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $user_data = mysqli_fetch_assoc($query_run);
        // Assuming you want to return the data in some format (e.g., HTML or JSON)
        // Here is an example of returning the data as HTML
        echo "<p>Name: " . $user_data['product_name'] . "</p>";
        echo "<p>Email: " . $user_data['product_des'] . "</p>";
        echo "<p>Phone: " . $user_data['categories_id'] . "</p>";
        echo "<p>Address: " . $user_data['sellPrice'] . "</p>";
    } else {
        echo "No data found for this user.";
    }
    exit; // Stop further execution since this is an AJAX request
}
// view data end

// Edit data start

if (isset($_POST['click_edit_btn'])) {
    $id = $_POST['user_id'];
    $arrayresult = [];

    // Fetch user data based on $user_id from the database
    $query = "SELECT * FROM product WHERE product_id = '$id'";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_array($query_run)) {
            array_push($arrayresult, $row);
            header('Content-type: application/json');
            echo json_encode($arrayresult);

        }
    } else {
        echo "No data found for this user.";
    }
    exit; // Stop further execution since this is an AJAX request
}
// Edit data end



//update data start
if (isset($_POST['update_data'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $update_query = "UPDATE supplier SET name='$name', email='$email', phone='$phone', address='$address' WHERE id='$id'";
    $update_query_run = mysqli_query($con, $update_query);

    if ($update_query_run) {
        $_SESSION['status'] = "Data updated successfully !";
        header('Location: supplier.php');
        exit;
    } else {
        $_SESSION['status'] = "Data updation failed !";
        header('Location: supplier.php');
        exit;
    }
}
//update data end



//Read Data start
if (isset($_POST['click_delete_btn'])) {
    $id = $_POST['user_id'];

    $delete_query = "DELETE FROM supplier WHERE id='$id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if ($delete_query_run) {
        $_SESSION['status'] = "Data deleted successfully !";
        exit;
    } else {
        $_SESSION['status'] = "Data deletion failed !";
        exit;
    }
}
//Read Data end

/* code.php FILE End */

?>


<!-- ................................................................................supplier.php file start............................................................ -->

<?php
include('includes/header.php');
include('includes/navbar.php');
?>





<style>
    .modal {
        color: black;
        font-weight: 500;
    }
</style>

<!--Insert Modal Start-->
<div class="modal fade" id="insertdata" tabindex="-1" role="dialog" aria-labelledby="insertdataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="insertdataLabel">Add Products</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="productname">Product Name</label>
                        <input type="text" class="form-control" name="productname" placeholder="Enter product name">
                    </div>

                    <div class="form-group">
                        <label for="productdes">Product Description</label>
                        <input type="text" class="form-control" name="productdes" placeholder="Enter description">
                    </div>
                    

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control" name="category">
                            <option value="Tshirt">T-shirt</option>
                            <option value="Shirt">Shirt</option>
                            <option value="trousers">Trousers</option>
                            <option value="skirts">Skirts</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="productprice">Sell Price</label>
                        <input type="number" class="form-control" name="productprice" placeholder="Selling price">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="save_supp_data" class="btn btn-primary">Add Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Insert Modal End-->

<!-- View Modal Start -->
<div class="modal fade" id="viewuser" tabindex="-1" role="dialog" aria-labelledby="viewuserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewuserLabel">View Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="view_user_data">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--View Modal End-->

<!--Edit Modal Start-->
<div class="modal fade" id="editdata" tabindex="-1" role="dialog" aria-labelledby="editdataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editdataLabel">Edit Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">

                        <input type="hidden" class="form-control" id="product_id" name="id">
                    </div>

                    <div class="form-group mb-3">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="enter name">
                    </div>
                    <!-- id use for jquery, name use for php -->

                    <div class="form-group">
                        <label for="email">Product Description</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="enter email">
                    </div>

                    <div class="form-group">
                        <label for="phone">Product Category</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="enter number">
                    </div>

                    <div class="form-group">
                        <label for="address">Sell Price</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="enter address">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="update_data" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit Modal End-->

<div class="container-fluid  mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Success messge show start -->
            <?php
            if (isset($_SESSION['status']) && $_SESSION['status'] != '') {

            ?>

                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Hey !</strong> <?php echo $_SESSION['status']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            <?php
                unset($_SESSION['status']);
            }
            ?>
            <!-- Success messge show end -->

            <!-- Manage suppliers card -->
            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark fw-bold">MANAGE PRODUCTS</h4>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#insertdata">
                        Add Products
                    </button>
                </div>

                <div class="card-body bg-light" style="max-height: 60vh; overflow-y: auto;">
                    <table class="table table-bordered table-hover" width="100%" p-3>
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Category</th>
                                <th scope="col">Sell Price</th>
                                <th scope="col">View</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $fetch_query = "SELECT * FROM product";
                            $fetch_query_run = mysqli_query($con, $fetch_query);

                            if (mysqli_num_rows($fetch_query_run) > 0) {
                                while ($row = mysqli_fetch_array($fetch_query_run)) {
                            ?>
                                    <tr>
                                        <td class="user_id"><?php echo $row['product_id']; ?></td>
                                        <td><?php echo $row['product_name']; ?></td>
                                        <td><?php echo $row['product_des']; ?></td>
                                        <td><?php echo $row['categories_id']; ?></td>
                                        <td><?php echo $row['sellPrice']; ?></td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm view_data">View</a>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-success btn-sm edit_data">Edit</a>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-danger btn-sm delete_btn" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr colspan="4">No Record Found</tr>
                            <?php

                            }

                            ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>


<?php
include('includes/scripts.php');
include('includes/footer.php');
?>

<script>
    //view data start
    $(document).ready(function() {
        $('.view_data').click(function(e) {
            e.preventDefault();


            var user_id = $(this).closest('tr').find('.user_id').text();
            // console.log(user_id);

            $.ajax({
                method: "POST",
                url: "product.php",
                data: {
                    'click_view_btn': true,
                    'user_id': user_id,
                },
                success: function(response) {
                    // console.log(response);

                    $('.view_user_data').html(response);
                    $('#viewuser').modal('show');

                }
            });


        })
    });
    //view data end


    // Edit data start
    $(document).ready(function() {
        $('.edit_data').click(function(e) {
            e.preventDefault();


            var user_id = $(this).closest('tr').find('.user_id').text();
            console.log(user_id);

            $.ajax({
                method: "POST",
                url: "product.php",
                data: {
                    'click_edit_btn': true,
                    'user_id': user_id,
                },
                success: function(response) {
                    // console.log(response);

                    $.each(response, function(key, value) {
                        $('#supplier_id').val(value[0]);
                        $('#name').val(value[1]);
                        $('#email').val(value[2]);
                        $('#phone').val(value[3]);
                        $('#address').val(value[4]);
                    });
                    
                    $('#editdata').modal('show');

                }
            });


        })
    });
    // Edit data end

    //Delete data start
    $(document).ready(function() {
        $('.delete_btn').click(function(e) {
            e.preventDefault();


            var user_id = $(this).closest('tr').find('.user_id').text();
            // console.log(supplier_id);

            $.ajax({
                method: "POST",
                url: "supplier.php",
                data: {
                    'click_delete_btn': true,
                    'user_id': user_id,
                },
                success: function(response) {
                    console.log(response);
                    window.location.reload();

                    // $('.view_user_data').html(response);
                    // $('#viewuser').modal('show');

                }
            });



        })
    });
    //Delete data end
</script>