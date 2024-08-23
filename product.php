<?php
session_start();
require('dbcon.php');

// ... (Insert, View, Edit, Delete functionality code remains the same)

// Fetch and search data
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($con, $_GET['search']);
}

$fetch_query = "SELECT p.product_id, p.product_name, p.product_des, c.categories_name, p.sellPrice 
                FROM product p 
                JOIN categories c ON p.categories_id = c.categories_id 
                WHERE p.product_name LIKE '%$search_query%' 
                OR p.product_des LIKE '%$search_query%'
                OR c.categories_name LIKE '%$search_query%' 
                OR p.sellPrice LIKE '%$search_query%'";

$fetch_query_run = mysqli_query($con, $fetch_query);
?>

<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Success message code -->

            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark fw-bold">MANAGE PRODUCTS</h4>
                    
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" class="float-left form-inline mt-2">
                        <input type="text" name="search" class="form-control mr-sm-2" placeholder="Search...">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>

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
                            if (mysqli_num_rows($fetch_query_run) > 0) {
                                while ($row = mysqli_fetch_array($fetch_query_run)) {
                            ?>
                                    <tr>
                                        <td class="user_id"><?php echo $row['product_id']; ?></td>
                                        <td><?php echo $row['product_name']; ?></td>
                                        <td><?php echo $row['product_des']; ?></td>
                                        <td><?php echo $row['categories_name']; ?></td>
                                        <td><?php echo $row['sellPrice']; ?></td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm view_data">View</a>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-success btn-sm edit_data">Edit</a>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-danger btn-sm delete_btn" data-id="<?php echo $row['product_id']; ?>">Delete</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="8">No Record Found</td>
                                </tr>
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
