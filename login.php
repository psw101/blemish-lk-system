<?php
// Path: login.php
session_start(); // Start the session at the very beginning

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  header("Location: ./session_info.php");
  exit();
}


// Initialize the variables - these are used for alert messages
$showModal = false;
$modalMessage = "";

// Include the configuration file
include_once './session-config.php';
include_once './popup-util.php';
// Initialize variables
$username = $password = "";
$username_err = $password_err = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Validate username
  if (empty(trim($_POST["username"]))) {
    $username_err = "Please enter a username.";
  } else {
    $username = trim($_POST["username"]);
  }

  // Validate password
  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter a password.";
  } else {
    $password = trim($_POST["password"]);
  }

  // If there are no errors, process the data
  if (empty($username_err) && empty($password_err)) {

    // Hash the input password
    $password_hash = hash('sha256', $password);

    // Prepare and execute the SQL statement with prepared statements to prevent SQL injection
    include_once './dbcon.php';


    // Prepare the SQL statement
    $sql = "SELECT username, password FROM users WHERE username = ?";
    if ($stmt = mysqli_prepare($con, $sql)) {
      // Bind the input parameter to the prepared statement
      mysqli_stmt_bind_param($stmt, "s", $param_username);
      $param_username = $username;

      // Execute the statement
      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        // Check if the username exists, if yes then verify the password
        if (mysqli_stmt_num_rows($stmt) == 1) {
          // Bind the result variables
          mysqli_stmt_bind_result($stmt, $db_username, $db_password_hash);

          if (mysqli_stmt_fetch($stmt)) {
            $db_password_hash = strtolower($db_password_hash);
            if ($password_hash === $db_password_hash) {
              // Password is correct, so start a new session
              $_SESSION['loggedin'] = true;
              $_SESSION['username'] = $username;
              $_SESSION['user_id'] = $user_id;

              header("Location: ./index.php");
              exit();
            } else {
              $showModal = true;
              $modalMessage = "Password is incorrect!";
            }
          }
        } else {
          $showModal = true;
          $modalMessage = "Username is incorrect!";
        }
      } else {
        $showModal = true;
        $modalMessage = "Oops! Something went wrong. Please try again later.";
      }

      // Close the statement
      mysqli_stmt_close($stmt);
    }
  }else{
    $showModal = true;
    $modalMessage = "Please enter a username and password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blemish.lk - Login Page</title>
  <link rel="icon" href="./img/site-logo.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <style>
    body {
      background-color: #077fa9;
    }
    .modal-header-custom {
      background-color: #f8d7da; /* Light red background */
      color: #721c24; /* Dark red text */
    }
  </style>
</head>

<body>
  <section class="vh-100">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-md-6 col-lg-5 d-none d-md-block">
                <img src="./img/login-page-fashion.jpg" alt="login form" class="img-fluid " style="border-radius: 1rem 0 0 1rem;" />
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="d-flex align-items-center mb-3 pb-1">
                      <img src="./img/site-logo.png" alt="logo" width="20%" style="border-radius: 50%; display: block;">
                      <span class="h1 fw-bold mb-0">Blemish.lk</span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>

                    <div class="form-outline mb-4">
                      <input type="text" name="username" id="username" class="form-control form-control-lg" />
                      <label class="form-label" for="username">Username</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="password" name="password" id="password" class="form-control form-control-lg" />
                      <label class="form-label" for="password">Password</label>
                    </div>

                    <div class="pt-1 mb-4">
                      <button class="btn btn-primary btn-block btn-lg px-5" type="submit">Login</button>
                    </div>

                    <a class="small text-muted" href="#!">Forgot password?</a>
                    <p class="small text-muted">blemish.lk @ 2024</p>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Modal to show messages -->
  <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header modal-header-custom">
          <h5 class="modal-title" id="alertModalLabel">Alert</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php echo $modalMessage; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Include Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

  <!-- Show the modal if the $showModal variable is set to true -->
  <?php if ($showModal): ?>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('alertModal'));
        myModal.show();
      });
    </script>
  <?php endif; ?>
</body>
</html>
