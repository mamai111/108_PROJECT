<?php
include 'connect.php';

session_start();

// $profile_id = $_SESSION['profile_id'];
// $post_id = $_POST['post_id'];
// $comment = $_POST['comment'];

$post_id = $_POST['post_id'];
$profile_id = $_POST['profile_id'];
$comment = $_POST['comment'];

try {

    // Insert the comment into the COMMENTS table
    $stmt = $db->prepare("INSERT INTO COMMENTS (post_id, user_id, content) VALUES (:post_id, :profile_id, :comment)");
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':profile_id', $profile_id);
    $stmt->bindParam(':comment', $comment);
    $stmt->execute();

    // Redirect back to the same page after the action is completed
    header('Location: http://localhost/109%20PROJECT/home.php');
    header('Location: http://localhost/109%20PROJECT/businesshome.php');

} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>