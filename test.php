<?php
session_start();
include('includes/header.php'); 
include('includes/navbar.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Button trigger modal -->
<button type="button" class="btn btn-primary ml-3" data-bs-toggle="modal" data-bs-target="#insertdata">
  Insert Data
</button>

<!-- Show Inser data status -->
 <?php
    if(isset($_SESSION['status']) && $_SESSION['status'] !=''){
        echo $_SESSION['status'];
        unset($_SESSION['status']);
    }
 ?>

<!-- Modal -->
<div class="modal fade" id="insertdata" tabindex="-1" aria-labelledby="insertdataLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title fs-5" id="insertdataLabel">Insert Data</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="code.php" method="POST">
        <div class="modal-body">
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" placeholder="Enter Name">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Enter Email">
            </div>

            <div class="form-group">
                <label for="number">Phone</label>
                <input type="number" class="form-control" name="phone" placeholder="Enter Phone">
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" name="address" placeholder="Enter Address">
            </div>

            

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="save_data" class="btn btn-primary">Save Data</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>



<?php
include('includes/scripts.php');
include('includes/footer.php');
?>