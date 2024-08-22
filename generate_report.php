<?php
// Start the session if needed
session_start();
include_once('./dbcon.php');

// Function to retrieve and download inventory data as a CSV
function downloadInventoryCsv()
{
    global $con; // Use the global connection variable

    // Check if connection is successful
    if (!$con) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // Query to get the inventory data
    $query = "SELECT i.product_id, p.product_name, i.quantity_in_stock, p.sellPrice AS price
              FROM inventory i
              JOIN product p ON i.product_id = p.product_id";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die('Query failed: ' . mysqli_error($con));
    }

    // Calculate total product types, total quantity, and total value
    $totalProductTypes = 0;
    $totalQuantity = 0;
    $totalValue = 0;
    $inventoryData = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $inventoryData[] = $row;
        $totalProductTypes++;
        $totalQuantity += $row['quantity_in_stock'];
        $totalValue += $row['quantity_in_stock'] * $row['price'];
    }

    // Free the result set
    mysqli_free_result($result);

    // Set CSV headers
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="inventory.csv"');

    // Open the output stream
    $output = fopen('php://output', 'w');

    // Write the headers
    fputcsv($output, ['Product ID', 'Product Name', 'Quantity in Stock', 'Price per Unit', 'Total Value']);

    // Write the inventory data
    foreach ($inventoryData as $data) {
        $totalValueForProduct = $data['quantity_in_stock'] * $data['price'];
        fputcsv($output, [$data['product_id'], $data['product_name'], $data['quantity_in_stock'], $data['price'], $totalValueForProduct]);
    }

    // Write the summary row
    fputcsv($output, []);
    fputcsv($output, ['Total Product Types', 'Total Quantity', 'Total Inventory Value']);
    fputcsv($output, [$totalProductTypes, $totalQuantity, $totalValue]);

    // Close the output stream
    fclose($output);

    // Close the connection
    mysqli_close($con);
}

// If this file is called to download the inventory as CSV
if (isset($_GET['action']) && $_GET['action'] == 'downloadInventoryCsv') {
    downloadInventoryCsv();
    exit;
}
?>
