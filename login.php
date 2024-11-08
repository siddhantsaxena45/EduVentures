<?php

session_start();


if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    
    header("Location: index.php");
    exit;
}


$loginError = false;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    include 'partials/_dbconnect.php';

    
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    
    $num = mysqli_num_rows($result);
    if ($num == 1) {
    
        $row = mysqli_fetch_assoc($result);

        
        if (password_verify($password, $row['password'])) {
            
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $row['id'];


            header("Location: index.php");
            exit;
        } else {
            
            $loginError = "Invalid Credentials.";
        }
    } else {
       
        $loginError = "Invalid Credentials.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EduVentures - A Quiz Based Online Platform</title>
    <link rel="icon" href="assets/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
   
    require "partials/nav.php";
    ?>

    <div class="main">
        <section class="hero">
            <img src="assets/hero.png" alt="" class="heroback">
            <div class="hero-content">
                <?php
                
                if ($loginError) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Login Failed! </strong> ' . $loginError . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
                }
                ?>
                <div class="container">
                    <h1>Login</h1>
                
                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <h5>don't have an account? <a href="signup.php" style="color:white">Sign Up</a> here </h5>
                    </form>
                </div>
            </div>
        </section>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
