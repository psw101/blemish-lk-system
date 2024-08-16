<?php
session_start();
include('includes/header.php'); 
include('includes/navbar.php'); 

?>

<?php 
  require('dbcon.php');
?>

<!-- Add New Supplier Modal -->
<div class="modal fade" id="addadminprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  
  

  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Products</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="code.php" method="POST">

        <div class="modal-body">

            <div class="form-group">
                <label> Supplier Name </label>
                <input type="text" name="name" class="form-control" placeholder="Enter Supplier Name">
            </div>
            <div class="form-group">
                <label>Supplier Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter Email">
            </div>
            <div class="form-group">
                <label>Contact Number</label>
                <input type="password" name="phone" class="form-control" placeholder="Enter Contact Number">
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="password" name="address" class="form-control" placeholder="Enter Address">
            </div>
        
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="add_supplier" class="btn btn-primary">Save</button>
        </div>
      </form>

    </div>
  </div>
</div>




<div class="container-fluid">

<!-- DataTales Example -->
<div class="card shadow mb-4">
  <div class="card-header py-3 d-flex justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">Manage Suppliers</h6>
    <button type="button" class="btn btn-primary f" data-toggle="modal" data-target="#addadminprofile">
              Add New Suppliers
    </button>
    
  </div>

  <div class="card-body">

    <div class="table-responsive">

      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th> ID </th>
            <th>Name </th>
            <th>Email </th>
            <th>Contact No</th>
            <th>Address </th>
            <th>Action </th>
          </tr>
        </thead>
        <tbody>

          <?php 
            $query = "SELECT * FROM supplier";
            $query_run = mysqli_query($con, $query);

            if(mysqli_num_rows($query_run)>0){
              foreach($query_run as $supplier){
                 
                ?>
                <tr>
                  <td class="user_id"><?= $supplier['id'];?></td>
                  <td><?= $supplier['name'];?></td>
                  <td><?= $supplier['email'];?></td>
                  <td><?= $supplier['phone'];?></td>
                  <td><?= $supplier['address'];?></td>
                  <td>
                    <a href="" class="btn btn-info btn-sm view_data">View</a>
                    <a href="#" class="btn btn-success btn-sm edit-data">Edit</a>
                    <a href="" class="btn btn-danger btn-sm">Delete</a>
                    
                  </td>
                </tr>
                <?php
              }
            }

            else{
              echo "<h5>No Record Found</h5>";
            }
          ?>
          
        
        </tbody>
      </table>

      

    </div>
  </div>
</div>
    <!--Allert Show Message start-->
    <?php include('message.php'); ?>
    <!--Allert Show Message end-->
</div>
<!-- /.container-fluid -->

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>

<script>
  $(document).ready(function(){
    $('.view_data').click(function (e){
      e.preventDefault();

      // console.log('Hello');

      var user_id = $(this).closest('tr').find('.user_id').text();
      // console.log(user_id);

      $.ajax({
        method: "POST",
        url: "supplier.php",
        data: {
          'click_view_btn': true,
          'user_id': user_id,
        },
        success: function (response){
            console.log(response);                                                                                                                 
        }
      });

    })
  });
</script>