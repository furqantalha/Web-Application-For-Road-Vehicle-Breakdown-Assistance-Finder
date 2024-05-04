<?php
session_start();
require_once "db.php";

// Check if the user is an adm
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id"])) {
        // Edit mechanic
        $id = $_POST["id"];
        $name = $_POST["name"];
        $contact = $_POST["contact"];
        $email = $_POST["email"];
        $status = $_POST["status"];

        $sql = "UPDATE mechanics_list SET name=?, contact=?, email=?, status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $contact, $email, $status, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Mechanic updated successfully.";
        } else {
            $_SESSION['error'] = "Error updating mechanic: " . $conn->error;
        }
        $stmt->close();
    } else {
        // Add new mechanic
        $name = $_POST["name"];
        $contact = $_POST["contact"];
        $email = $_POST["email"];
        $status = $_POST["status"];

        $sql = "INSERT INTO mechanics_list (name, contact, email, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $contact, $email, $status);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Mechanic added successfully.";
        } else {
            $_SESSION['error'] = "Error adding mechanic: " . $conn->error;
        }
        $stmt->close();
    }
    header("Location: mechanics.php");
    exit();
}

if (isset($_GET['id']) && $_GET['id'] > 0) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM mechanics_list WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $mechanic = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Mechanic not found.";
        header("Location: mechanics.php");
        exit();
    }
}

if ($_settings->chk_flashdata('success')) {
    echo '<script>
        alert_toast("' . $_settings->flashdata('success') . '", "success");
    </script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mechanics Management</title>
   <!-- Add styles for your HTML and form elements -->
<style>
    /* Global Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
    }

    /* Page Title */
    h1 {
        font-size: 24px;
        margin: 20px 0;
        text-align: center;
    }

    /* Form Styles */
    form {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-top: 10px;
    }

    input[type="text"],
    input[type="email"],
    select {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    select {
        height: 35px;
    }

    input[type="submit"] {
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 3px;
        padding: 5px 10px;
        cursor: pointer;
        font-weight: bold;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }
</style>

</head>
<body>
    <h2>Mechanics Management</h2>

    <?php if($_settings->chk_flashdata('success')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>", 'success');
    </script>
    <?php endif; ?>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">List of Mechanics</h3>
            <div class="card-tools">
                <a href="?page=mechanics/manage_mechanic" class="btn btn-flat btn-primary">
                    <span class="fas fa-plus"></span> Create New
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="5%">
                        <col width="15%">
                        <col width="30%">
                        <col width="25%">
                        <col width="10%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date Created</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT * from `mechanics_list` order by (`name`) asc ");
                        while ($row = $qry->fetch_assoc()):
                            foreach ($row as $k => $v) {
                                $row[$k] = trim(stripslashes($v));
                            }
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                            <td><?php echo ucwords($row['name']) ?></td>
                            <td>
                                <p class="m-0 lh-1">
                                    <?php echo $row['contact'] ?> <br>
                                    <?php echo $row['email'] ?>
                                </p>
                            </td>
                            <td class="text-center">
                                <?php if ($row['status'] == 1): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td align="center">
                                <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item" href="?page=mechanics/manage_mechanic&id=<?php echo $row['id'] ?>">
                                        <span class="fa fa-edit text-primary"></span> Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
                                        <span class="fa fa-trash text-danger"></span> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.delete_data').click(function(){
                _conf("Are you sure to delete this mechanic permanently?", "delete_mechanic", [$(this).attr('data-id')]);
            })
            $('.table').dataTable();
        })

        function delete_mechanic($id){
            start_loader();
            $.ajax({
                url: _base_url_+"classes/Master.php?f=delete_mechanic",
                method: "POST",
                data: {id: $id},
                dataType: "json",
                error: err => {
                    console.log(err);
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                },
                success: function(resp){
                    if (typeof resp == 'object' && resp.status == 'success'){
                        location.reload();
                    } else {
                        alert_toast("An error occurred.", 'error');
                        end_loader();
                    }
                }
            });
        }
    </script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
	</script>
    <!-- Include your JavaScript libraries and scripts here -->
	<form id="mechanic-form" action="process_mechanic.php" method="POST">
    <input type="hidden" name="id" value="0"> <!-- Use 0 for new mechanic, otherwise, set the mechanic ID -->
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="contact">Contact:</label>
        <input type="text" id="contact" name="contact" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="on-leave">On Leave</option>
        </select>
    </div>
    <button type="submit">Save</button>
</form>


</body>
</html>


<!-- Add styles for your HTML and form elements -->

<!-- Create a form for adding/editing mechanics -->

<!-- Create a table to list mechanics with options to edit and delete -->
