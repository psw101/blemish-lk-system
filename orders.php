<!-- -- Orders Table (Modified with 'status' column)
CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_date DATE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL
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
//toggle status
if (isset($_POST['update_order_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $query = "UPDATE orders SET status = '$status' WHERE order_id = $order_id";
    $query_run = mysqli_query($con, $query);
    if ($query_run) {
        $_SESSION['success'] = "Order status is updated successfully";
        header('Location: orders.php');
    } else {
        $_SESSION['status'] = "Order status is not updated";
        header('Location: orders.php');
    }
}


//delete order
if (isset($_POST['delete_order'])) {
    $order_id = $_POST['delete_id'];
    $query = "DELETE FROM orders WHERE order_id = $order_id";
    $query_run = mysqli_query($con, $query);
    if ($query_run) {
        $_SESSION['success'] = "Order is deleted successfully";
        header('Location: orders.php');
    } else {
        $_SESSION['status'] = "Order is not deleted";
        header('Location: orders.php');
    }
}

//delete order item
if (isset($_POST['delete_order_item'])) {
    $order_item_id = $_POST['delete_id'];
    $query = "DELETE FROM order_items WHERE order_item_id = $order_item_id";
    $query_run = mysqli_query($con, $query);
    if ($query_run) {
        $_SESSION['success'] = "Order item is deleted successfully";
        header('Location: orders.php');
    } else {
        $_SESSION['status'] = "Order item is not deleted";
        header('Location: orders.php');
    }
}

//add order and order items
if (isset($_POST['add_order'])) {
    $order_date = $_POST['order_date'];
    $total_amount = $_POST['total_amount'];
    $status = $_POST['status'];
    $query = "INSERT INTO orders (order_date, total_amount, status) VALUES ('$order_date', '$total_amount', '$status')";
    $query_run = mysqli_query($con, $query);
    if ($query_run) {
        $order_id = mysqli_insert_id($con);
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $total_price = $_POST['total_price'];
        for ($i = 0; $i < count($product_id); $i++) {
            $query = "INSERT INTO order_items (order_id, product_id, quantity, price, total_price) VALUES ('$order_id', '$product_id[$i]', '$quantity[$i]', '$price[$i]', '$total_price[$i]')";
            $query_run = mysqli_query($con, $query);
        }
        if ($query_run) {
            $_SESSION['success'] = "Order is added successfully";
            header('Location: orders.php');
        } else {
            $_SESSION['status'] = "Order is not added";
            header('Location: orders.php');
        }
    } else {
        $_SESSION['status'] = "Order is not added";
        header('Location: orders.php');
    }
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
                                            $query_for_supplier = "SELECT name FROM supplier WHERE id = ".$row['supplier_id'];
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
                    
                                

            
<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
