  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
          document.addEventListener('DOMContentLoaded', function() {
              var timeoutDuration = 60 * 60 * 1000; // 1 hour
              var warningTime = 55 * 60 * 1000; // 5 minutes

              var sessionTimeout = setTimeout(function() {
                  // Show the Bootstrap modal
                  var myModal = new bootstrap.Modal(document.getElementById('sessionTimeoutModal'), {
                      backdrop: 'static',
                      keyboard: false
                  });
                  myModal.show();
              }, timeoutDuration - warningTime);

              var sessionEnd = setTimeout(function() {
                  window.location.href = 'logout.php'; // Redirect to logout page
              }, timeoutDuration);

              // Optional: Reset timers on user activity
              document.addEventListener('mousemove', resetTimers);
              document.addEventListener('keypress', resetTimers);

              function resetTimers() {
                  clearTimeout(sessionTimeout);
                  clearTimeout(sessionEnd);

                  sessionTimeout = setTimeout(function() {
                      var myModal = new bootstrap.Modal(document.getElementById('sessionTimeoutModal'), {
                          backdrop: 'static',
                          keyboard: false
                      });
                      myModal.show();
                  }, timeoutDuration - warningTime);

                  sessionEnd = setTimeout(function() {
                      window.location.href = 'logout.php'; // Redirect to logout page
                  }, timeoutDuration);
              }
          });
  </script>

  <?php


    $connection = mysqli_connect("localhost", "root", "", "blemishdb");

    if (isset($_POST['registerbtn'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirmpassword'];

        if ($password === $confirm_password) {
            $query = "INSERT INTO register (username,email,password) VALUES ('$username','$email','$password')";
            $query_run = mysqli_query($connection, $query);

            if ($query_run) {
                echo "done";
                $_SESSION['success'] =  "Admin is Added Successfully";
                header('Location: register.php');
            } else {
                echo "not done";
                $_SESSION['status'] =  "Admin is Not Added";
                header('Location: register.php');
            }
        } else {
            echo "pass no match";
            $_SESSION['status'] =  "Password and Confirm Password Does not Match";
            header('Location: register.php');
        }
    }

    ?>