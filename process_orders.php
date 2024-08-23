<?php
// Start the session if needed
session_start();
include_once('./dbcon.php');

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['order_date']) && isset($_POST['total_amount']) && isset($_POST['product_id']) && isset($_POST['quantity']) && isset($_POST['price'])) {
        // Get order details
        $order_date = mysqli_real_escape_string($con, $_POST['order_date']);
        $total_amount = floatval($_POST['total_amount']);
        
        // Insert the new order
        $orderQuery = "INSERT INTO orders (order_date, total_amount) VALUES ('$order_date', $total_amount)";
        if (mysqli_query($con, $orderQuery)) {
            // Get the last inserted order ID
            $order_id = mysqli_insert_id($con);
            
            // Prepare to insert order items
            $product_ids = $_POST['product_id'];
            $quantities = $_POST['quantity'];
            $prices = $_POST['price'];
            
            $orderItemsQuery = "INSERT INTO order_items (order_id, product_id, quantity, price, total_price) VALUES ";
            $orderItemsValues = [];
            
            for ($i = 0; $i < count($product_ids); $i++) {
                $product_id = intval($product_ids[$i]);
                $quantity = intval($quantities[$i]);
                $price = floatval($prices[$i]);
                $total_price = $quantity * $price;
                
                $orderItemsValues[] = "($order_id, $product_id, $quantity, $price, $total_price)";
            }
            
            $orderItemsQuery .= implode(', ', $orderItemsValues);
            
            if (mysqli_query($con, $orderItemsQuery)) {
                // Redirect to a success page or show a success message
                echo "<script>window.location.href = 'orders-new.php?showModal=true';</script>";
            } else {
                // If there's an error inserting order items
                echo "<script>alert('Error processing order items: " . mysqli_error($con) . "'); window.location.href='orders-new.php';</script>";
            }
        } else {
            // If there's an error inserting the order
            echo "<script>alert('Error processing order: " . mysqli_error($con) . "'); window.location.href='orders-new.php';</script>";
        }
    } else {
        // Form fields are missing
        echo "<script>alert('Missing form fields.'); window.location.href='orders-new.php';</script>";
    }
    
    // Close the connection
    mysqli_close($con);
} else {
    // If the request method is not POST
    echo "<script>alert('Invalid request method.'); window.location.href='orders-new.php';</script>";
}
?>
