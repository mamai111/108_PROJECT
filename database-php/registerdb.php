<?php

include 'connect.php';

$a = $_POST['firstname'];
$b = $_POST['lastname'];
$c = $_POST['email'];
$d = $_POST['password'];


//echo $a . "<br>" . $b . "<br>" . $c . "<br>" . $d . "<br>" . $e . "<br>" . $f . "<br>" . $g . "<br>" . $h . "<br>" . $i . "<br>";

$stmt = $db->prepare("INSERT INTO USERS (FNAME, LNAME, EMAIL,PASSWORD_) VALUES (:a, :b, :c,MD5(:d))");
$stmt->bindParam(':a', $a);
$stmt->bindParam(':b', $b);
$stmt->bindParam(':c', $c);
$stmt->bindParam(':d', $d);
$stmt->execute();

$user_num = $db->lastInsertId();
$default_profile_pic = 'img\defaultprofile.png';
$default_bio = 'Bugsay pa more.';

header('Location: http://localhost/109%20PROJECT/login.php');

$stmt = $db->prepare("INSERT INTO USER_PROFILE (bio,img,user_id) 
                    VALUES (:default_bio,:default_profile_pic,:user_num)");
$stmt->bindParam(':user_num', $user_num);  // Use the stud_id provided during registration
$stmt->bindParam(':default_profile_pic', $default_profile_pic);  // Use the last inserted user_num (primary key)
$stmt->bindParam(':default_bio', $default_bio);  // Profile name (fname + lname)

$stmt->execute();

header('Location: http://localhost/109%20PROJECT/login.php');
