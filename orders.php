<?php
session_start();
include_once('./dbcon.php');
include_once('./popup-util.php');
// view order
if (isset($_POST['view_order'])) {
    $order_id = $_POST['id'];

    // Prepared statement to prevent SQL injection
    $query = $con->prepare("SELECT * FROM orders WHERE order_id = ?");
    $query->bind_param("i", $order_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 0) {
        echo "Order not found.";
        exit;
    } else {
        $order_data = $result->fetch_assoc();

        $query_for_find_products = $con->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $query_for_find_products->bind_param("i", $order_id);
        $query_for_find_products->execute();
        $order_items = $query_for_find_products->get_result()->fetch_all(MYSQLI_ASSOC);

        $supplier_query = $con->prepare("SELECT name FROM supplier WHERE id = ?");
        $supplier_query->bind_param("i", $order_data['supplier_id']);
        $supplier_query->execute();
        $supplier_name = $supplier_query->get_result()->fetch_assoc()['name'];

        $output = '<p><b>Order ID: </b>' . $order_data['order_id'] . '</p>'
            . '<p><b>Order Date: </b>' . $order_data['order_date'] . '</p>'
            . '<p><b>Total Amount: </b>' . $order_data['total_amount'] . '</p>'
            . '<p><b>Supplier: </b>' . $supplier_name . '</p>'
            . '<p><b>Order Items: </b></p>';

        $output .= '<div style="padding-left: 2%;">';

        // Query to get items for a specific order_id
        $query_for_find_item = "SELECT product_id, quantity, price FROM order_items WHERE order_id = ?";
        $stmt = $con->prepare($query_for_find_item);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $query_run = $stmt->get_result();

        if ($query_run->num_rows > 0) {
            while ($row = $query_run->fetch_assoc()) {
                // Query to get product name
                $product_query = "SELECT product_name FROM products WHERE product_id = ?";
                $product_stmt = $con->prepare($product_query);
                $product_stmt->bind_param("i", $row['product_id']);
                $product_stmt->execute();
                $product_result = $product_stmt->get_result();
                $product_name = $product_result->fetch_assoc()['product_name'];

                $output .= '<p">' .
                    htmlspecialchars($product_name) . ' - ' .
                    'Quantity: ' . htmlspecialchars($row['quantity']) . ', ' .
                    'Price: $' . number_format($row['price'], 2) .
                    '</p>';
            }
        } else {
            $output .= 'No Record Found';
        }

        $output .= '</div>';

        echo $output;
    }
    exit;
}

//view order-item details (function)
if (isset($_POST['view_order_item'])) {
    $order_item_id = $_POST['id'];

    // Prepared statement to prevent SQL injection
    $query = $con->prepare("SELECT * FROM order_items WHERE order_item_id = ?");
    $query->bind_param("i", $order_item_id);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 0) {
        echo "Order item not found.";
        exit;
    } else {
        $product_name_query = $con->prepare("SELECT product_name FROM products WHERE product_id = ?");
        $order_item_data = $result->fetch_assoc();
        $product_id = $order_item_data['product_id'];
        $product_name_query->bind_param("i", $product_id);
        $product_name_query->execute();
        $product_name = $product_name_query->get_result()->fetch_assoc()['product_name'];

        $output = '<p><b>Order Item ID: </b>' . $order_item_data['order_item_id'] . '</p>'
            . '<p><b>Order ID: </b>' . $order_item_data['order_id'] . '</p>'
            . '<p><b>Product ID: </b>' . $order_item_data['product_id'] . '</p>'
            . '<p><b>Product Name: </b>' . $product_name . '</p>'
            . '<p><b>Quantity: </b>' . $order_item_data['quantity'] . '</p>'
            . '<p><b>Price: </b>' . $order_item_data['price'] . '</p>'
            . '<p><b>Total Price: </b>' . $order_item_data['total_price'] . '</p>';

        echo $output;
    }
    exit;
}


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
            $_SESSION['status'] = "Data inserted successfully!";
        } else {
            $_SESSION['status'] = "Insertion of data failed!";
        }
    }
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
            $_SESSION['status_2'] = "Data inserted successfully!";
        } else {
            $_SESSION['status_2'] = "Insertion of data failed!";
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


//edit order
if (isset($_POST['edit_order'])) {
    $order_id = $_POST['order_id'];
    $arrayResult = [];

    // Fetch order details from the database
    $fetch_query = "SELECT * FROM orders WHERE order_id = '$order_id'";
    $fetch_query_run = mysqli_query($con, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_assoc($fetch_query_run)) {
            // Push the fetched order details into the result array
            array_push($arrayResult, $row);
        }
        // Send the response as a JSON object
        header('Content-Type: application/json');
        echo json_encode($arrayResult);
        exit;
    } else {
        // No record found case
        echo json_encode(['status' => 'No record found']);
    }
}

