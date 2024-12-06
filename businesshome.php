<?php
include 'database-php/business_owner.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_num = $_SESSION['user_id'];

// Initialize the search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare SQL statement to retrieve posts from all businesses
$query = "
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
";

// Add search conditions if a search term is provided
if (!empty($searchTerm)) {
    $query .= " WHERE rg.bus_name LIKE :searchTerm 
                 OR p.type_ LIKE :searchTerm 
                 OR p.description_ LIKE :searchTerm";
}

$query .= " ORDER BY p.date_posted DESC";

// Prepare and execute the statement
$stmt = $db->prepare($query);

// Bind the search parameter if a search term is provided
if (!empty($searchTerm)) {
    $likeTerm = '%' . $searchTerm . '%';
    $stmt->bindParam(':searchTerm', $likeTerm);
}

$stmt->execute();

if (!$stmt) {
    die("Query failed: " . $db->errorInfo()[2]);
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
            transition: transform 0.2s;
        }
        .profile-pic:hover {
            transform: scale(1.1);
            cursor: pointer;
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
            background-color: #f1f1f1;
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
            background: #D3D9D4
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .comment-form button:hover {
            background: linear-gradient(135deg, #F4E7FB, #F3DCDC, #F5BCBA, #E3AADD, #C8A8E9, #C3C7F3);
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
            background-color:white;
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
    </style>
</head>
<body>
    <header>
        <h1>CSU Commerce Hub</h1>
        <div class="search-bar">
            <form action="" method="GET">
                <input type="text" class="search-input" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="search-button">Search</button>
            </form>
        </div>
        <nav>
            <a href="post.php"><b>Create Post</b></a>
            <a href="businessprofile.php"><b>Profile</b></a>
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
                                <a href="#" onclick="deletePost(this)">Delete</a>
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

        function deletePost(link) {
                link.closest('.post').remove();
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

        function addComment(event) {
            event.preventDefault(); // Prevent form submission

            const commentText = event.target.querySelector('textarea').value;
            const date = new Date().toLocaleString(); // Get current date and time

            const commentDiv = document.createElement('div');
            commentDiv.classList.add('comment');

            commentDiv.innerHTML = `
                <p>${commentText}</p>
                <span class="comment-date">${date}</span>
            `;

            document.getElementById('comments').appendChild(commentDiv);

            event.target.reset(); // Clear the textarea
        }
    </script>
    <script src="js/home.js"></script>
</body>
</html>


businesshome