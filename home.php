<?php
include 'database-php/regular_user.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_num = $_SESSION['user_id'];

// Check if the user exists
$userCheckStmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
$userCheckStmt->execute([$user_num]);
$userExists = $userCheckStmt->fetch();

if (!$userExists) {
    die("No user found with ID: $user_num");
}

// Prepare SQL statement to retrieve posts from all businesses
$stmt = $db->prepare("
    SELECT 
        rg.bus_name, 
        bp.img AS profile_img, 
        p.img AS post_img, 
        p.type_, 
        p.description_, 
        p.date_posted, 
        p.price,
        p.post_id,
        u.user_id,
        rg.bus_id,
        (SELECT COUNT(*) FROM COMMENTS c WHERE c.post_id = p.post_id) AS comments_count
    FROM users u 
    JOIN business_reg rg USING(user_id) 
    JOIN business_profile bp USING(bus_id) 
    JOIN post p USING(buspro_id)
    ORDER BY p.date_posted DESC
");
$stmt->execute();

if (!$stmt) {
    die("Query failed: " . implode(", ", $stmt->errorInfo()));
}

$allPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/home.css">
    <style>
        /* Your existing CSS styles here */
       /* Header */
       header {
        background-color: #D3D9D4; /* Light beige header background */
        padding: 0px 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: black; /* Set header text color to black */
        }

        header h1, header nav a {
            color: black; /* Ensure both the title and navigation links are black */
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #fbe9db; /* Light beige background */
            color: #333; /* Dark gray text */
            margin: 0;
            padding: 0;
            background-color: #2E3944;
            
        }
        .container {
            padding: 50px;
        }
        .post {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
            padding: 40px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
        }
        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .post-info {
            flex-grow: 1;
        }
        .business-name {
            font-weight: bold;
            margin: 0;
        }
        .post-meta {
            color: #65676B;
            font-size: 0.9em;
            margin: 0;
        }
        .post-content {
            margin-bottom: 10px;
        }
        .post-image {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .post-options {
            position: absolute;
            top: 17px;
            right: 15px;
        }
        .options-btn {
            margin-top: 20px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
        }
        .options-dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 120px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .options-dropdown a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .options-dropdown a:hover {
            background-color: #D3D9D4;
        }
        .comment-section {
            margin-top: 20px;
            border-top: 1px solid #e0e0e0;
            padding-top: 20px;
        }
        .comment {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .comment-date {
            font-size: 0.85em;
            color: gray;
        }
        .comment-author {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .comment-form {
            margin-top: 20px;
            width: 96%;
        }
        .comment-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }
        .comment-form button {
            background: #D3D9D4;
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .comment-form button:hover {
            background: linear-gradient(135deg,#F5BCBA, #E3AADD, #C8A8E9, #C3C7F3);
        }
        .search-bar {
            display: flex;
            align-items: center;
            position: relative;
        }
        .search-input {
            width: 250px;
            padding: 10px;
            border: 1px solid #F4E7FB;
            border-radius: 20px;
            margin-right: 8px;
        }
        .search-input:focus {
            border-color: #F5BCBA;
            outline: none;
        }
        .search-button {
            background-color: transparent;
            color: black;
            border: 2px solid #F5BCBA;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
        }
        .search-button:hover {
            background-color: white;
        }
        .comment-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .comment-content {
            margin-top: 10px; 
            padding: 10px; 
            background-color: #f9f9f9; 
            border-radius: 5px; 
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
    </style>
</head>
<body>
    <header>
        <h1>CSU Commerce Hub</h1>
        <div class="search-bar">
            <input type="text" class="search-input" placeholder="Search...">
            <button class="search-button" onclick="performSearch()">Search</button>
        </div>
        <nav>
            <a id="registerBtn"><b>Business Register</b></a>
            <a href="profile.php"><b>Profile</b></a>
            <a href="logout.php"><b>Logout</b></a>
        </nav>
    </header>

    <div class="container">
        <?php if (empty($allPosts)): ?>
            <p>No posts found.</p>
        <?php else: ?>
            <?php foreach ($allPosts as $post): ?>
                <div class="post">
                    <div class="post-options">
                        <button class="options-btn" onclick="toggleDropdown(this)">⋮</button>
                        <div class="options-dropdown">
                            <a href="#" onclick="savePost(this)">Save</a>
                            <?php if ($post['user_id'] == $user_num): ?>
                                <a href="#" onclick="deletePost(this, <?php echo $post['post_id']; ?>)">Delete</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="post-header">
                        <a href="viewbusiness.php?user_id=<?php echo htmlspecialchars($post['user_id']); ?>">
                            <img src="<?php echo htmlspecialchars($post['profile_img']); ?>" alt="Profile Picture" class="profile-pic">
                        </a>
                        <div class="post-info">
                            <h3 class="business-name"><?php echo htmlspecialchars($post['bus_name']); ?></h3>
                            <p class="post-meta">
                                <span class="post-type"><?php echo htmlspecialchars($post['type_']); ?></span> &nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;&nbsp; 
                                <span class="post-date"><?php echo date("m/d/Y h:i A", strtotime($post['date_posted'])); ?></span> &nbsp;&nbsp;&nbsp;•&nbsp;&nbsp;&nbsp; 
                                <span class="post-price">₱<?php echo htmlspecialchars($post['price']); ?></span>
                            </p>

                        </div>
                    </div>
                    <div class="post-content">
                        <p><?php echo htmlspecialchars($post['description_']); ?></p>
                    </div>
                    <?php if (!empty($post['post_img'])): ?>
                        <img src="<?php echo htmlspecialchars($post['post_img']); ?>" alt="Post Image" class="post-image">
                    <?php endif; ?>

                    <?php
                    $commentStmt = $db->prepare("
                        SELECT c.content, c.date_commented, up.img, u.fname, u.lname
                        FROM COMMENTS c
                        JOIN USERS u ON c.user_id = u.user_id
                        JOIN USER_PROFILE up ON u.user_id = up.user_id
                        WHERE c.post_id = ?
                        ORDER BY c.date_commented ASC
                    ");
                    $commentStmt->execute([$post['post_id']]);
                    $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

<div class="comment-section">
                        <h4>
                            Comments 
                            <span id="comment-count-<?php echo $post['post_id']; ?>">
                                <?php echo htmlspecialchars($post['comments_count']); ?>
                            </span>
                            <i class="fa fa-comment" aria-hidden="true"></i>
                        </h4>
                        <div id="comments-<?php echo $post['post_id']; ?>">
                            <?php
                            if (count($comments) > 0) {
                                foreach ($comments as $comment) {
                                    echo '    <div style="display: flex; align-items: flex-start; margin-bottom: 20px;">';  // Flex layout with start alignment
                                    echo '        <img src="' . htmlspecialchars($comment['img']) . '" alt="User profile picture" class="comment-pic" style="margin-right: 15px; width: 40px; height: 40px; border-radius: 50%;">';  // Profile image
                                    echo '        <div style="flex-grow: 1;">';  // Make the content take remaining space
                                    echo '            <div class="comment-author" style="font-weight: bold; font-size: 1.1em;">' . htmlspecialchars($comment['fname']) . ' <span style="font-weight: lighter; color: #555;">@' . htmlspecialchars($comment['lname']) . '</span></div>';
                                    echo '            <div class="comment-date" style="font-size: 0.9em; color: #888; margin-bottom: 5px;">' . date("F j, Y", strtotime($comment['date_commented'])) . '</div>';
                                    echo '            <div class="comment-content" style="font-size: 1em; line-height: 1.5; padding-left: 5px;">' . htmlspecialchars($comment['content']) . '</div>';  // Content aligned with name
                                    echo '        </div>';
                                    echo '    </div>';
                                }
                            } else {
                                echo '<p>No comments</p>'; 
                            }
                            ?>
                        </div>
                    </div>

                        <form class="comment-form" action="database-php/commentdb.php" method="post">
                            <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['post_id']); ?>">
                            <input type="hidden" name="profile_id" value="<?php echo htmlspecialchars($user_num); ?>">
                            <textarea placeholder="Write a comment..." name="comment" required></textarea>
                            <button type="submit">Post Comment</button>
                        </form>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Modal -->
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
        function toggleDropdown(btn) {
            btn.nextElementSibling.style.display = btn.nextElementSibling.style.display === "block" ? "none" : "block";
        }

        function savePost(link) {
            alert("Post saved!");
            link.closest('.options-dropdown').style.display = 'none';
        }

        function deletePost(link, postId) {
            if (confirm("Are you sure you want to delete this post?")) {
                fetch(`database-php/delete_post.php?post_id=${postId}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        link.closest('.post').remove();
                    } else {
                        alert("Failed to delete post. Please try again.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("An error occurred. Please try again.");
                });
            }
        }

        function performSearch() {
            const searchTerm = document.querySelector('.search-input').value.toLowerCase();
            const posts = document.querySelectorAll('.post');

            posts.forEach(post => {
                const businessName = post.querySelector('.business-name').textContent.toLowerCase();
                const postType = post.querySelector('.post-meta .post-type').textContent.toLowerCase();
                const description = post.querySelector('.post-content').textContent.toLowerCase();

                if (businessName.includes(searchTerm) || postType.includes(searchTerm) || description.includes(searchTerm)) {
                    post.style.display = 'block';
                } else {
                    post.style.display = 'none';
                }
            });
        }


        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.options-btn')) {
                var dropdowns = document.getElementsByClassName("options-dropdown");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.style.display === "block") {
                        openDropdown.style.display = "none";
                    }
                }
            }
        }

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
</body>
</html>
