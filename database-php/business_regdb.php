<?php

include 'connect.php';

session_start();

$a = $_POST['business_name'];
$b = $_POST['bio'];
$c = $_POST['registration_date'];
$d = $_SESSION['user_id'];

$stmt = $db->prepare("INSERT INTO BUSINESS_REG (bus_name, bio, date_register, user_id) VALUES (:a, :b, :c,:d)");
$stmt->bindParam(':a', $a);
$stmt->bindParam(':b', $b);
$stmt->bindParam(':c', $c);
$stmt->bindParam(':d', $d);
$stmt->execute();


$user_num = $db->lastInsertId();
$default_profile_pic = 'img\defaultprofile.png';
$default_bio = 'Business pa more';

$stmt = $db->prepare("INSERT INTO business_profile (bio,img,bus_id) 
                    VALUES (:default_bio,:default_profile_pic,:user_num)");
$stmt->bindParam(':user_num', $user_num);  // Use the stud_id provided during registration
$stmt->bindParam(':default_profile_pic', $default_profile_pic);  // Use the last inserted user_num (primary key)
$stmt->bindParam(':default_bio', $default_bio);  // Profile name (fname + lname)

$stmt->execute();

header('Location: http://localhost/109%20PROJECT/businesshome.php');
