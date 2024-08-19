<!-- 
-- Orders Table (Modified with 'supplier_id' column)
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_date DATE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    supplier_id INT,
    FOREIGN KEY (supplier_id) REFERENCES supplier(id)
);

-- Order Items Table
CREATE TABLE order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
); -->

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

//add order items
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
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addorder">
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



<?php
include('includes/scripts.php');
include('includes/footer.php');
?>