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
    <link rel="stylesheet" href="css/register.css">
    <title>register</title>

</head>
<body>

<div class="card">
    <h2>Register</h2>
    <form action="database-php/registerdb.php" method="post">
        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" required>

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Register">
    </form>
</div>

</body>
</html>