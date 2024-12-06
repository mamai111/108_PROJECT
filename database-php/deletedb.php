<?php

// Include database connection
include 'connect.php';

session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Get the data sent from the client
$data = json_decode(file_get_contents('php://input'), true);
$post_id = $data['post_id'] ?? null;

// Ensure post_id is provided
if (!$post_id) {
    echo json_encode(['success' => false, 'message' => 'Post ID is missing']);
    exit();
}

// Check if the post belongs to the logged-in user
$query = "SELECT user_id FROM post WHERE post_id = :post_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':post_id', $post_id);
$stmt->execute();

$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Verify the post exists and belongs to the user
if ($post && $post['user_id'] == $user_id) {
    // Delete the post
    $deleteQuery = "DELETE FROM post WHERE post_id = :post_id";
    $deleteStmt = $db->prepare($deleteQuery);
    $deleteStmt->bindParam(':post_id', $post_id);
    $deleteStmt->execute();

    if ($deleteStmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Post successfully deleted']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete the post']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Unauthorized or post not found']);
}

?>
