<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash the input password to compare with the hashed password in the database

    // Database connection
    $servername = "localhost";
    $username = "root";
    $dbpassword = "";  // Changed variable name to avoid conflict with $password
    $dbname = "only_hunger_db";
    $port = 3306;

    $conn = new mysqli($servername, $username, $dbpassword, $dbname, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM volunteers WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['email'] = $email;
        header('Location: donar-view.php'); // Redirect to a welcome page or dashboard
        exit();
    } else {
        echo "Invalid email or password.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Login</title>
    <style>
        body {
            padding-top: 70px; /* Adjust this value based on the height of your navbar */ 
            width: 100%;
            height: 100vh;
            background-image: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)), url(background.jpg);
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8); /* Slightly transparent white */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px; /* Adjust width as needed */
            width: 100%;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column; /* Stack elements vertically */
            align-items: center; /* Center align elements */
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ced4da;
            border-radius: 5px;
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
            padding: 10px 20px;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: #00796b;
            box-shadow: 0 4px 8px rgba(0, 121, 107, 0.2);
        }

        h2 {
            margin-bottom: 20px;
            color: #000;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #000;
        }

        input[type="email"], input[type="password"], input[type="submit"] {
            margin-top: 10px;
        }

        input[type="submit"] {
            background-color: #009688;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #00796b;
            box-shadow: 0 4px 8px rgba(0, 121, 107, 0.2);
        }

    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="container">
        <h2>Volunteer Login</h2>
        <form method="POST" action="lol.php">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" required><br>
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" required><br>
            <button type="submit" class="btn-primary">Login</button>
        </form>
    </div>
</body>
</html>
