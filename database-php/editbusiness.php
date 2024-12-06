<?php
include 'connect.php';

session_start();

// Retrieve updated data from POST request (form input)
$bio = $_POST['bio'] ?? null;       // New Bio (optional)
$bus_name = $_POST['bus_name'] ?? null; // New Business Name
$image = $_FILES['profilePicture'] ?? null; // New Profile Image (optional)

$user_id = $_SESSION['user_id'] ?? null;  // Assuming user is logged in and we have their user_id

if (!$user_id) {
    die("User ID is not set. Please log in.");
}

// Check if the img directory exists, and create it if it doesn't
$uploadDir = 'img/';
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        die("Failed to create image directory.");
    }
}

// Update the business profile
try {
    // Update the business name in the business_reg table if provided
    if ($bus_name !== null) {
        $stmt = $db->prepare("UPDATE business_reg SET bus_name = :bus_name WHERE user_id = :user_id");
        $stmt->bindParam(':bus_name', $bus_name);
        $stmt->bindParam(':user_id', $user_id);
        if ($stmt->execute()) {
            echo "Business name updated successfully.<br>";
        } else {
            echo "Failed to update business name.<br>";
        }
    }

    // Update the bio in BUSINESS_PROFILE if provided
    if ($bio !== null) {
        $stmt = $db->prepare("UPDATE BUSINESS_PROFILE SET bio = :bio WHERE bus_id = :user_id");
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':user_id', $user_id);
        if ($stmt->execute()) {
            echo "Bio updated successfully.<br>";
        } else {
            echo "Failed to update bio.<br>";
        }
    }

    // Check if an image was uploaded
    if ($image && $image['error'] === 0) {
        $fileName = $image['name'];
        $fileTmpName = $image['tmp_name'];
        $fileSize = $image['size'];
        $fileError = $image['error'];
        $fileType = $image['type'];

        // Extract file extension
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        // Allowed file types
        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileSize < 1000000) { // 1MB size limit
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = $uploadDir . $fileNameNew;

                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    // Update the image path in BUSINESS_PROFILE
                    $stmt = $db->prepare("UPDATE BUSINESS_PROFILE SET img = :img WHERE bus_id = :buspro_id");
                    $stmt->bindParam(':img', $fileDestination);  // Make sure this points to the correct column
                    $stmt->bindParam(':buspro_id', $user_id);  // Ensure this points to the correct identifier for the business
                    if ($stmt->execute()) {
                        echo "Image updated successfully in the business profile.<br>";
                    } else {
                        echo "Failed to update image in the business profile.<br>";
                    }
                } else {
                    echo "Failed to upload the file.<br>";
                }
            } else {
                echo "File size exceeds the limit of 1MB.<br>";
            }
        } else {
            echo "Invalid file type. Allowed types: jpg, jpeg, png.<br>";
        }
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Redirect back to the business profile page
header('Location: http://localhost/109%20PROJECT/businessprofile.php');
exit();
?>
