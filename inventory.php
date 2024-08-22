<?php
session_start();
require('dbcon.php');

/* code.php file start */


/* code.php FILE End */

?>


<!-- ................................................................................supplier.php file start............................................................ -->

<?php
include('includes/header.php');
include('includes/navbar.php');
?>





<style>
    .modal {
        color: black;
        font-weight: 500;
    }
</style>



<div class="container-fluid  mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Manage suppliers card -->
            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark fw-bold">INVENTORY</h4>
                    
                </div>

                <div class="card-body bg-light" style="max-height: 60vh; overflow-y: auto;">
                    <table class="table table-bordered table-hover" width="100%" p-3>
                        <thead>
                            <tr>
                                <th scope="col">Product ID</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $fetch_query = "SELECT * FROM supplier";
                            $fetch_query_run = mysqli_query($con, $fetch_query);

                            if (mysqli_num_rows($fetch_query_run) > 0) {
                                while ($row = mysqli_fetch_array($fetch_query_run)) {
                            ?>
                                    <tr>
                                        <td class="user_id"><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr colspan="4">No Record Found</tr>
                            <?php

                            }

                            ?>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>


<?php
include('includes/scripts.php');
include('includes/footer.php');
?>

<script>
    //view data start
    $(document).ready(function() {
        $('.view_data').click(function(e) {
            e.preventDefault();


            var user_id = $(this).closest('tr').find('.user_id').text();
            // console.log(user_id);

            $.ajax({
                method: "POST",
                url: "supplier.php",
                data: {
                    'click_view_btn': true,
                    'user_id': user_id,
                },
                success: function(response) {
                    // console.log(response);

                    $('.view_user_data').html(response);
                    $('#viewuser').modal('show');

                }
            });


        })
    });
    //view data end


    // Edit data start
    $(document).ready(function() {
        $('.edit_data').click(function(e) {
            e.preventDefault();


            var user_id = $(this).closest('tr').find('.user_id').text();
            // console.log(user_id);

            $.ajax({
                method: "POST",
                url: "supplier.php",
                data: {
                    'click_edit_btn': true,
                    'user_id': user_id,
                },
                success: function(response) {
                    // console.log(response);

                    $.each(response, function(key, value) {
                        // console.log(value['name']);
                        $('#supplier_id').val(value['id']);
                        $('#name').val(value['name']);
                        $('#email').val(value['email']);
                        $('#phone').val(value['phone']);
                        $('#address').val(value['address']);
                        //id,name,email,.. are database column names.    user_id,name,email,.. are form's field ids used in modal

                    });


                    $('#editdata').modal('show');

                }
            });


        })
    });
    // Edit data end

    //Delete data start
    $(document).ready(function() {
        $('.delete_btn').click(function(e) {
            e.preventDefault();


            var user_id = $(this).closest('tr').find('.user_id').text();
            // console.log(supplier_id);

            $.ajax({
                method: "POST",
                url: "supplier.php",
                data: {
                    'click_delete_btn': true,
                    'user_id': user_id,
                },
                success: function(response) {
                    console.log(response);
                    window.location.reload();

                    // $('.view_user_data').html(response);
                    // $('#viewuser').modal('show');

                }
            });



        })
    });
    //Delete data end
</script>