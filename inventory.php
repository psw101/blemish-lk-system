<?php
session_start();
require('dbcon.php');

/* code.php file start */


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



<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Manage suppliers card -->
            <div class="card">
    <div class="card-header">
        <h4 class="text-dark fw-bold">INVENTORY</h4>
        <form method="GET" action="">
            <div class="row gx-2">
                <!-- Search section -->
                <div class="col-lg-6 mb-2">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by Product Name" name="search" 
                               value="<?php if (isset($_GET['search'])) { echo $_GET['search']; } ?>">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
                <!-- Filter section -->
                <div class="col-lg-6 mb-2">
                    <div class="input-group">
                        <select class="form-select" name="category">
                            <option value="">Filter by Category</option>
                            <?php
                            // Fetching categories for dropdown
                            $category_query = "SELECT * FROM categories";
                            $category_query_run = mysqli_query($con, $category_query);

                            if (mysqli_num_rows($category_query_run) > 0) {
                                while ($cat_row = mysqli_fetch_array($category_query_run)) {
                                    $selected = (isset($_GET['category']) && $_GET['category'] == $cat_row['categories_id']) ? 'selected' : '';
                                    echo "<option value='" . $cat_row['categories_id'] . "' $selected>" . $cat_row['categories_name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                        <button class="btn btn-primary" type="submit">Filter</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="card-body bg-light" style="max-height: 60vh; overflow-y: auto;">
        <table class="table table-bordered table-hover" width="100%" p-3>
            <thead>
                <tr>
                    <th scope="col">Product ID</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Quantity In Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Search and filter functionality
                $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
                $category = isset($_GET['category']) ? mysqli_real_escape_string($con, $_GET['category']) : '';

                $fetch_query = "SELECT inventory.product_id, product.product_name, inventory.quantity_in_stock 
                                FROM inventory 
                                INNER JOIN product ON inventory.product_id = product.product_id ";

                // Applying conditions based on search and category filter
                $conditions = [];
                if ($search != '') {
                    $conditions[] = "product.product_name LIKE '%$search%'";
                }
                if ($category != '') {
                    $conditions[] = "product.categories_id = '$category'";
                }
                if (count($conditions) > 0) {
                    $fetch_query .= " WHERE " . implode(' AND ', $conditions);
                }

                $fetch_query_run = mysqli_query($con, $fetch_query);

                if (mysqli_num_rows($fetch_query_run) > 0) {
                    while ($row = mysqli_fetch_array($fetch_query_run)) {
                ?>
                        <tr>
                            <td class="user_id"><?php echo $row['product_id']; ?></td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['quantity_in_stock']; ?></td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="3" class="text-center">No Record Found</td>
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