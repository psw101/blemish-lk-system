<?php
include_once('./dbcon.php');
include_once('./popup-util.php');

/* -- Products Table
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    category_id INT,
    supplier_id INT,
    price DECIMAL(10, 2) NOT NULL,
    quantity_in_stock INT NOT NULL,
    size VARCHAR(50),
    color VARCHAR(50),
    description TEXT,
    FOREIGN KEY (category_id) REFERENCES categories(categories_id),
    FOREIGN KEY (supplier_id) REFERENCES supplier(id)
); */


/* -- Inventory Table
CREATE TABLE inventory (
    inventory_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    quantity INT NOT NULL,
    remarks TEXT,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
); */

// add new data to inventory table
if (isset($_POST['add'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $remarks = $_POST['remarks'];

    //data validations
    if (empty($product_id) || empty($quantity)) {
        display_alert("Please fill in the required fields");
        sleep(30);
    } else {
        // prevent sql injection
        $product_id = mysqli_real_escape_string($con, $product_id);
        $quantity = mysqli_real_escape_string($con, $quantity);
        $remarks = mysqli_real_escape_string($con, $remarks);

        // check if product exists
        $query = "SELECT * FROM products WHERE product_id = '$product_id'";
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result) == 0) {
            display_alert("Product does not exist");
        } else {
            $query = "INSERT INTO inventory (product_id, quantity, remarks) VALUES ('$product_id', '$quantity', '$remarks')";
            $result = mysqli_query($con, $query);

            if ($result) {
                $_SESSION['status'] = "Data Inserted Successfully";
            } else {
                $_SESSION['status'] = "Data Insertion is failed";
            }
        }
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// view product details
if (isset($_GET['view_btn'])) {
    $product_id = $_GET['product_id'];

    // prevent sql injection
    $product_id = mysqli_real_escape_string($con, $product_id);

    $query = "SELECT * FROM products WHERE product_id = '$product_id'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 0) {
        display_alert("Product does not exist");
        sleep(30);
    } else {
        $row = mysqli_fetch_array($result);
        $product_name = $row['product_name'];
        $price = $row['price'];
        $quantity_in_stock = $row['quantity_in_stock'];
        $size = $row['size'];
        $color = $row['color'];
        $description = $row['description'];
        $category_id = $row['category_id'];
        $supplier_id = $row['supplier_id'];

        $query_for_category = "SELECT * FROM categories WHERE categories_id = '$category_id'";
        $query_for_supplier = "SELECT * FROM supplier WHERE id = '$supplier_id'";

        $result_for_category = mysqli_query($con, $query_for_category);
        $result_for_supplier = mysqli_query($con, $query_for_supplier);
        if (mysqli_num_rows($result_for_category) == 0 || mysqli_num_rows($result_for_supplier) == 0) {
            $category_name = "N/A";
            $supplier_name = "N/A";
        } else {
            $row_for_category = mysqli_fetch_array($result_for_category);
            $row_for_supplier = mysqli_fetch_array($result_for_supplier);
            $category_name = $row_for_category['category_name'];
            $supplier_name = $row_for_supplier['name'];
        }
    }

    $query = "SELECT * FROM inventory WHERE product_id = '$product_id'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) == 0) {
        $quantity = 0;
        $remarks = "no remarks";
    } else {
        $row = mysqli_fetch_array($result);
        $quantity = $row['quantity'];
        $remarks = $row['remarks'];
    }

    echo "<script>document.getElementById('viewModal').style.display='block';</script>";
}



