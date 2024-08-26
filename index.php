<?php
require('dbcon.php');

use includes\header;
use includes\navbar;

include_once 'includes/header.php';
include_once 'includes/navbar.php';

// Fetch total products count
$result = $con->query("SELECT COUNT(*) AS total_products FROM product");
$totalProducts = $result->fetch_assoc()['total_products'];

// Fetch total categories count
$result = $con->query("SELECT COUNT(*) AS total_categories FROM categories");
$totalCategories = $result->fetch_assoc()['total_categories'];

// Fetch total sales amount
$result = $con->query("SELECT SUM(total_amount) AS total_sales FROM sales");
$totalSales = $result->fetch_assoc()['total_sales'];

// Fetch total orders amount
$result = $con->query("SELECT SUM(total_amount) AS total_orders FROM orders");
$totalOrders = $result->fetch_assoc()['total_orders'];

// Fetch total low stock products count (e.g., products with less than 10 units in stock)
$result = $con->query("SELECT COUNT(*) AS low_stock_count FROM inventory WHERE quantity_in_stock < 10");
$lowStockCount = $result->fetch_assoc()['low_stock_count'];

?>

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <a href="./generate_report.php?action=downloadInventoryCsv" class="d-none d-sm-inline-block btn btn-sm btn-primary rounded shadow-sm p-2"><i class="fa-solid fa-cloud-arrow-down text-white-50 mr-1"> </i>Download Inventory Report</a>
  </div>

  <!-- Content Row -->
  <div class="row">

    <!-- admin count Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Registered Admin
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <h4>
                                Total Admin: 
                                <?php
                                // SQL query to count the number of admins
                                $query = "SELECT COUNT(*) AS admin_count FROM users WHERE role='admin'";
                                
                                // Execute the query
                                $result = mysqli_query($con, $query);
                                
                                if ($result) {
                                    // Fetch the result as an associative array
                                    $row = mysqli_fetch_assoc($result);
                                    
                                    // Display the count
                                    echo htmlspecialchars($row['admin_count']);
                                } else {
                                    // In case of query failure, display 0 or an error message
                                    echo "0";
                                    // Optionally, you can log the error for debugging
                                    // error_log("Query Failed: " . mysqli_error($con));
                                }
                                ?>
                            </h4>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        <!-- Updated the icon to better represent admins -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Revenue (Annual) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card border-left-danger shadow h-100 py-2">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Revenue</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">
            <?php
            // SQL query to sum the total revenue from the sales table
            $query = "SELECT SUM(total_amount) AS total_revenue FROM sales";

            // Execute the query
            $result = mysqli_query($con, $query);

            if ($result) {
                // Fetch the result as an associative array
                $row = mysqli_fetch_assoc($result);

                // Display the total revenue
                echo "Rs. " . number_format($row['total_revenue'], 2);
            } else {
                // In case of query failure, display 0 or an error message
                echo "$0.00";
                // Optionally, you can log the error for debugging
                // error_log("Query Failed: " . mysqli_error($con));
            }
            ?>
          </div>
        </div>
        <div class="col-auto">
          <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
        </div>
      </div>
    </div>
  </div>
