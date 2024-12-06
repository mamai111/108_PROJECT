<?php
session_start();

if (isset($_SESSION['user_id'])) {
//echo "<h1>" . $_SESSION['user_id'] . "</h1>";
    header("Location: home.php");
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Login</title>
   
</head>

<body>

<div class="card">
    <h2 class="text-center">Login</h2>
    <form action="database-php/logindb.php" method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <div id="emailHelp" class="form-text mt-3">Forget password?</div>

        <button type="submit" class="btn btn-custom mt-3">Login</button>
        
        <div class="form-text mt-3">
            Don't have an account yet? <span><a href="http://localhost/109%20PROJECT/register.php">Register</a></span>
        </div>

        <div class="mt-4 text-center">
             <p>Or connect with:</p>
            <a href="http://localhost/109%20PROJECT/auth/google.php" class="btn btn-danger mx-2">
            <i class="fab fa-google"></i> Google
             </a>
            <a href="http://localhost/109%20PROJECT/auth/facebook.php" class="btn btn-primary mx-2">
            <i class="fab fa-facebook-f"></i> Facebook
            </a>
        </div>

    </form>
</div>

</body>
</html>
