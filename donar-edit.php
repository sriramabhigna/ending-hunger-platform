<?php
session_start();
require 'dbcon.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Donar Edit</title>
</head>
<body>
  
    <div class="container mt-5">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Donar Edit 
                            <!-- <a href="index.php" class="btn btn-danger float-end">BACK</a> -->
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $donar_id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM donardetails WHERE id='$donar_id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                $donar = mysqli_fetch_array($query_run);
                                ?>
                                <form action="code.php" method="POST">
                                    <input type="hidden" name="donar_id" value="<?= $donar['id']; ?>">

                                    <div class="mb-3">
                                        <label>Donar Name</label>
                                        <input type="text" name="name" value="<?=$donar['name'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Donar Email</label>
                                        <input type="email" name="email" value="<?=$donar['email'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Donar Phone</label>
                                        <input type="text" name="phone" value="<?=$donar['phone'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Food Type</label>
                                        <input type="text" name="food" value="<?=$donar['food'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" name="update_donar" class="btn btn-primary">
                                            Update Donar
                                        </button>
                                    </div>

                                </form>
                                <?php
                            }
                            else
                            {
                                echo "<h4>No Such Id Found</h4>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>