</div>


    <!-- Users count Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Registered Users
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <h4>
                                Total Users: 
                                <?php
                                // SQL query to count the number of admins
                                $query = "SELECT COUNT(*) AS user_count FROM users WHERE role='user'";
                                
                                // Execute the query
                                $result = mysqli_query($con, $query);
                                
                                if ($result) {
                                    // Fetch the result as an associative array
                                    $row = mysqli_fetch_assoc($result);
                                    
                                    // Display the count
                                    echo htmlspecialchars($row['user_count']);
                                } else {
                                    // In case of query failure, display 0 or an error message
                                    echo "0";
                                    // Optionally, you can log the error for debugging
                                    // error_log("Query Failed: " . mysqli_error($con));
                                }
                                ?>
                            </h4>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        <!-- Updated the icon to better represent admins -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- suppliers count Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Registered Suppliers
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <h4>
                                Suppliers: 
                                <?php
                                // SQL query to count the number of admins
                                $query = "SELECT COUNT(*) AS admin_count FROM supplier";
                                
                                // Execute the query
                                $result = mysqli_query($con, $query);
                                
                                if ($result) {
                                    // Fetch the result as an associative array
                                    $row = mysqli_fetch_assoc($result);
                                    
                                    // Display the count
                                    echo htmlspecialchars($row['admin_count']);
                                } else {
                                    // In case of query failure, display 0 or an error message
                                    echo "0";
                                    // Optionally, you can log the error for debugging
                                    // error_log("Query Failed: " . mysqli_error($con));
                                }
                                ?>
                            </h4>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                        <!-- Updated the icon to better represent admins -->
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

  <div class="container mt-5">
        <div class="row">
            <!-- Total Products Card -->
            <div class="col-md-4 d-flex align-items-stretch">
                <div class="card shadow-sm mb-4 w-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <p class="card-text h3 text-primary"><?php echo $totalProducts; ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Categories Card -->
            <div class="col-md-4 d-flex align-items-stretch">
                <div class="card shadow-sm mb-4 w-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Categories</h5>
                        <p class="card-text h3 text-primary"><?php echo $totalCategories; ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Sales Card -->
            <div class="col-md-4 d-flex align-items-stretch">
                <div class="card shadow-sm mb-4 w-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Sales Amount</h5>
                        <p class="card-text h3 text-success">$<?php echo number_format($totalSales, 2); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Total Orders Card -->
            <div class="col-md-4 d-flex align-items-stretch">
                <div class="card shadow-sm mb-4 w-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders Amount</h5>
                        <p class="card-text h3 text-success">$<?php echo number_format($totalOrders, 2); ?></p>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alerts Card -->
            <div class="col-md-4 d-flex align-items-stretch">
                <div class="card shadow-sm mb-4 w-100">
                    <div class="card-body">
                        <h5 class="card-title">Low Stock Products</h5>
                        <p class="card-text h3 text-danger"><?php echo $lowStockCount; ?></p>
                        <p class="text-muted">Products with less than 10 units in stock</p>
                    </div>
                </div>
            </div>

            <!-- Placeholder for Future Insights -->
            <div class="col-md-4 d-flex align-items-stretch">
                <div class="card shadow-sm mb-4 w-100">
                    <div class="card-body">
                        <h5 class="card-title">Future Insights</h5>
                        <p class="card-text h3 text-primary">N/A</p>
                        <p class="text-muted">More insights to be added</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <!-- Content Row -->
  <div class="row">
    <!-- Revenue Chart -->
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Total Revenue by Date</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="chart-area">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Fetch data from the sales table and pass to JavaScript -->
<?php
$query = "SELECT sale_date, SUM(total_amount) AS total_revenue FROM sales GROUP BY sale_date";
$result = mysqli_query($con, $query);

$sale_dates = [];
$total_revenues = [];

while ($row = mysqli_fetch_assoc($result)) {
    $sale_dates[] = $row['sale_date'];
    $total_revenues[] = $row['total_revenue'];
}
?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Parse PHP arrays into JavaScript
const saleDates = <?php echo json_encode($sale_dates); ?>;
const totalRevenues = <?php echo json_encode($total_revenues); ?>;

const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line', // Specify chart type (line, bar, etc.)
    data: {
        labels: saleDates, // X-axis labels
        datasets: [{
            label: 'Total Revenue',
            data: totalRevenues, // Y-axis data
            backgroundColor: 'rgba(78, 115, 223, 0.05)',
            borderColor: 'rgba(78, 115, 223, 1)',
            pointRadius: 3,
            pointBackgroundColor: 'rgba(78, 115, 223, 1)',
            pointBorderColor: 'rgba(78, 115, 223, 1)',
            pointHoverRadius: 3,
            pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
            pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
            pointHitRadius: 10,
            pointBorderWidth: 2,
            lineTension: 0.3
        }]
    },
    options: {
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            x: {
                time: {
                    unit: 'date'
                },
                grid: {
                    display: false
                },
                ticks: {
                    maxTicksLimit: 7
                }
            },
            y: {
                ticks: {
                    maxTicksLimit: 5,
                    padding: 10,
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return '$' + value.toFixed(2);
                    }
                },
                grid: {
                    color: "rgb(234, 236, 244)",
                    zeroLineColor: "rgb(234, 236, 244)",
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                }
            }
        },
        legend: {
            display: false
        },
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: 'index',
            caretPadding: 10,
            callbacks: {
                label: function(tooltipItem, chart) {
                    return '$' + tooltipItem.yLabel.toFixed(2);
                }
            }
        }
    }
});
</script>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
