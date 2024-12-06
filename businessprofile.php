<?php
include 'database-php/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
//echo "<h1>" . $_SESSION['user_id'] . "</h1>";
    header("Location: index.php");
}

$user_num = $_SESSION['user_id'];

// Prepare SQL statement to retrieve post for the current customer
$stmt = $db->prepare("SELECT * FROM users u join business_reg br using(user_id) join business_profile bp using (bus_id) WHERE user_id = :user_num");
$stmt->bindParam(':user_num', $user_num);
$stmt->execute();

if (!$stmt) {
    die("Query failed: " . $db->errorInfo());
}

$userProfile = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($userProfile) > 0) {
    foreach ($userProfile as $user) {
        $user["fname"] . "<br>";
        $user["lname"] . "<br>";
        $user["img"] . "<br>";
        $user["bio"] . "<br>";
        $user["date_register"] . "<br>";
        $user["bus_name"] . "<br>";


    }
} else {
    echo "No user found with ID: $user_num";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>John Doe - Profile</title>
    <link rel="stylesheet" href="">
    <style>

        /* General page styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #fbe9db; /* Light beige background */
            color: #333; /* Dark gray text */
            margin: 0;
            padding: 0;
            background-color: #2E3944;
            
        }

        /* Header */
        header {
            background-color: #D3D9D4; /* Light beige header background */
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            margin: 0;
            font-size: 1.8rem;
            color: #333; /* Dark gray text */
        }

        header nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #333; /* Dark gray text for nav links */
            font-weight: bold;
        }

        /* Profile container and card */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .profile-header {
            background-color: #D3D9D4; /* Light beige background for profile card */
            border-radius: 8px;
            padding: 15px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cover-photo {
            width: 100%;
            height: 120px;
            border-radius: 8px 8px 0 0;
            background-color: #748D92; /* Light pink color */
        }

        .profile-picture {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-top: -40px;
            border: 4px solid white;
        }

        .profile-name {
            margin: 10px 0;
            font-size: 1.4rem;
            font-weight: bold;
        }

        .edit-button {
            background-color: #748D92; /* Muted brown button */
            border: none;
            color: black;
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 1rem;
            cursor: pointer;
            margin: 10px 0;
        }

        .edit-button:hover {
            opacity: 0.9;
        }

        .profile-section {
            background-color: #d3d9d4; /* Light beige background for bio section */
            border-radius: 8px;
            padding: 20px;
            width: 80%;
            max-width: 600px;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000; /* Change text color to black */
        }

        .profile-bio {
            font-size: 1rem;
            color: #000; /* Darker gray text for the bio */
            text-align: center; /* Center the text horizontally */
        }

        .modal {
            display: none; /* Hidden by default */
            position: fixed;  Stay in place */
            z-index: 1000; /* Sit on top of other content */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            background-color: rgba(0, 0, 0, 0.7); /* Black background with more transparency */
            display: none; /* Flex for centering */
            justify-content: center; /* Center modal horizontally */
            align-items: center; /* Center modal vertically */
            backdrop-filter: blur(5px); /* Slight blur effect for the background */
        }

        /* Modal Content Box */
        .modal-content {
            background: linear-gradient(135deg, #f0f8ff, #dcdcdc); /* Soft light gradient background */
            position: relative; /* For positioning the close button */
            padding: 30px; /* Padding around content */
            border-radius: 16px; /* Rounded corners */
            width: 80%; /* Width relative to viewport */
            max-width: 480px; /* Limit the modal width */
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2); /* Subtle shadow for emphasis */
            font-family: 'Roboto', sans-serif; /* Clean modern font */
            animation: zoomIn 0.4s ease-in-out; /* Smooth zoom-in animation */
            overflow: hidden; /* Hide overflow for smoother animations */
        }

        /* Close Button Styling */
        .close {
            color: #444; /* Muted color */
            font-size: 28px; /* Larger size for emphasis */
            font-weight: bold;
            position: absolute; /* Positioned over modal content */
            top: 15px;
            right: 20px;
            cursor: pointer; /* Pointer cursor */
            border: none;
            background: none;
            transition: color 0.3s ease, transform 0.3s ease; /* Smooth hover effect */
        }

        .close:hover {
            color: #ff6347; /* Tomato color on hover */
            transform: rotate(90deg); /* Rotate effect */
        }

        /* Section Title */
        .section-title {
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            background:(90deg, #000); /* Gradient text */
            -webkit-background-clip: text;
        }

        /* Form Styling */
        .edit-profile label {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #555;
            display: block;
        }

        .edit-profile input,
        .edit-profile textarea {
            width: 100%; /* Full width */
            padding: 12px; /* Padding inside fields */
            margin-bottom: 20px; /* Space between inputs */
            border: 2px solid #ccc; /* Light border */
            border-radius: 10px; /* Smooth corners */
            box-sizing: border-box;
            font-size: 16px;
            font-family: 'Roboto', sans-serif;
            background-color: #f7f9fc; /* Light background */
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .edit-profile input:focus,
        .edit-profile textarea:focus {
            border-color: #007bff; /* Highlighted border on focus */
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2); /* Soft glow effect */
            outline: none; /* Remove default outline */
        }

        /* Submit Button */
        .edit-profile button {
            display: inline-block;
            width: 100%; /* Full width */
            padding: 14px 18px; /* Larger padding */
            font-size: 18px;
            font-weight: 600;
            background: linear-gradient(90deg, #007bff, #00c6ff); /* Gradient button */
            color: #fff; /* White text */
            border: none;
            border-radius: 12px; /* Rounded corners */
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.3); /* Subtle shadow */
        }

        .edit-profile button:hover {
            background: linear-gradient(90deg, #0056b3, #008ae6); /* Darker gradient on hover */
            transform: translateY(-2px); /* Slight lift effect */
        }

        /* Placeholder Styling */
        .edit-profile input::placeholder,
        .edit-profile textarea::placeholder {
            color: #bbb; /* Muted placeholder text */
            font-style: italic;
        }

        /* Animation for Modal Appearance */
        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .modal-content {
                width: 90%;
                padding: 20px;
            }

            .section-title {
                font-size: 20px;
            }

            .edit-profile button {
                font-size: 16px;
            }
        }
    </style>
    
</head>
<body>
<header>
        <h1>CSU Commerce Hub</h1>
        <nav>
            <a href="businesshome.php"><b>Home</b></a>
            <a href="post.php"><b>Create Post</b></a>
            <a href="businessprofile.php"><b>Profile</b></a>
            <a href="logout.php"><b>Logout</b></a>

        </nav>
    </header>


    <div class="container">
        <div class="profile-header">
            <div class="cover-photo"></div>
            <img src="database-php/<?php echo $user["img"]; ?>" alt="John Doe" class="profile-picture">
            <h1 class="business-name" id="displayName" ><?php echo $user["bus_name"]; ?></h1>
            <p><?php echo $user["fname"]; ?> <?php echo $user["lname"]; ?></p>
            <p><?php echo $user["date_register"]; ?></p>
            <button class="edit-button" id="editBtn">Edit Profile</button>
        </div>
        
        <div class="profile-section">
            <h2 class="section-title">BIO</h2>
            <p class="profile-bio" id="displayBio"><?php echo $user["bio"]; ?></p>
        </div>
    </div>

    <!-- Modal for Editing Profile -->
    <div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditModal">&times;</span>
        <h2 class="section-title">EDIT PROFILE</h2>

        <form action="database-php/editbusiness.php" method="POST" id="editForm" class="edit-profile" enctype="multipart/form-data">
            <label for="profilePicture">Profile Picture:</label>
            <input type="file" id="profilePicture" name="profilePicture" accept="image/*">
            
            <label for="bus_name">Business Name:</label>
            <input type="text" id="bus_name" name="bus_name" placeholder="Mamai and Ate's Business" required>

            <label for="bio">Bio:</label>
            <textarea id="bio" name="bio" rows="4" placeholder="Hi, I'm new here! Please be nice to me." required></textarea>

            <button type="submit">Save Changes</button>
        </form>

    </div>
</div>

    <script src="js/businessprofile.js"></script>

</body>
</html>
