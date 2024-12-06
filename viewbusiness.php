<?php 
include 'database-php/connect.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_num = $_SESSION['user_id'];

// Check if user_id is set in the URL
if (isset($_GET['user_id'])) {
    $user_id = htmlspecialchars($_GET['user_id']); // Sanitize input

    // Prepare SQL statement to retrieve the user and business information
    $stmt = $db->prepare("
        SELECT 
            u.user_id,
            u.fname, 
            u.lname, 
            up.img, 
            bp.bio, 
            br.date_register, 
            br.bus_name 
        FROM users u 
        JOIN user_profile up ON u.user_id = up.user_id 
        LEFT JOIN business_reg br ON u.user_id = br.user_id 
        LEFT JOIN business_profile bp ON br.bus_id = bp.bus_id 
        WHERE u.user_id = :user_id
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if (!$stmt) {
        die("Query failed: " . implode(", ", $db->errorInfo()));
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Fetch business registration to determine user type
        $stmt = $db->prepare("SELECT COUNT(*) FROM business_reg WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_num, PDO::PARAM_INT);
        $stmt->execute();
        $isBusinessUser = $stmt->fetchColumn() > 0; // Check if the logged-in user has a business

        // Check if the profile being viewed is the logged-in user's profile
        $isOwnProfile = $user_id == $user_num;
    } else {
        echo "No user found with ID: $user_id";
        exit();
    }
} else {
    echo "No user ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?> - Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <style>
        header {
            background: linear-gradient(135deg, #F4E7FB, #F3DCDC, #F5BCBA, #E3AADD, #C8A8E9, #C3C7F3);
        }
    </style>
</head>
<body>
<header>
    <h1>CSU Commerce Hub</h1>
    <nav>
        <?php if ($isBusinessUser): ?>
            <a href="businesshome.php"><b>Home</b></a>
            <a href="post.php"><b>Create Post</b></a>
            <a href="businessprofile.php?bus_id=<?php echo $user_num; ?>"><b>Profile</b></a> <!-- Link to business profile -->
        <?php else: ?>
            <a href="home.php"><b>Home</b></a>
            <a id="registerBtn"><b>Business Register</b></a>
            <a href="profile.php?user_id=<?php echo $user_num; ?>"><b>Profile</b></a> <!-- Link to user profile -->
        <?php endif; ?>

        <a href="logout.php"><b>Logout</b></a>
    </nav>
</header>


<div class="container">
    <div class="profile-header">
        <div class="cover-photo"></div>
        <img src="<?php echo htmlspecialchars($user['img']); ?>" alt="Profile Picture" class="profile-picture">
        <h1 class="business-name" id="displayName"><?php echo htmlspecialchars($user['bus_name']); ?></h1>
        <p><?php echo htmlspecialchars($user['fname']) . ' ' . htmlspecialchars($user['lname']); ?></p>
        <p><?php echo htmlspecialchars($user['date_register']); ?></p>
        
        <?php if ($isOwnProfile): ?>
            <button class="edit-button" id="editBtn">Edit Profile</button>
        <?php endif; ?>
    </div>
    
    <div class="profile-section">
        <h2 class="section-title">BIO</h2>
        <p class="profile-bio" id="displayBio"><?php echo htmlspecialchars($user['bio']); ?></p>
    </div>
</div>

<!-- Modal for Editing Profile -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <div class="card">
            <h2>Business Registration</h2>
            <form action="database-php/business_regdb.php" method="POST">
                <label for="businessName">Business Name:</label>
                <input type="text" id="businessName" name="business_name" required>

                <label for="section-title">BIO:</label>
                <input type="text" id="displaybio" name="bio" required>

                <label for="registrationDate">Registration Date:</label>
                <input type="date" id="registrationDate" name="registration_date" required>

                <button type="submit" class="btn">Register</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Modal functionality
    const modal = document.getElementById("myModal");
        const btn = document.getElementById("registerBtn");
        const span = document.getElementById("closeModal");

        btn.onclick = function() {
            modal.style.display = "flex";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
</script>

<script src="js/businessprofile.js"></script>

</body>
</html>
