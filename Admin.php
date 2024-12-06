<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="#dashboard">Dashboard</a></li>
            <li><a href="#manage-users">Manage Users</a></li>
            <li><a href="#manage-businesses">Manage Businesses</a></li>
            <li><a href="#logs">View Logs</a></li>
            <li><a href="#search">Search</a></li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <section id="dashboard">
            <h1>Welcome, Admin</h1>
            <p>Use the sidebar to navigate and manage the system.</p>
        </section>

        <section id="manage-users">
            <h2>Manage Users</h2>
            <button onclick="openModal('addUser')">Add User</button>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="userTable"></tbody>
            </table>
        </section>

        <section id="manage-businesses">
            <h2>Manage Businesses</h2>
            <button onclick="openModal('addBusiness')">Add Business</button>
            <table>
                <thead>
                    <tr>
                        <th>Business ID</th>
                        <th>Name</th>
                        <th>Owner</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="businessTable"></tbody>
            </table>
        </section>

        <section id="logs">
            <h2>Activity Logs</h2>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody id="logTable"></tbody>
            </table>
        </section>

        <section id="search">
            <h2>Search</h2>
            <input type="text" id="searchInput" placeholder="Search Users or Businesses">
            <button onclick="performSearch()">Search</button>
            <div id="searchResults"></div>
        </section>
    </div>

    <!-- Modal for Adding Data -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Add User</h2>
            <form id="modalForm">
                <!-- Form fields will be dynamically added -->
            </form>
            <button onclick="submitForm()">Submit</button>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