//update order - data from edit order modal
if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $order_date = $_POST['order_date'];
    $total_amount = $_POST['total_amount'];
    $supplier_id = $_POST['supplier_id'];

    // Prepare the SQL update query
    $update_query = "UPDATE orders 
                     SET order_date = ?, total_amount = ?, supplier_id = ? 
                     WHERE order_id = ?";

    // Initialize prepared statement
    $stmt = mysqli_prepare($con, $update_query);

    if ($stmt) {
        // Bind parameters to the prepared statement
        // 's' for string, 'd' for decimal, 'i' for integer
        mysqli_stmt_bind_param($stmt, "sdii", $order_date, $total_amount, $supplier_id, $order_id);

        // Execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Order updated successfully";
        } else {
            $_SESSION['status'] = "Order update failed";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['status'] = "Query preparation failed";
        
    }

    header('Location: orders.php');
    exit();
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
            <?php
            if (isset($_SESSION['status']) && $_SESSION['status'] != '') {

            ?>

                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Alert - </strong> <?php echo $_SESSION['status']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            <?php
                unset($_SESSION['status']);
            }
            ?>
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
                                        <td class="order_id_cls"><?php echo $row['order_id']; ?></td>
                                        <td><?php echo $row['order_date']; ?></td>
                                        <td><?php echo $row['total_amount']; ?></td>
                                        <td><?php
                                            $query_for_supplier = "SELECT name FROM supplier WHERE id = " . $row['supplier_id'];
                                            echo mysqli_fetch_assoc(mysqli_query($con, $query_for_supplier))['name'];
                                            ?></td>
                                        <td style="white-space: nowrap;">
                                            <button type="button" class="btn btn-success editbtn">Edit</button>
                                            <button type="button" class="btn btn-primary viewbtn" data-toggle="modal" data-target="#viewOrderModal">View</button>
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
            <?php
            if (isset($_SESSION['status_2']) && $_SESSION['status_2'] != '') {

            ?>

                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Alert :</strong> <?php echo $_SESSION['status_2']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            <?php
                unset($_SESSION['status_2']);
            }
            ?>
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
                                        <td class="order_item_id_cls"><?php echo $row['order_item_id']; ?></td>
                                        <td><?php echo $row['order_id']; ?></td>
                                        <td><?php echo $row['product_id']; ?></td>
                                        <td><?php echo $row['quantity']; ?></td>
                                        <td><?php echo $row['price']; ?></td>
                                        <td><?php echo $row['total_price']; ?></td>
                                        <td style="white-space: nowrap;">
                                            <button type="button" class="btn btn-success editbtn_2">Edit</button>
                                            <button type="button" class="btn btn-primary viewbtn_2">View</button>
                                            <button type="button" class="btn btn-danger deletebtn_2">Delete</button>
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


<!-- view order modal -->
<div class="modal fade" id="viewOrderModal" tabindex="-1" role="dialog" aria-labelledby="viewuserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewuserLabel">View Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="view_order_data">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- view order-items model -->
<div class="modal fade" id="viewOrderItemModal" tabindex="-1" role="dialog" aria-labelledby="viewuserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewuserLabel">View Order Item Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="view_order_item_data">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Order Modal Start -->
<div class="modal fade" id="editOrderModal" tabindex="-1" role="dialog" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOrderModalLabel">Edit Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="modal-body">
                    <!-- Hidden field to store the Order ID -->
                    <input type="hidden" class="form-control" id="order_id" name="order_id">

                    <div class="form-group mb-3">
                        <label for="order_date">Order Date</label>
                        <input type="date" class="form-control" id="order_date" name="order_date" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="total_amount">Total Amount</label>
                        <input type="number" step="0.01" class="form-control" id="total_amount" name="total_amount" placeholder="Enter Total Amount" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="supplier_id">Supplier Id</label>
                        <input type="number" class="form-control" id="supplier_id" name="supplier_id" placeholder="Enter Supplier ID" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="update_order" class="btn btn-primary">Update Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Order Modal End -->


<?php
include('includes/scripts.php');
include('includes/footer.php');
?>


<script>
    $(document).ready(function() {

        function handleViewButtonClick(buttonClass, idClass, ajaxData, modalSelector, contentSelector) {
            $(buttonClass).click(function(e) {
                e.preventDefault();

                var id = $(this).closest('tr').find(idClass).text();
                console.log(id);

                if (!id) {
                    alert('ID not found');
                    return;
                }

                $.ajax({
                    method: "POST",
                    url: "orders.php",
                    data: ajaxData(id),
                    success: function(response) {
                        $(contentSelector).html(response);
                        $(modalSelector).modal('show');
                    }
                });
            });
        }

        handleViewButtonClick('.viewbtn', '.order_id_cls',
            function(id) {
                return {
                    'view_order': true,
                    'id': id
                };
            },
            '#viewOrderModal', '.view_order_data'
        );

        handleViewButtonClick('.viewbtn_2', '.order_item_id_cls',
            function(id) {
                return {
                    'view_order_item': true,
                    'id': id
                };
            },
            '#viewOrderItemModal', '.view_order_item_data'
        );

    });

    $(document).ready(function() {
        $('.editbtn').click(function(e) {
            e.preventDefault();

            // Retrieve the order_id from the clicked row
            var order_id = $(this).closest('tr').find('.order_id_cls').text();
            console.log(order_id);

            // Make an AJAX request to fetch order details
            $.ajax({
                method: "POST",
                url: "orders.php", // Adjust the URL to your backend script
                data: {
                    'edit_order': true,
                    'order_id': order_id,
                },
                success: function(response) {
                    // Assuming the response is a JSON object with order details
                    $.each(response, function(key, value) {
                        $('#order_id').val(value['order_id']);
                        $('#order_date').val(value['order_date']);
                        $('#total_amount').val(value['total_amount']);
                        $('#supplier_id').val(value['supplier_id']);
                    });

                    // Show the modal with order data populated
                    $('#editOrderModal').modal('show');
                }
            });
        });
    });
</script>