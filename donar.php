<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Donor CRUD</title>
    <style>
        body {
            padding-top: 90px; /* Adjust this value based on the height of your navbar */
            width: 100%;
            height: 100vh;
            background-image: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)), url(background.jpg);
            background-size: cover;
            background-position: center;
        }

        .card {
            background-color: #fff; /* White background color */
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color: #fff; /* White background color */
            color: #000; /* Black text color */
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            color: #000; /* Black text color */
        }

        .card-body label {
            color: #000; /* Black color for labels */
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white */
            color: #000; /* Black color for input text */
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 1); /* Solid white on focus */
            color: #000; /* Black color for input text on focus */
            box-shadow: 0 0 5px rgba(0, 150, 136, 0.5);
            border-color: #009688;
        }

        .form-control:hover {
            background-color: rgba(255, 255, 255, 0.95); /* Slightly more opaque on hover */
            box-shadow: inset 0 1px 5px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #009688;
            border: none;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .btn-primary:hover {
            background-color: #00796b;
            box-shadow: 0 4px 8px rgba(0, 121, 107, 0.2);
        }

        table {
            color: #000; /* Black color for table text */
        }

        table th, table td {
            color: #000; /* Ensure table header and cell text are black */
        }

        .form-container {
            background-color: rgba(0, 0, 0, 0.7); /* Dark background with 70% opacity */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            color: #fff;
            margin-top: 20px;
        }

        .form-container h4 {
            color: #fff;
            margin-bottom: 20px;
        }

        .form-container .form-control {
            margin-bottom: 15px;
        }

        .form-container .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<?php 
include 'connect.php'; 
?>

  
<div class="container mt-4">
    <?php include('message.php'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Donor Details</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Donor Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Food Type</th>
                                <th>Organisation</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                             
                            <?php 
                                include 'connect.php'; 
                                
                                $query = "SELECT * FROM donardetails";
                                $query_run = mysqli_query($conn2, $query);
                                
                                if ($query_run === false) {
                                    // Output the error message
                                    echo "Error: " . mysqli_error($conn);
                                } else {
                                    if (mysqli_num_rows($query_run) > 0) {
                                        foreach($query_run as $donar) {
                                            ?>
                                            <tr>
                                                <td><?= $donar['id']; ?></td>
                                                <td><?= $donar['name']; ?></td>
                                                <td><?= $donar['email']; ?></td>
                                                <td><?= $donar['phone']; ?></td>
                                                <td><?= $donar['food']; ?></td>
                                                <td><?= $donar['organisation']; ?></td>
                                                <td>
                                                    <a href="donar-view.php?id=<?= $donar['id']; ?>" class="btn btn-info btn-sm">View Full Details</a>
                                                    <a href="index2.html?id=<?= $donar['id']; ?>" class="btn btn-success btn-sm">Donation Completion</a>  
                                                    <form action="code.php" method="POST" class="d-inline">
                                                        <!-- <button type="submit" name="delete_donar" value="<?=$donar['id'];?>" class="btn btn-danger btn-sm">Delete</button> -->
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo "<h5>No Record Found</h5>";
                                    }
                                }
                            ?>
                                
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
