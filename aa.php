<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
</head>

<body>
<!-- Modal Trigger Button -->
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        <h2>Contacts</h2>
        <ol class="breadcrumb">
            <li>
                <a href="home.php">Home</a>
            </li>
            <li class="active">
                <strong>Clients</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-3">
        <h5>Contact an Admin
            <!-- Modal trigger button -->
            <a href="#" class="add-user" data-toggle="modal" data-target="#studentaddmodal"><i class="fa fa-plus"></i></a>
        </h5>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="studentaddmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add a new user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal Body -->
            <form method="POST" class="profile-form" id="profile-form" enctype='multipart/form-data' action="reg_request.php">
                <div class="modal-body">
                    <!-- Your form fields go here -->
                    <div class="form-group">
                        <label for="name">First name</label>
                        <input class="form-control" id="name" name="name" type="text" placeholder="Enter your first name">
                    </div>
                    <!-- Add other form fields as needed -->
                </div>
                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="insertdata" id="insertdata" class="btn btn-primary">Save Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<!-- Custom Script -->
<script>
    // JavaScript to handle modal triggering
    document.addEventListener('DOMContentLoaded', function () {
        var modalTrigger = document.querySelector('.add-user');
        modalTrigger.addEventListener('click', function (event) {
            event.preventDefault();
            $('#studentaddmodal').modal('show');
        });
    });

    // JavaScript to handle form submission confirmation using SweetAlert2
    document.getElementById('insertdata').addEventListener('click', function (event) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to save the data!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, save it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.querySelector('form').submit();
            }
        });
    });
</script>
</body>

</html>
