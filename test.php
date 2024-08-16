<?php
session_start();
include('includes/header.php');
include('includes/navbar.php');
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
                <h5 class="modal-title" id="insertdataLabel">Add Suppliers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="code.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Supplier Name</label>
                        <input type="text" class="form-control" name="name" placeholder="enter name">
                    </div>

                    <div class="form-group">
                        <label for="email">Supplier Email</label>
                        <input type="text" class="form-control" name="email" placeholder="enter email">
                    </div>

                    <div class="form-group">
                        <label for="phone">Supplier Contact No</label>
                        <input type="text" class="form-control" name="phone" placeholder="enter number">
                    </div>

                    <div class="form-group">
                        <label for="address">Supplier Address</label>
                        <input type="text" class="form-control" name="address" placeholder="enter address">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="save_data" class="btn btn-primary">Add Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--Insert Modal End-->

<div class="container bg-white mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
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

            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark fw-bold">MANAGE SUPPLIERS</h4>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#insertdata">
                        Add Suppliers
                    </button>
                </div>

                <div class="card-body">

                </div>
            </div>
        </div>


    </div>
</div>


<?php
include('includes/scripts.php');
include('includes/footer.php');
?>