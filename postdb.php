<?php
include 'database-php/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

// Fetch bus_id
$stmt1 = $db->prepare("SELECT bus_id FROM business_reg WHERE user_id = :user_id");
$stmt1->bindParam(':user_id', $user_id);
$stmt1->execute();
$user = $stmt1->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Business not found for this user");
}

$bus_id = $user['bus_id'];

// Fetch buspro_id
$stmt2 = $db->prepare("SELECT buspro_id FROM business_profile WHERE bus_id = :bus_id");
$stmt2->bindParam(':bus_id', $bus_id);
$stmt2->execute();
$business = $stmt2->fetch(PDO::FETCH_ASSOC);

if (!$business) {
    die("Business profile not found");
}

$buspro_id = $business['buspro_id'];

// Process form data
$description_ = $_POST['description'];
$type_ = $_POST['type_'];
$registration_date = $_POST['registration_date'];
$price = $_POST['price'];

// Initialize $fileDestination
$fileDestination = null;

// Process file upload
if (isset($_FILES['pictures']) && $_FILES['pictures']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['pictures'];
    
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileExt, $allowed)) {
        if ($fileSize < 1000000) {
            // Check if the img directory exists
            if (!is_dir('img')) {
                mkdir('img', 0755, true);
            }

            $fileNameNew = uniqid('', true) . "." . $fileExt;
            $fileDestination = 'img/' . $fileNameNew;
            
            // Move the uploaded file
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                // File uploaded successfully
            } else {
                die("Failed to move uploaded file.");
            }
        } else {
            die("Your file is too big.");
        }
    } else {
        die("Invalid file type for picture.");
    }
} else {
    die("No file uploaded or an error occurred during upload.");
}

// Insert data into the database
try {
    $db->beginTransaction();

    $stmt3 = $db->prepare("INSERT INTO POST (img, type_, price, buspro_id, description_) 
        VALUES (:pictures, :type_, :price, :buspro_id, :description_)");
    $stmt3->bindParam(':pictures', $fileDestination);
    $stmt3->bindParam(':type_', $type_);
    $stmt3->bindParam(':price', $price);
    $stmt3->bindParam(':buspro_id', $buspro_id);
    $stmt3->bindParam(':description_', $description_);
    $stmt3->execute();

    $db->commit();

    echo "Post created successfully!";
} catch (Exception $e) {
    $db->rollBack();
    die("An error occurred: " . $e->getMessage());
}

// Redirect to the business home page
header('Location: http://localhost/109%20PROJECT/businesshome.php');
exit();
?>