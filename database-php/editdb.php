<?php
include 'connect.php';

session_start();

// Retrieve updated data from POST request (form input)
$a = $_POST['fname'];     // First Name
$b = $_POST['lname'];     // Last Name
$bio = $_POST['bio'];     // New Bio (can be optional)
$image = $_FILES['profilePicture']; // New Profile Image (can be optional)

$user_id = $_SESSION['user_id'];  // Assuming user is logged in and we have their user_id

// Check if the img directory exists, and create it if it doesn't
$uploadDir = 'img/';
if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        echo "Failed to create image directory.";
        exit();
    }
}

// Prepare and execute the database query to update the name
$stmt = $db->prepare("UPDATE USERS SET FNAME = :fname, LNAME = :lname WHERE user_id = :user_id");
$stmt->bindParam(':fname', $a);
$stmt->bindParam(':lname', $b);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

// Update bio (always updated, even if image isn't changed)
$stmt = $db->prepare("UPDATE USER_PROFILE SET bio = :bio WHERE user_id = :user_id");
$stmt->bindParam(':bio', $bio);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

// Check if an image was uploaded
if (isset($image) && $image['error'] === 0) {
    $fileName = $image['name'];
    $fileTmpName = $image['tmp_name'];
    $fileSize = $image['size'];
    $fileError = $image['error'];
    $fileType = $image['type'];

    // Debugging: Show file info
    echo "File Name: " . $fileName . "<br>";
    echo "File Size: " . $fileSize . "<br>";
    echo "File Type: " . $fileType . "<br>";

    // Extract file extension
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    // Allowed file types
    $allowed = array('jpg', 'jpeg', 'png');

    // Check if the file extension is allowed
    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000) { // 1MB size limit
                // Generate a unique file name
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = $uploadDir . $fileNameNew;

                // Move the uploaded file to the server directory
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                    echo "File uploaded successfully to: " . $fileDestination;
                    // Update the image in the database
                    $product_image = $fileDestination;
                    $stmt = $db->prepare("UPDATE USER_PROFILE SET img = :img WHERE user_id = :user_id");
                    $stmt->bindParam(':img', $product_image);
                    $stmt->bindParam(':user_id', $user_id);

                    // Check if the query executed successfully
                    if ($stmt->execute()) {
                        echo "Image updated in the database!";
                    } else {
                        echo "Failed to update image in the database!";
                        print_r($stmt->errorInfo());
                    }
                } else {
                    echo "Failed to upload the file!";
                }
            } else {
                echo "Your file is too big.";
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Invalid file type for profile image.";
    }
} else {
    echo "No file uploaded or file error occurred.";
}

// Redirect back to the profile page (after debugging)
header('Location: http://localhost/109%20PROJECT/profile.php');
exit();
?>
