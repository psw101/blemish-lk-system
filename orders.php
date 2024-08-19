<?php
include_once('./dbcon.php');
include_once('./popup-util.php');

//delete order
if (isset($_POST['delete_order'])) {
    $order_id = $_POST['delete_id'];
    $query = "DELETE FROM orders WHERE order_id = $order_id";
    $query_run = mysqli_query($con, $query);
    if ($query_run) {
        display_alert("Order is deleted successfully");
    } else {
        display_alert("Order is not deleted");
    }
    sleep(3);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

//delete order item
if (isset($_POST['delete_order_item'])) {
    $order_item_id = $_POST['delete_id'];
    $query = "DELETE FROM order_items WHERE order_item_id = $order_item_id";
    $query_run = mysqli_query($con, $query);
    if ($query_run) {
        display_alert("Order item is deleted successfully");
    } else {
        display_alert("Order item is not deleted");
    }
    sleep(3);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

//add order
if (isset($_POST['add'])) {
    $order_date = $_POST['order_date'];
    $total_amount = $_POST['total_amount'];
    $supplier_id = $_POST['supplier_id'];

    //prevent sql injection
    $order_date = mysqli_real_escape_string($con, $order_date);
    $total_amount = mysqli_real_escape_string($con, $total_amount);
    $supplier_id = mysqli_real_escape_string($con, $supplier_id);

    //validation
    if (empty($order_date) || empty($total_amount) || empty($supplier_id)) {
        $_SESSION['status'] = "All fields are required";
    } else {
        $query = "INSERT INTO orders (order_date, total_amount, supplier_id) VALUES ('$order_date', '$total_amount', '$supplier_id')";
        $query_run = mysqli_query($con, $query);
        if ($query_run) {
            display_alert("Order is added successfully");
        } else {
            display_alert("Order is not added");
        }
    }
    sleep(3);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

//add order item
if (isset($_POST['add_order_item'])) {
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $total_price = $quantity * $price;

    //prevent sql injection
    $order_id = mysqli_real_escape_string($con, $order_id);
    $product_id = mysqli_real_escape_string($con, $product_id);
    $quantity = mysqli_real_escape_string($con, $quantity);
    $price = mysqli_real_escape_string($con, $price);
    $total_price = mysqli_real_escape_string($con, $total_price);

    //validation
    if (empty($order_id) || empty($product_id) || empty($quantity) || empty($price)) {
        $_SESSION['status'] = "All fields are required";
    } else {
        $query = "INSERT INTO order_items (order_id, product_id, quantity, price, total_price) VALUES ('$order_id', '$product_id', '$quantity', '$price', '$total_price')";
        $query_run = mysqli_query($con, $query);
        if ($query_run) {
            display_alert("Order item is added successfully");
        } else {
            display_alert("Order item is not added");
        }
    }
    sleep(3);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


//update order
if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $order_date = $_POST['order_date'];
    $total_amount = $_POST['total_amount'];
    $status = $_POST['status'];
    $query = "UPDATE orders SET order_date = '$order_date', total_amount = '$total_amount', status = '$status' WHERE order_id = $order_id";
    $query_run = mysqli_query($con, $query);
    if ($query_run) {
        $_SESSION['success'] = "Order is updated successfully";
        header('Location: orders.php');
    } else {
        $_SESSION['status'] = "Order is not updated";
        header('Location: orders.php');
    }
}

//update order item
if (isset($_POST['update_order_item'])) {
    $order_item_id = $_POST['order_item_id'];
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $total_price = $_POST['total_price'];
    $query = "UPDATE order_items SET order_id = '$order_id', product_id = '$product_id', quantity = '$quantity', price = '$price', total_price = '$total_price' WHERE order_item_id = $order_item_id";
    $query_run = mysqli_query($con, $query);
    if ($query_run) {
        $_SESSION['success'] = "Order item is updated successfully";
        header('Location: orders.php');
    } else {
        $_SESSION['status'] = "Order item is not updated";
        header('Location: orders.php');
    }
}


function generateDeleteConfirmationModal($modalId, $buttonText, $actionUrl)
{
    echo '
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="' . $modalId . '" tabindex="-1" role="dialog" aria-labelledby="' . $modalId . 'Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="' . $modalId . 'Label">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="' . $actionUrl . '" method="POST" style="display:inline;">
                        <button type="submit" class="btn btn-danger">' . $buttonText . '</button>
                    </form>
                </div>
            </div>
        </div>
    </div>';
}


?>

<?php
include('includes/header.php');
include('includes/navbar.php');
?>
<!-- show orders table using php-->
<div class="container-fluid  mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- show orders table -->
            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark font-weight-bold">Orders</h4>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addOrderModal" id="addOrderButton">
                        Add Order
                    </button>
                </div>
                <div class="card-body bg-light" style="max-height: 60vh;
                overflow-y: auto;">
                    <table class="table table-bordered table-hover" width="100%" p-3>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Total Amount</th>
                                <th>Supplier</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM orders";
                            $query_run = mysqli_query($con, $query);
                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
                            ?>
                                    <tr>
                                        <td><?php echo $row['order_id']; ?></td>
                                        <td><?php echo $row['order_date']; ?></td>
                                        <td><?php echo $row['total_amount']; ?></td>
                                        <td><?php
                                            $query_for_supplier = "SELECT name FROM supplier WHERE id = " . $row['supplier_id'];
                                            echo mysqli_fetch_assoc(mysqli_query($con, $query_for_supplier))['name'];
                                            ?></td>
                                        <td>
                                            <button type="button" class="btn btn-success editbtn">Edit</button>
                                            <button type="button" class="btn btn-danger deletebtn">Delete</button>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "No Record Found";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- order items table -->
<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark font-weight-bold">Order Items</h4>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addorderitem">
                        Add Order Item
                    </button>
                </div>
                <div class="card-body bg-light" style="max-height: 60vh;
                overflow-y: auto;">
                    <table class="table table-bordered table-hover" width="100%" p-3>
                        <thead>
                            <tr>
                                <th>Order Item ID</th>
                                <th>Order ID</th>
                                <th>Product ID</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM order_items";
                            $query_run = mysqli_query($con, $query);
                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
                            ?>
                                    <tr>
                                        <td><?php echo $row['order_item_id']; ?></td>
                                        <td><?php echo $row['order_id']; ?></td>
                                        <td><?php echo $row['product_id']; ?></td>
                                        <td><?php echo $row['quantity']; ?></td>
                                        <td><?php echo $row['price']; ?></td>
                                        <td><?php echo $row['total_price']; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-success editbtn">Edit</button>
                                            <button type="button" class="btn btn-danger deletebtn">Delete</button>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "No Record Found";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Order Modal Start -->
<div class="modal fade" id="addOrderModal" tabindex="-1" role="dialog" aria-labelledby="addOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrderModalLabel">Add Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="order_date">Order Date</label>
                        <input type="date" class="form-control" name="order_date" placeholder="Enter order date" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="total_amount">Total Amount</label>
                        <input type="number" class="form-control" name="total_amount" step="0.01" placeholder="Enter total amount" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="supplier_id">Supplier ID</label>
                        <input type="number" class="form-control" name="supplier_id" placeholder="Enter supplier ID" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="add" class="btn btn-primary">Add Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Order Modal End -->


<!-- Add Order Item Modal Start -->
<div class="modal fade" id="addorderitem" tabindex="-1" role="dialog" aria-labelledby="addOrderItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrderItemModalLabel">Add Order Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="order_id">Order ID</label>
                        <input type="text" class="form-control" name="order_id" placeholder="Enter order ID" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="product_id">Product ID</label>
                        <input type="text" class="form-control" name="product_id" placeholder="Enter product ID" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" name="quantity" placeholder="Enter quantity" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" name="price" step="0.01" placeholder="Enter price per unit" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="add_order_item" class="btn btn-primary">Add Order Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Order Item Modal End -->







<?php
include('includes/scripts.php');
include('includes/footer.php');
?>


<script>

</script>
