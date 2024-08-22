<?php
// Start the session if needed
session_start();
include_once('./dbcon.php');

// Function to get products as JSON
function getProductsJson()
{
    global $con; // Use the global connection variable

    // Check if connection is successful
    if (!$con) {
        return json_encode(['error' => 'Connection failed']);
    }

    // Prepare the query
    $query = "SELECT product_id, product_name FROM products";
    $result = mysqli_query($con, $query);

    // Check if the query was successful
    if (!$result) {
        return json_encode(['error' => 'Query failed']);
    }

    // Fetch products
    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    // Free the result set and close the connection
    mysqli_free_result($result);
    mysqli_close($con);

    return json_encode($products);
}

// If this file is called with an AJAX request to fetch products
if (isset($_GET['action']) && $_GET['action'] == 'getProducts') {
    echo getProductsJson();
    exit;
}
?>

<?php
include_once('./includes/header.php');
include_once('./includes/navbar.php');
?>


<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header">
                    <h4 class="card-title font-weight-bold">Sales</h4>
                </div>
                <div class="card-body">
                    <form method="post" action="process_sales.php">
                        <!-- Sales Table Fields -->
                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <label for="sale_date">Sale Date</label>
                                <input type="date" class="form-control" id="sale_date" name="sale_date" required>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="total_amount">Total Amount</label>
                                <input type="number" step="0.01" class="form-control" id="total_amount" name="total_amount" required>
                            </div>
                            <div class="col-lg-12">
                                <hr>
                            </div>
                        </div>

                        <!-- Sales Items Table Fields -->
                        <div id="products-container">
                            <div class="form-row">
                                <div class="form-group col-lg-6">
                                    <label for="product_id">Product</label>
                                    <select class="form-control" id="product_id" name="product_id[]" required>
                                        <?php
                                        $products = json_decode(getProductsJson(), true);
                                        foreach ($products as $product) {
                                            echo "<option value='" . htmlspecialchars($product['product_id']) . "'>" . htmlspecialchars($product['product_name']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group col-lg-3">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity[]" required>
                                </div>

                                <div class="form-group col-lg-3">
                                    <label for="price">Price</label>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price[]" required>
                                </div>
                                <div class="col-12 d-lg-none">
                                    <hr>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-secondary" onclick="addProductRow()">Add Another Product</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    async function getProductOptions() {
        const response = await fetch('?action=getProducts');
        const data = await response.json();

        if (data.error) {
            console.error(data.error);
            return '';
        }

        return data.map(product =>
            `<option value="${product.product_id}">${product.product_name}</option>`
        ).join('');
    }

    async function addProductRow() {
        const container = document.getElementById('products-container');
        const productOptions = await getProductOptions();

        const newRow = document.createElement('div');
        newRow.className = 'form-row';
        newRow.innerHTML = `
                <div class="form-group col-lg-6">
                    <label for="product_id">Product</label>
                    <select class="form-control" name="product_id[]" required>
                        ${productOptions}
                    </select>
                </div>
                <div class="form-group col-lg-3">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control" name="quantity[]" required>
                </div>
                <div class="form-group col-lg-3">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" class="form-control" name="price[]" required>
                </div>
                <div class="col-12 d-lg-none">
                    <hr>
                </div>
            `;
        container.appendChild(newRow);
    }
</script>

<?php
include_once('./includes/footer.php');
include_once('./includes/scripts.php');
?>