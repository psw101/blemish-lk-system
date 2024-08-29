// Database configuration
$host = 'blemishweb-server.mysql.database.azure.com';
$port = 3306;
$username = 'yapjsmkanw';
$password = 'RPpM2nI7t7i1Nw$4';
$dbname = 'blemish_inventory';

// Path to your SSL certificate
$ssl_ca = '/home/site/wwwroot/ca-cert.pem'; // Ensure this path is correct

// Create connection with SSL
$conn = new mysqli();
$conn->ssl_set(null, null, $ssl_ca, null, null);
$conn->real_connect($host, $username, $password, $dbname, $port, null, MYSQLI_CLIENT_SSL);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}
