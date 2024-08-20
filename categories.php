<?php
session_start();
include('includes/header.php');
include('includes/navbar.php');
?>

<?php 
  require('dbcon.php');
?>

<style>
    .modal {
        color: black;
        font-weight: 500;
    }

    
</style>

<!--Insert Modal Start-->
<div class="modal fade" id="insertdata" tabindex="-1" role="dialog" aria-labelledby="insertdataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="insertdataLabel">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="code.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Category Name</label>
                        <input type="text" class="form-control" name="name" placeholder="enter category name">
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="save_supp_data" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Insert Modal End-->

<!-- View Modal Start -->
<div class="modal fade" id="viewuser" tabindex="-1" role="dialog" aria-labelledby="viewuserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewuserLabel">View Category Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="view_user_data">

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--View Modal End-->

<!--Edit Modal Start-->
<div class="modal fade" id="editdata" tabindex="-1" role="dialog" aria-labelledby="editdataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editdataLabel">Edit Category Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="code.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        
                        <input type="hidden" class="form-control" id="supplier_id" name="id">
                    </div> 

                    <div class="form-group mb-3">
                        <label for="name">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="enter category name">
                    </div> 
                    <!-- id use for jquery, name use for php -->


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="update_data" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Edit Modal End-->

<div class="container-fluid  mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Success messge show start -->
            <?php
            if (isset($_SESSION['status']) && $_SESSION['status'] != '') {

            ?>

                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Hey !</strong> <?php echo $_SESSION['status']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            <?php
                unset($_SESSION['status']);
            }
            ?>
            <!-- Success messge show end -->

            <!-- Manage suppliers card -->
            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark fw-bold">MANAGE Categories</h4>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#insertdata">
                        Add Categories
                    </button>
                </div>

                <div class="card-body bg-light" style="max-height: 60vh; overflow-y: auto;">
                    <table class="table table-bordered table-hover" width="100%" p-3>
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Category Name</th>    
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $fetch_query = "SELECT * FROM categories";
                                $fetch_query_run = mysqli_query($con, $fetch_query);

                                if(mysqli_num_rows($fetch_query_run) > 0){
                                    while($row = mysqli_fetch_array($fetch_query_run)){
                                        ?>
                                        <tr>
                                            <td class="user_id"><?php echo $row['categories_id']; ?></td>
                                            <td><?php echo $row['categories_name']; ?></td>
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm view_data">View</a>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-success btn-sm edit_data">Edit</a>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-danger btn-sm delete_btn" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }                                       
                                }

                                else{
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
    $(document).ready(function(){
        $('.view_data').click(function(e){
            e.preventDefault();

            
            var user_id = $(this).closest('tr').find('.user_id').text();
            // console.log(user_id);

            $.ajax({
                method: "POST",
                url: "code.php",
                data: {
                    'click_view_btn': true,
                    'user_id': user_id,
                },
                success: function(response){
                    // console.log(response);

                    $('.view_user_data').html(response);
                    $('#viewuser').modal('show');

                }
            });


        })
    });
    //view data end


    // Edit data start
    $(document).ready(function(){
        $('.edit_data').click(function(e){
            e.preventDefault();

            
            var user_id = $(this).closest('tr').find('.user_id').text();
            // console.log(user_id);

            $.ajax({
                method: "POST",
                url: "code.php",
                data: {
                    'click_edit_btn': true,
                    'user_id': user_id,
                },
                success: function(response){
                    // console.log(response);

                    $.each(response, function(key, value){
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
    $(document).ready(function(){
        $('.delete_btn').click(function(e){
            e.preventDefault();

            
            var user_id = $(this).closest('tr').find('.user_id').text();
            // console.log(supplier_id);

            $.ajax({
                method: "POST",
                url: "code.php",
                data: {
                    'click_delete_btn': true,
                    'user_id': user_id,
                },
                success: function(response){
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





<!-- haminima start -->
<?php

?>
<!-- haminima end -->