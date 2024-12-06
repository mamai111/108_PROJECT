<?php

include 'connect.php';

session_start(); // Start the session

$a = $_POST['email'];
$b = $_POST['password'];

// Prepare a SQL statement to select user with the given username and password
$stmt = $db->prepare("SELECT * FROM users WHERE email = :a AND PASSWORD_ = MD5(:b)");
$stmt->bindParam(':a', $a);
$stmt->bindParam(':b', $b);
$stmt->execute();

// Fetch the first row returned by the query
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if a user was found
if ($user) {
// User found, store user information in session
    $_SESSION['user_id'] = $user['user_id']; // Assuming 'id' is the column in your 'users' table that uniquely identifies each user

    $businessStmt = $db->prepare("SELECT * FROM business_reg WHERE user_id = :user_id");
    $businessStmt->bindParam(':user_id', $user['user_id']);
    $businessStmt->execute();

    $business = $businessStmt->fetch(PDO::FETCH_ASSOC);

    if ($business) {
        // User has a business record, redirect to business home
        header('Location: http://localhost/109%20PROJECT/businesshome.php');
    } else {
        // No business record, redirect to user home
        header('Location: http://localhost/109%20PROJECT/home.php');
    }
    exit(); // Make sure no other code is executed after redirection

} else {
// User not found, redirect back to login page with an error message
    header('Location: http://localhost/109%20PROJECT/login.php?error=1');
    //alert() na function sa js
    exit();
}