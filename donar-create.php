<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Link to your custom CSS -->

    <title>Donor Create</title>
    <style>
        body {
            padding-top: 70px; /* Adjust this value based on the height of your navbar */ 
            width: 100%;
            height: 100vh;
            background-image: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)), url(background.jpg);
            background-size: cover;
            background-position: center;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.2); /* White with 80% opacity */
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
            background-color: rgba(0, 0, 0, 0.5); /* Black with 50% opacity */
            color: #fff;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            color: #000;
        }

        .card-body label {
            color: #fff; /* White color for labels */
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white */
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 10px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 1); /* Solid white on focus */
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
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-5">
        <?php include('message.php'); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Donor Add 
                            <!-- <a href="donar.php" class="btn btn-danger float-end">BACK</a> -->
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <div class="mb-3">
                                <label>Donor Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Donor Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Donor Phone</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Food Type</label>
                                <input type="text" name="food" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Amount or Quantity of Food</label>
                                <input type="text" name="quantity" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Name of the Organisation</label>
                                <select name="organisation" class="form-control" required>
                                    <option value="">Select Organisation</option>
                                    <option value="Org1">Organisation 1</option>
                                    <option value="Org2">Organisation 2</option>
                                    <option value="Org3">Organisation 3</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="save_donar" class="btn btn-primary">Save Donor</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