// update data in inventory table
if (isset($_POST['update_btn'])) {
    $inventory_id = $_POST['inventory_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $remarks = $_POST['remarks'];

    //data validations
    if (empty($product_id) || empty($quantity)) {
        display_alert("Please fill in the required fields");
        sleep(30);
    } else {
        // prevent sql injection
        $inventory_id = mysqli_real_escape_string($con, $inventory_id);
        $product_id = mysqli_real_escape_string($con, $product_id);
        $quantity = mysqli_real_escape_string($con, $quantity);
        $remarks = mysqli_real_escape_string($con, $remarks);

        // check if product exists
        $query = "SELECT * FROM products WHERE product_id = '$product_id'";
        $result = mysqli_query($con, $query);
        if (mysqli_num_rows($result) == 0) {
            display_alert("Product does not exist");
        } else {
            $query = "UPDATE inventory SET product_id='$product_id', quantity='$quantity', remarks='$remarks' WHERE inventory_id='$inventory_id'";
            $result = mysqli_query($con, $query);

            if ($result) {
                $_SESSION['status'] = "Data Updated Successfully";
            } else {
                $_SESSION['status'] = "Data Updation is failed";
            }
        }
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


// delete data from inventory table
if (isset($_POST['delete_btn'])) {
    $inventory_id = $_POST['inventory_id'];

    // prevent sql injection
    $inventory_id = mysqli_real_escape_string($con, $inventory_id);

    $query = "DELETE FROM inventory WHERE inventory_id='$inventory_id'";
    $result = mysqli_query($con, $query);

    if ($result) {
        $_SESSION['status'] = "Data Deleted Successfully";
    } else {
        $_SESSION['status'] = "Data Deletion is failed";
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

?>

<!-- Header -->
<?php
include('includes/header.php');
include('includes/navbar.php');
?>

<!-- Page Wrapper -->
<style>
    .model {
        color: #000;
        font-weight: 600;
    }
</style>

<!-- data insert modal -->
<div class="modal fade" id="insertdata" tabindex="-1" role="dialog" aria-labelledby="insertdata" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="insertdata">Add New Inventory</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="product_id">Product ID</label>
                        <input type="text" class="form-control" id="product_id" name="product_id" placeholder="Enter Product ID" required>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity" required>
                    </div>

                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter Remarks"></textarea>
                    </div>

                    <!-- dropdown to select in or out -->
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value="in">In</option>
                            <option value="out">Out</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add" class="btn btn-primary">Add Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- view data modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModal">View Product Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('viewModal').style.display='none';">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="view_inventory">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update_btn" class="btn btn-primary">Update Data</button>
                    <button type="submit" name="delete_btn" class="btn btn-danger">Delete Data</button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- update data modal -->
<div class="modal fade" id="updatedata" tabindex="-1" role="dialog" aria-labelledby="updatedata" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatedata">Update Inventory Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('updatedata').style.display='none';">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inventory_id">Inventory ID</label>
                        <input type="text" class="form-control" id="inventory_id" name="inventory_id" placeholder="Enter Inventory ID" required>
                    </div>
                    <div class="form-group">
                        <label for="product_id">Product ID</label>
                        <input type="text" class="form-control" id="product_id" name="product_id" placeholder="Enter Product ID" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" placeholder="Enter Remarks"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="update_btn" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- delete data modal -->
<div class="modal fade" id="deletedata" tabindex="-1" role="dialog" aria-labelledby="deletedata" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- modal confirm delete (pop up message) -->
            <!--delete or cancel-->
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletedata">Delete Inventory Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('deletedata').style.display='none';">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this data?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="delete_btn" class="btn btn-danger">Delete Data</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="document.getElementById('deletedata').style.display='none';">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!------------------------ Table ------------------------>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Inventory Management
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#insertdata">
                    Add New Inventory
                </button>
            </h6>
        </div>
        <div class="card-body">
            <?php
            if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                echo '<div class="alert alert-warning">' . $_SESSION['status'] . '</div>';
                unset($_SESSION['status']);
            }
            ?>
            <div class="table-responsive">
                <?php
                $query = "SELECT * FROM inventory";
                $result = mysqli_query($con, $query);
                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Inventory ID</th>
                            <th>Product ID</th>
                            <th>Quantity</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>';
                                echo '<td>' . $row['inventory_id'] . '</td>';
                                echo '<td>' . $row['product_id'] . '</td>';
                                echo '<td>' . $row['quantity'] . '</td>';
                                echo '<td>' . $row['remarks'] . '</td>';
                                echo '<td>
                                        <button type="button" class="btn btn-primary viewbtn" data-toggle="modal" data-target="#viewModal" onclick="viewData(' . $row['inventory_id'] . ')">View</button>
                                        <button type="button" class="btn btn-success editbtn" data-toggle="modal" data-target="#updatedata" onclick="editData(' . $row['inventory_id'] . ')">Edit</button>
                                        <button type="button" class="btn btn-danger deletebtn" data-toggle="modal" data-target="#deletedata" onclick="deleteData(' . $row['inventory_id'] . ')">Delete</button>
                                      </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5">No records found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>

<script>
    $(document).ready(function() {
        function viewData(inventoryId) {
            $.ajax({
                method: "GET",
                url: "orders.php",
                data: {
                    view_btn: true,
                    product_id: inventoryId
                },
                dataType: "json",
                success: function(response) {
                    // Handle successful response here
                    if (response) {
                        $('#viewModal .view_inventory').html(`
                        <p>Product Name: ${response.product_name}</p>
                        <p>Price: ${response.price}</p>
                        <p>Quantity in Stock: ${response.quantity_in_stock}</p>
                        <p>Size: ${response.size}</p>
                        <p>Color: ${response.color}</p>
                        <p>Description: ${response.description}</p>
                        <p>Category: ${response.category_name}</p>
                        <p>Supplier: ${response.supplier_name}</p>
                        <p>Inventory Quantity: ${response.quantity}</p>
                        <p>Remarks: ${response.remarks}</p>
                    `);
                    }
                }
            });
        }

        // Bind the viewData function to the View button click
        $('.viewbtn').click(function() {
            var inventoryId = $(this).data('id');
            viewData(inventoryId);
        });
    });


    $(document).ready(function() {
        function editData(inventoryId) {
            $.ajax({
                method: "POST",
                url: "orders.php",
                data: {
                    click_edit_btn: true,
                    inventory_id: inventoryId
                },
                dataType: "json",
                success: function(response) {
                    // Handle successful response here
                    if (response) {
                        $('#updatedata #inventory_id').val(response.inventory_id);
                        $('#updatedata #product_id').val(response.product_id);
                        $('#updatedata #quantity').val(response.quantity);
                        $('#updatedata #remarks').val(response.remarks);
                    }
                }
            });
        }

        // Bind the editData function to the Edit button click
        $('.editbtn').click(function() {
            var inventoryId = $(this).data('id');
            editData(inventoryId);
        });
    });

    $(document).ready(function() {
        function deleteData(inventoryId) {
            $('#deletedata .btn-danger').click(function() {
                $.ajax({
                    method: "POST",
                    url: "orders.php",
                    data: {
                        delete_btn: true,
                        inventory_id: inventoryId
                    },
                    success: function(response) {
                        // Handle successful response here
                        if (response) {
                            location.reload(); // Reload the page to reflect the changes
                        }
                    }
                });
            });
        }

        // Bind the deleteData function to the Delete button click
        $('.deletebtn').click(function() {
            var inventoryId = $(this).data('id');
            deleteData(inventoryId);
        });
    });
</script>