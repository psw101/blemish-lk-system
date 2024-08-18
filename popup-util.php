<?php
function display_alert($message, $is_success = false) {
    $alert_type = $is_success ? 'success' : 'danger';
    
    echo "
    <div class='alert alert-$alert_type alert-dismissible fade show' role='alert' style='
        position: fixed;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        padding: 0.5rem;
        margin: 0;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: auto;
        max-width: calc(100% - 2rem); /* Adjusted for padding gap */
    '>
        <span>$message</span>
    </div>
    <script>
        setTimeout(function() {
            var alert = document.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(function() {
                    alert.remove();
                }, 1500); // Time for fade effect to complete
            }
        }, 2000); // Time before alert starts fading out
    </script>
    ";
}


?>


