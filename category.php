<?php
session_start();
require('dbcon.php');

/* code.php file start */

// Insert data start
if (isset($_POST['save_category_data'])) {
    $name = $_POST['name'];

    $insert_query = "INSERT INTO categories(categories_name) VALUES ('$name')";
    $insert_query_run = mysqli_query($con, $insert_query);

    if ($insert_query_run) {
        $_SESSION['status'] = "Data inserted successfully!";
    } else {
        $_SESSION['status'] = "Insertion of data failed!";
    }

    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
// Insert data end

// View data start
if (isset($_POST['click_view_btn'])) {
    $category_id = $_POST['category_id'];

    // Fetch category data based on $category_id from the database
    $query = "SELECT * FROM categories WHERE categories_id = '$category_id'";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $category_data = mysqli_fetch_assoc($query_run);
        // Assuming you want to return the data in some format (e.g., HTML or JSON)
        // Here is an example of returning the data as HTML
        echo "<p>Name: " . $category_data['categories_name'] . "</p>";
    } else {
        echo "No data found for this category.";
    }
    exit; // Stop further execution since this is an AJAX request
}
// View data end

// Edit data start
if (isset($_POST['click_edit_btn'])) {
    $category_id = $_POST['category_id'];
    $arrayresult = [];

    $fetch_query = "SELECT * FROM categories WHERE categories_id='$category_id'";
    $fetch_query_run = mysqli_query($con, $fetch_query);

    if (mysqli_num_rows($fetch_query_run) > 0) {
        while ($row = mysqli_fetch_array($fetch_query_run)) {
            array_push($arrayresult, $row);
            header('content-type: application/json');
            echo json_encode($arrayresult);
            exit;
        }
    } else {
        echo '<h4>No record found</h4>';
    }
}
// Edit data end

// Update data start
if (isset($_POST['update_data'])) {
    $category_id = $_POST['id'];
    $name = $_POST['name'];

    $update_query = "UPDATE categories SET categories_name='$name' WHERE categories_id='$category_id'";
    $update_query_run = mysqli_query($con, $update_query);

    if ($update_query_run) {
        $_SESSION['status'] = "Data updated successfully!";
        header('Location: category.php');
        exit;
    } else {
        $_SESSION['status'] = "Data updation failed!";
        header('Location: category.php');
        exit;
    }
}
// Update data end

// Delete data start
if (isset($_POST['click_delete_btn'])) {
    $category_id = $_POST['category_id'];

    $delete_query = "DELETE FROM categories WHERE categories_id='$category_id'";
    $delete_query_run = mysqli_query($con, $delete_query);

    if ($delete_query_run) {
        $_SESSION['status'] = "Data deleted successfully!";
        exit;
    } else {
        $_SESSION['status'] = "Data deletion failed!";
        exit;
    }
}
// Delete data end

/* code.php FILE End */
?>

<!-- ................................................................................category.php file start............................................................ -->

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

<!-- Insert Modal Start -->
<div class="modal fade" id="insertdata" tabindex="-1" role="dialog" aria-labelledby="insertdataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="insertdataLabel">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="name">Category Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="save_category_data" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Insert Modal End -->

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
                <div class="view_user_data"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- View Modal End -->

<!-- Edit Modal Start -->
<div class="modal fade" id="editdata" tabindex="-1" role="dialog" aria-labelledby="editdataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editdataLabel">Edit Category Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <input type="hidden" class="form-control" id="category_id" name="id">
                    </div>

                    <div class="form-group mb-3">
                        <label for="name">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="update_data" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Modal End -->

<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Success message show start -->
            <?php
            if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
            ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
                unset($_SESSION['status']);
            }
            ?>
            <!-- Success message show end -->

            <!-- Manage categories card -->
            <div class="card">
                <div class="card-header">
                    <h4 class="text-dark fw-bold">MANAGE CATEGORIES</h4>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#insertdata">
                        Add Category
                    </button>
                </div>

                <div class="card-body bg-light" style="max-height: 60vh; overflow-y: auto;">
                    <table class="table table-bordered table-hover" width="100%" p-3>
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">View</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $fetch_query = "SELECT * FROM categories";
                            $fetch_query_run = mysqli_query($con, $fetch_query);

                            if (mysqli_num_rows($fetch_query_run) > 0) {
                                while ($row = mysqli_fetch_array($fetch_query_run)) {
                            ?>
                                    <tr>
                                        <td class="category_id"><?php echo $row['categories_id']; ?></td>
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
    // View data start
    $(document).ready(function() {
        $('.view_data').click(function(e) {
            e.preventDefault();

            var category_id = $(this).closest('tr').find('.category_id').text();

            $.ajax({
                method: "POST",
                url: "category.php",
                data: {
                    'click_view_btn': true,
                    'category_id': category_id,
                },
                success: function(response) {
                    $('.view_user_data').html(response);
                    $('#viewuser').modal('show');
                }
            });
        });
    });
    // View data end

    // Edit data start
    $(document).ready(function() {
        $('.edit_data').click(function(e) {
            e.preventDefault();

            var category_id = $(this).closest('tr').find('.category_id').text();

            $.ajax({
                method: "POST",
                url: "category.php",
                data: {
                    'click_edit_btn': true,
                    'category_id': category_id,
                },
                success: function(response) {
                    $.each(response, function(key, value) {
                        $('#category_id').val(value['categories_id']);
                        $('#name').val(value['categories_name']);
                    });

                    $('#editdata').modal('show');
                }
            });
        });
    });
    // Edit data end

    // Delete data start
    $(document).ready(function() {
        $('.delete_btn').click(function(e) {
            e.preventDefault();

            var category_id = $(this).closest('tr').find('.category_id').text();

            $.ajax({
                method: "POST",
                url: "category.php",
                data: {
                    'click_delete_btn': true,
                    'category_id': category_id,
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        });
    });
    // Delete data end
</script>