<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Form</title>

    <style>
        body{
            background-color: #2E3944;
        }
        header {
            position: fixed; /* Make the header fixed at the top */
            top: 0; /* Align to the top of the viewport */
            left: 0; /* Align to the left */
            right: 0; /* Align to the right */
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #D3D9D4;
            padding: 0px 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000; /* Ensure the header stays on top of other elements */
            font-family: Arial, sans-serif;
            padding-right: 3%;
            color: black; /* Set header text color to black */
        }
        header h1, header nav a {
            color: black; /* Ensure both the title and navigation links are black */
        }

        nav {
            display: flex;
            align-items: center;
        }

        nav a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            margin-left: 20px;
            transition: text-decoration 0.3s ease; /* Add transition for smooth effect */
        }

        nav a:hover {
            text-decoration: underline; /* Add underline on hover */
            color: black; /* Keep the original color */
            

        }

        main {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 100px 20px 20px; /* Add top padding to account for the header height */
            min-height: calc(100vh - 80px);
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 600px;
            margin-top: 20px;
        }

        .form-group {
            margin: 20px 0;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        textarea, select, input[type="text"], input[type="number"], input[type="date"], input[type="file"] {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-top: 5px;
            box-sizing: border-box; /* Ensure padding is included in width */
        }

        input[type="submit"] {
            background: #D3D9D4;
            width: 100%;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.3s;
            color: black; /* Set header text color to black */
        }

        input[type="submit"]:hover {
            background: linear-gradient(135deg,#F5BCBA, #E3AADD, #C8A8E9, #C3C7F3);
            color: black; /* Set header text color to black */
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
<main>
    <h1>Create Post</h1>
    <div class="card">
        <form action="postdb.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="type">Type:</label>
                <select id="type" name="type_" required>
                    <option value="">Select type</option>
                    <option value="Food & Drinks">Food & Drinks</option>
                    <option value="Nail Care">Nail Care</option>
                    <option value="School Supplies">School Supplies</option>
                    <option value="Technology/Gadgets">Technology/Gadgets</option>
                    <option value="Clothing & Accessories">Clothing & Accessories</option>
                    <option value="Books/Reading Materials">Books/Reading Materials</option>
                    <option value="Beauty & Personal Care">Beauty & Personal Care</option>
                    

                </select>
            </div>

            <div class="form-group date-price-group">

                <div class="price-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label for="pictures">Upload Pictures:</label>
                <input type="file" id="pictures" name="pictures" accept="image/*" multiple>
            </div>

            <input type="submit" value="Submit">
        </form>
    </div>
</main>
<script src="js/post.js"></script>
</body>
</html