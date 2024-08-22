<!-- Bootstrap Modal for session timeout warning -->
<div class="modal fade" id="sessionTimeoutModal" tabindex="-1" aria-labelledby="sessionTimeoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionTimeoutModalLabel">Session Timeout Warning</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Your session is about to expire. Please save your work to avoid losing any unsaved data.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var timeoutDuration = 60 * 60 * 1000; // 1 minute
        var warningTime = 55 * 60 * 1000; // 55 minutes

        var sessionTimeout = setTimeout(function() {
            // Show the Bootstrap modal
            // The modal has the ID 'sessionTimeoutModal'
            // timeout is set to 5 minutes before the session ends
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

include_once('./dbcon.php');

if (isset($_POST['registerbtn'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmpassword'];

    if ($password === $confirm_password) {
        $query = "INSERT INTO register (username,email,password) VALUES ('$username','$email','$password')";
        $query_run = mysqli_query($con, $query);

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